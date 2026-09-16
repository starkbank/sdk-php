<?php

namespace StarkBank;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;


class InvoicePullSubscription extends Resource
{
    
    public $start;
    public $interval;
    public $pullMode;
    public $pullRetryLimit;
    public $type;
    public $amount;
    public $amountMinLimit;
    public $displayDescription;
    public $due;
    public $externalId;
    public $referenceCode;
    public $end;
    public $data;
    public $name;
    public $taxId;
    public $tags;
    public $status;
    public $bacenId;
    public $installmentId;
    public $created;
    public $updated;
    public $brcode;

    /**
    # InvoicePullSubscription object

    An InvoicePullSubscription is a recurring payment agreement between a payer and a receiver, authorized through the Pix Automatic infrastructure. Once active, it lets the receiver periodically trigger automatic debits by issuing Invoices that match the agreed amount, frequency and billing cycle, without new consent per transaction. Use webhooks to monitor status changes asynchronously.

    ## Parameters (required):
        - name [string]: debtor full name.
        - taxId [string]: debtor tax ID (CPF or CNPJ).
        - displayDescription [string]: description to be presented to the payer.
        - externalId [string]: unique safe string among all your InvoicePullSubscriptions, used to prevent duplicates.
        - referenceCode [string]: unique safe string representing the underlying contract.
        - interval [string]: cycle definition. Options: "week", "month", "quarter", "semester", "year".
        - pullMode [string]: whether Invoice Pull Requests are issued automatically by Stark Bank or manually by the company.
        - pullRetryLimit [integer]: number of retries the receiver may attempt per cycle. Options: 0 or 3.
        - start [DateTime or string]: expected date to settle the first Invoice Pull Request.
        - type [string]: subscription journey type. Options: "push", "qrcode", "qrcodeAndPayment", "paymentAndOrQrCode".

    ## Parameters (conditionally required):
        - amount [integer, default null]: fixed amount in cents to be charged every cycle. Required when the subscription has a fixed amount.
        - amountMinLimit [integer, default null]: minimum amount in cents the payer may authorize per cycle. Required when the subscription has a variable amount.
        - data [dictionary, default null]: additional data required by some journey types: for "push" it carries the payer's account details; for "qrcodeAndPayment"/"paymentAndOrQrCode" it carries the immediate payment parameters. Not required for "qrcode".

    ## Parameters (optional):
        - due [DateTime or string, default today + 2 days]: due date for the payer to approve or deny the subscription.
        - end [DateTime or string, default null]: final date of the subscription.
        - tags [array of strings, default null]: array of strings for tagging

    ## Attributes (return-only):
        - id [string]: unique id returned when the subscription is created.
        - status [string]: current subscription status.
        - bacenId [string]: Central Bank identifier.
        - brcode [string]: BR Code for the subscription.
        - installmentId [string]: id of the linked installment, when applicable.
        - created [DateTime]: creation datetime.
        - updated [DateTime]: latest update datetime.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->start = Checks::checkDateTime(Checks::checkParam($params, "start"));
        $this->interval = Checks::checkParam($params, "interval");
        $this->pullMode = Checks::checkParam($params, "pullMode");
        $this->pullRetryLimit = Checks::checkParam($params, "pullRetryLimit");
        $this->type = Checks::checkParam($params, "type");
        $this->amount = Checks::checkParam($params, "amount");
        $this->amountMinLimit = Checks::checkParam($params, "amountMinLimit");
        $this->displayDescription = Checks::checkParam($params, "displayDescription");
        $this->due = Checks::checkDateTime(Checks::checkParam($params, "due"));
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->referenceCode = Checks::checkParam($params, "referenceCode");
        $this->end = Checks::checkDateTime(Checks::checkParam($params, "end"));
        $this->data = Checks::checkParam($params, "data");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->tags = Checks::checkParam($params, "tags");
        $this->status = Checks::checkParam($params, "status");
        $this->bacenId = Checks::checkParam($params, "bacenId");
        $this->installmentId = Checks::checkParam($params, "installmentId");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        $this->brcode = Checks::checkParam($params, "brcode");

        Checks::checkParams($params);
    }

    /**
    # Create InvoicePullSubscriptions

    Send an array of InvoicePullSubscription objects for creation in the Stark Bank API

    ## Parameters (required):
        - subscriptions [array of InvoicePullSubscription objects]: array of InvoicePullSubscription objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - array of InvoicePullSubscription objects with updated attributes
     */
    public static function create($invoicePullRequests, $user = null)
    {
        return Rest::post($user, InvoicePullSubscription::resource(), $invoicePullRequests);
    }

    /**
    # Retrieve a specific InvoicePullSubscription

    Receive a single InvoicePullSubscription object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id.

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - InvoicePullSubscription object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, InvoicePullSubscription::resource(), $id);
    }

    /**
    # Retrieve InvoicePullSubscriptions

    Receive an enumerator of InvoicePullSubscription objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null.
        - after [DateTime or string, default null]: date filter for objects created only after specified date.
        - before [DateTime or string, default null]: date filter for objects created only before specified date.
        - status [string, default null]: filter for status of retrieved objects.
        - tags [array of strings, default null]: tags to filter retrieved objects.
        - ids [array of strings, default null]: array of ids to filter retrieved objects.
        - expand [array of strings, default null]: additional fields to expand in the response. Options: "data".
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of InvoicePullSubscription objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, InvoicePullSubscription::resource(), $options);
    }

    /**
    # Retrieve paged InvoicePullSubscriptions

    Receive a list of up to 100 InvoicePullSubscription objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100.
        - (same filters as query: after, before, status, tags, ids, expand)
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - list of InvoicePullSubscription objects with updated attributes
        - cursor to retrieve the next page of InvoicePullSubscription objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, InvoicePullSubscription::resource(), $options);
    }

    /**
    # Cancel an InvoicePullSubscription entity

    Cancel an InvoicePullSubscription entity previously created in the Stark Bank API. The subscription must currently have "active" status to be canceled.

    ## Parameters (required):
        - id [string]: InvoicePullSubscription unique id.

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - canceled InvoicePullSubscription object
     */
    public static function cancel($id, $user = null)
    {
        return Rest::deleteId($user, InvoicePullSubscription::resource(), $id);
    }

    /**
  
     */
    private static function resource()
    {
        $subscription = function ($array) {
            return new InvoicePullSubscription($array);
        };
        return [
            "name" => "InvoicePullSubscription",
            "maker" => $subscription,
        ];
    }
}
