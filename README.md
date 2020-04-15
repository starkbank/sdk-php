# Stark Bank PHP SDK Beta

Welcome to the Stark Bank PHP SDK! This tool is made for PHP 
developers who want to easily integrate with our API.
This SDK version is compatible with the Stark Bank API v2.

If you have no idea what Stark Bank is, check out our [website](https://www.starkbank.com/) 
and discover a world where receiving or making payments 
is as easy as sending a text message to your client!

## Supported PHP Versions

This library supports the following PHP versions:

* PHP 5.6+

## Stark Bank API documentation

If you want to take a look at our API, follow [this link](https://docs.api.starkbank.com/?version=latest).

## Installation

To install the package with composer, add this to your composer deps:

```sh
"require": {
    "starkbank/sdk": "dev-master#0.0.1"
},
```

## Versioning

This project adheres to the following versioning pattern:

Given a version number MAJOR.MINOR.PATCH, increment:

- MAJOR version when the **API** version is incremented. This may include backwards incompatible changes;
- MINOR version when **breaking changes** are introduced OR **new functionalities** are added in a backwards compatible manner;
- PATCH version when backwards compatible bug **fixes** are implemented.

## Creating a Project

To connect to the Stark Bank API, you need user credentials. We currently have 2
kinds of users: Members and Projects. Given the purpose of this SDK, it only
supports Projects, which is a type of user made specially for direct API
integrations. To start using the SDK, create your first Sandbox Project in our 
[website](https://sandbox.web.starkbank.com) in the Project session.

Once you've created your project, load it in the SDK:

```php
use StarkBank\Project;

$project = new Project(
    "sandbox",
    "129817512982",
    "
    -----BEGIN EC PRIVATE KEY-----
    MHQCAQEEIOJ3xkQ9NRdMPLLSrX3OlaoexG8JZgQyTMdX1eISCXaCoBcGBSuBBAAK
    oUQDQgAEUneBQJsBhZl8/nPQd4YUe/UqEAtyJRH01YyWrg+nsNcSRlc1GzC3DB+X
    CPZXBUbsMQAbLoWXIN1pqIX2b/NE9Q==
    -----END EC PRIVATE KEY-----
    "
);
```

Once you are done testing and want to move to Production, create a new Project
in your Production account ([click here](https://web.starkbank.com)). Also,
when you are loading your Project, change the environment from `"sandbox"` to
`"production"` in the constructor shown above. 

NOTE: Never hard-code your private key. Get it from an environment variable, for example. 

## Setting up the user

You can inform the project to the SDK in two different ways.

The first way is passing the user argument in all methods, such as:

```php
use StarkBank\Balance;

$balance = Balance::get($project)
```

Or, alternatively, if you want to use the same project on all requests,
we recommend you set it as the default user by doing:

```php
use StarkBank\User;

User::setDefault($project);

$balance = Balance::get();
```

Just select the way of passing the project user that is more convenient to you.
On all following examples we will assume a default user has been set.

## Testing in Sandbox

Your initial balance is zero. For many operations in Stark Bank, you'll need funds
in your account, which can be added to your balance by creating a Boleto. 

In the Sandbox environment, 90% of the created Boletos will be automatically paid,
so there's nothing else you need to do to add funds to your account. Just create
a few and wait around a bit.

In Production, you (or one of your clients) will need to actually pay this Boleto
for the value to be credited to your account.


## Usage

Here are a few examples on how to use the SDK. If you have any doubts, check out
the function or class docstring to get more info or go straight to our [API docs].

### Get balance

To know how much money you have in your workspace, run:

```php
use StarkBank\Balance;

$balance = Balance::get();

print_r($balance);
```

### Create boletos

You can create boletos to charge customers or to receive money from accounts
you have in other banks.

```php
use StarkBank\Boleto;


$boletos = Boleto::create([
    new Boleto([
        "amount" => 23571,  # R$ 235,71 
        "name" => "Buzz Aldrin",
        "taxId" => "012.345.678-90", 
        "streetLine1" => "Av. Paulista, 200", 
        "streetLine2" => "10 andar",
        "district" => "Bela Vista", 
        "city" => "São Paulo",
        "stateCode" => "SP",
        "zipCode" => "01310-000",
        "due" => "2020-3-20",
        "fine" => 5,  # 5%
        "interest" => 2.5  # 2.5% per month
    ])
]);

foreach($boletos as $boleto){
    print_r($boleto);
}
```

### Get boleto

After its creation, information on a boleto may be retrieved by passing its id. 
Its status indicates whether it's been paid.

```php
use StarkBank\Boleto;

$boleto = Boleto::get("5155165527080960");

print_r($boleto);
```

### Get boleto PDF

After its creation, a boleto PDF may be retrieved by passing its id. 

```php
use StarkBank\Boleto;

$pdf = Boleto::pdf("5155165527080960");

$fp = fopen('boleto.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Delete boleto

You can also cancel a boleto by its id.
Note that this is not possible if it has been processed already.

```php
use StarkBank\Boleto;

$boleto = Boleto::delete("5155165527080960");

print_r($boleto);
```

### Query boletos

You can get a list of created boletos given some filters.

```php
use StarkBank\Boleto;
use \DateTime;

$boletos = Boleto::query([
    "after" => "2020-01-01",
    "before" => (new DateTime("now"))->add(new DateInterval("P1D"))
]);

foreach($boletos as $boleto){
    print_r($boleto);
}
```

### Query boleto logs

Logs are pretty important to understand the life cycle of a boleto.

```php
use StarkBank\Boleto;

$logs = Boleto\Log::query(["limit" => 150]);

foreach($logs as $log){
    print_r($log);
}
```

### Get a boleto log

You can get a single log by its id.

```php
use StarkBank\Boleto;

$log = Boleto\Log::get("5155165527080960");

print_r($log);
```

### Create transfers

You can also create transfers in the SDK (TED/DOC).

```php
use StarkBank\Transfer;

$transfers = Transfer::create([
    new Transfer([
        "amount" => 100,
        "bankCode" => "200",
        "branchCode" => "0001",
        "accountNumber" => "10000-0",
        "taxId" => "012.345.678-90",
        "name" => "Tony Stark",
        "tags" => ["iron", "suit"]
    ]),
    new Transfer([
        "amount" => 200,
        "bankCode" => "341",
        "branchCode" => "1234",
        "accountNumber" => "123456-7",
        "taxId" => "012.345.678-90",
        "name" => "Jon Snow",
        "tags" => []
    ])
]);

foreach($transfers as $transfer){
    print_r($transfer);
}
```

### Query transfers

You can query multiple transfers according to filters.

```php
use StarkBank\Transfer;

$transfers = Transfer::query([
    "after" => "2020-01-01",
    "before" => "2020-04-01"
]);

foreach($transfers as $transfer){
    print_r($transfer->name);
}
```

### Get transfer

To get a single transfer by its id, run:

```php
use StarkBank\Transfer;

$transfer = Transfer::get("5155165527080960");

print_r($transfer);
```

### Get transfer PDF

A transfer PDF may also be retrieved by passing its id.
This operation is only valid if the transfer status is "processing" or "success". 

```php
use StarkBank\Transfer;

$pdf = Transfer::pdf("5155165527080960");

$fp = fopen('transfer.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Query transfer logs

You can query transfer logs to better understand transfer life cycles.

```php
use StarkBank\Transfer;

$logs = Transfer\Log::query(["limit" => 50]);

foreach($logs as $log){
    print_r($log->id);
}
```

### Get a transfer log

You can also get a specific log by its id.

```php
use StarkBank\Transfer;

$log = Transfer\Log::get("5155165527080960");

print_r($log);
```

### Pay a boleto

Paying a boleto is also simple.

```php
use StarkBank\BoletoPayment;

$payments = BoletoPayment::create([
    new BoletoPayment([
        "line" => "34191.09008 61207.727308 71444.640008 5 81310001234321",
        "taxId" => "012.345.678-90",
        "scheduled" => "2020-03-13",
        "description" => "take my money",
        "tags" => ["take", "my", "money"],
    ]),
    new BoletoPayment([
        "barCode" => "34197819200000000011090063609567307144464000",
        "taxId" => "012.345.678-90",
        "scheduled" => "2020-03-14",
        "description" => "take my money one more time",
        "tags" => ["again"],
    ]),
]);

foreach($payments as $payment){
    print_r($payment);
}
```

### Get boleto payment

To get a single boleto payment by its id, run:

```php
use StarkBank\BoletoPayment;

$payment = BoletoPayment::get("19278361897236187236");

print_r($payment);
```

### Get boleto payment PDF

After its creation, a boleto payment PDF may be retrieved by passing its id. 

```php
use StarkBank\BoletoPayment;

$pdf = BoletoPayment::pdf("5155165527080960");

$fp = fopen('boletoPayment.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Delete boleto payment

You can also cancel a boleto payment by its id.
Note that this is not possible if it has been processed already.

```php
use StarkBank\BoletoPayment;

$payment = BoletoPayment::delete("5155165527080960");

print_r($payment);
```

### Query boleto payments

You can search for boleto payments using filters. 

```php
use StarkBank\BoletoPayment;

$payments = BoletoPayment::query([
    "tags" => ["company_1", "company_2"]
]);

foreach($payments as $payment){
    print_r($payment);
}
```

### Query boleto payment logs

Searches are also possible with boleto payment logs:

```php
use StarkBank\BoletoPayment;

$logs = BoletoPayment\Log::query([
    "paymentIds" => ["5155165527080960", "76551659167801921"],
]);

foreach($logs as $log){
    print_r($log);
}
```


### Get boleto payment log

You can also get a boleto payment log by specifying its id.

```php
use StarkBank\BoletoPayment;

$log = BoletoPayment\Log::get("5155165527080960");

print_r($log);
```

### Create utility payment

It's also simple to pay utility bills (such as electricity and water bills) in the SDK.

```php
use StarkBank\UtilityPayment;

$payments = UtilityPayment::create([
    new UtilityPayment([
        "line" => "34197819200000000011090063609567307144464000",
        "scheduled" => "2020-03-13",
        "description" => "take my money",
        "tags" => ["take", "my", "money"],
    ]),
    new UtilityPayment([
        "barCode" => "34191.09008 61207.727308 71444.640008 5 81310001234321",
        "scheduled" => "2020-03-14",
        "description" => "take my money one more time",
        "tags" => ["again"],
    ]),
]);

foreach($payments as $payment){
    print_r($payment);
}
```

### Query utility payments

To search for utility payments using filters, run:

```php
use StarkBank\UtilityPayment;

$payments = UtilityPayment::query(
    "tags" => ["electricity", "gas"]
);

foreach($payments as $payment){
    print_r($payment);
}
```

### Get utility payment

You can get a specific bill by its id:

```php
use StarkBank\UtilityPayment;

$payment = UtilityPayment::get("5155165527080960");

print_r($payment);
```

### Get utility payment PDF

After its creation, a utility payment PDF may also be retrieved by passing its id. 

```php
use StarkBank\UtilityPayment;

$pdf = UtilityPayment::pdf("5155165527080960");

$fp = fopen('electricity.pdf', 'w');
fwrite($fp, $pdf);
fclose($fp);
```

Be careful not to accidentally enforce any encoding on the raw pdf content,
as it may yield abnormal results in the final file, such as missing images
and strange characters.

### Delete utility payment

You can also cancel a utility payment by its id.
Note that this is not possible if it has been processed already.

```php
use StarkBank\UtilityPayment;

$payment = UtilityPayment::delete("5155165527080960");

print_r($payment);
```

### Query utility bill payment logs

You can search for payments by specifying filters. Use this to understand the
bills life cycles.

```php
use StarkBank\UtilityPayment;

$logs = UtilityPayment\Log::query(
    "paymentIds" => ["102893710982379182", "92837912873981273"],
);

foreach($logs as $log){
    print_r($log);
}
```

### Get utility bill payment log

If you want to get a specific payment log by its id, just run:

```php
use StarkBank\UtilityPayment;

$log = UtilityPayment\Log::get("1902837198237992");

print_r($log);
```

### Create transactions

To send money between Stark Bank accounts, you can create transactions:

```php
use StarkBank\Transaction;

$transactions = Transaction::create([
    new Transaction([
        "amount" => 100,  # (R$ 1.00)
        "receiverId" => "1029378109327810",
        "description" => "Transaction to dear provider",
        "externalId" => "12345",  # so we can block anything you send twice by mistake
        "tags" => ["provider"]
    ]),
    new Transaction([
        "amount" => 234,  # (R$ 2.34)
        "receiverId" => "2093029347820947",
        "description" => "Transaction to the other provider",
        "externalId" => "12346",  # so we can block anything you send twice by mistake
        "tags" => ["provider"]
    ]),
]);

foreach($transactions as $transaction){
    print_r($transaction);
}
```

### Query transactions

To understand your balance changes (bank statement), you can query
transactions. Note that our system creates transactions for you when
you receive boleto payments, pay a bill or make transfers, for example.

```php
use StarkBank\Transaction;

$transactions = Transaction::query([
    "after" => "2020-01-01",
    "before" => "2020-03-01"
]);

foreach($transactions as $transaction){
    print_r($transaction);
}
```

### Get transaction

You can get a specific transaction by its id:

```php
use StarkBank\Transaction;

$transaction = Transaction::get("5155165527080960");

print_r($transaction);
```

### Create webhook subscription

To create a webhook subscription and be notified whenever an event occurs, run:

```php
use StarkBank\Webhook;

$webhook = Webhook::create(
    "https://webhook.site/dd784f26-1d6a-4ca6-81cb-fda0267761ec",
    ["transfer", "boleto", "boleto-payment", "utility-payment"],
);

print_r($webhook);
```

### Query webhooks

To search for registered webhooks, run:

```php
use StarkBank\Webhook;

$webhooks = Webhook::query();

foreach($webhooks as $webhook){
    print_r($webhook);
}
```

### Get webhook

You can get a specific webhook by its id.

```php
use StarkBank\Webhook;

$webhook = Webhook::get("10827361982368179");

print_r($webhook);
```

### Delete webhook

You can also delete a specific webhook by its id.

```php
use StarkBank\Webhook;

$webhook = Webhook::delete("10827361982368179");

print_r($webhook);
```

### Process webhook events

It's easy to process events that arrived in your webhook. Remember to pass the
signature header so the SDK can make sure it's really StarkBank that sent you
the event.

```php
use StarkBank\Event;

$response = listen()  # this is the method you made to get the events posted to your webhook

$event = Event::parse($response->content, $response->headers["Digital-Signature"]);

if ($event->subscription == "transfer"){
    print_r($event->log->transfer);
} elseif ($event->subscription == "boleto"){
    print_r($event->log->boleto);
} elseif ($event->subscription == "boleto-payment"){
    print_r($event->log->payment);
} elseif ($event->subscription == "utility-payment"){
    print_r($event->log->payment);
}
```

### Query webhook events

To search for webhooks events, run:

```php
use StarkBank\Event;

$events = Event::query(["after" => "2020-03-20", "isDelivered" => false]);

foreach($events as $event){
    print_r($event);
}
```

### Get webhook event

You can get a specific webhook event by its id.

```php
use StarkBank\Event;

$event = Event::get("10827361982368179");

print_r($event);
```

### Delete webhook event

You can also delete a specific webhook event by its id.

```php
use StarkBank\Event;

$event = Event::delete("10827361982368179");

print_r($event);
```

### Set webhook events as delivered

This can be used in case you've lost events.
With this function, you can manually set events retrieved from the API as
"delivered" to help future event queries with `isDelivered=false`.

```php
use StarkBank\Event;

$event = Event::update("129837198237192", ["isDelivered" => true]);

print_r($event);
```

## Handling errors

The SDK may raise one of four types of errors: __InputErrors__, __InternalServerError__, __UnknownError__, __InvalidSignatureError__

__InputErrors__ will be raised whenever the API detects an error in your request (status code 400).
If you catch such an error, you can get its elements to verify each of the
individual errors that were detected in your request by the API.
For example:

```php
use StarkBank\Transaction;

try {
    $transactions = Transaction::create([
        new Transaction([
            "amount" => 99999999999999,  # (R$ 999,999,999,999.99)
            "receiverId" => "1029378109327810",
            "description" => ".",
            "externalId" => "12345",  # so we can block anything you send twice by mistake
            "tags" => ["provider"]
        ]),
    ])
} catch (Exception $e) {
    foreach($e->errors as $error){
        echo "\n\ncode: " . $error->code;
        echo "\nmessage: " . $error->message;
    }
}
```

__InternalServerError__ will be raised if the API runs into an internal error.
If you ever stumble upon this one, rest assured that the development team
is already rushing in to fix the mistake and get you back up to speed.

__UnknownError__ will be raised if a request encounters an error that is
neither __InputErrors__ nor an __InternalServerError__, such as connectivity problems.

__InvalidSignatureError__ will be raised specifically by StarkBank\Event::parse()
when the provided content and signature do not check out with the Stark Bank public
key.

## Key pair generation

The SDK provides a helper to allow you to easily create ECDSA secp256k1 keys to use
within our API. If you ever need a new pair of keys, just run:

```php
use StarkBank\Key;

list(privateKey, publicKey) = Key::create();

# or, to also save .pem files in a specific path
list(privateKey, publicKey) = Key::create("file/keys/");
```

NOTE: When you are creating a new Project, it is recommended that you create the
keys inside the infrastructure that will use it, in order to avoid risky internet
transmissions of your **private-key**. Then you can export the **public-key** alone to the
computer where it will be used in the new Project creation.


[API docs]: (https://starkbank.com/docs/api)