<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;


class Event extends Resource
{

    public $isDelivered;
    public $subscription;
    public $created;
    public $log;
    public $workspaceId;

    /**
    # Webhook Event object

    An Event is the notification received from the subscription to the Webhook.
    Events cannot be created, but may be retrieved from the Stark Bank API to
    list all generated updates on entities.

    ## Attributes (return-only):
        - id [string]: unique id returned when the event is created. ex: "5656565656565656"
        - log [Log]: a Log object from one the subscription services (Transfer\Log, Boleto\Log, BoletoPayment\log or UtilityPayment\Log)
        - created [DateTime]: creation datetime for the notification event.
        - isDelivered [bool]: true if the event has been successfully delivered to the user url. ex: false
        - subscription [string]: service that triggered this event. ex: "transfer", "utility-payment"
        - workspaceId [string]: ID of the Workspace that generated this event. Mostly used when multiple Workspaces have Webhooks registered to the same endpoint. ex: "4545454545454545"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->isDelivered = Checks::checkParam($params, "isDelivered");
        $this->subscription = Checks::checkParam($params, "subscription");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->log = Event::buildLog($this->subscription, Checks::checkParam($params, "log"));
        $this->workspaceId = Checks::checkParam($params, "workspaceId");

        Checks::checkParams($params);
    }

    private static function buildLog($subscription, $log)
    {
        $makerOptions = [
            "transfer" => Event::transferLogResource(),
            "boleto" => Event::boletoLogResource(),
            "boleto-payment" => Event::boletoPaymentLogResource(),
            "utility-payment" => Event::utilityPaymentLogResource(),
            "darf-payment" => Event::DarfPaymentLogResource(),
            "tax-payment" => Event::TaxPaymentLogResource(),
            "brcode-payment" => Event::brcodePaymentLogResource(),
            "boleto-holmes" => Event::boletoHolmesLogResource(),
            "invoice" => Event::invoiceLogResource(),
            "deposit" => Event::depositLogResource()
        ];

        if (!isset($makerOptions[$subscription])) {
            return $log;
        }

        return $makerOptions[$subscription]($log);
    }

    private static function transferLogResource()
    {
        return function ($array) {
            $transfer = function ($array) {
                return new Transfer($array);
            };
            $array["transfer"] = API::fromApiJson($transfer, $array["transfer"]);
            $log = function ($array) {
                return new Transfer\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function boletoLogResource()
    {
        return function ($array) {
            $boleto = function ($array) {
                return new Boleto($array);
            };
            $array["boleto"] = API::fromApiJson($boleto, $array["boleto"]);
            $log = function ($array) {
                return new Boleto\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function boletoPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new BoletoPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            $log = function ($array) {
                return new BoletoPayment\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function utilityPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new UtilityPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            $log = function ($array) {
                return new UtilityPayment\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function taxPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new TaxPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            $log = function ($array) {
                return new TaxPayment\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }
    
    private static function darfPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new DarfPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            $log = function ($array) {
                return new DarfPayment\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function boletoHolmesLogResource()
    {
        return function ($array) {
            $holmes = function ($array) {
                return new BoletoHolmes($array);
            };
            $array["holmes"] = API::fromApiJson($holmes, $array["holmes"]);
            $log = function ($array) {
                return new BoletoHolmes\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function invoiceLogResource()
    {
        return function ($array) {
            $invoice = function ($array) {
                return new Invoice($array);
            };
            $array["invoice"] = API::fromApiJson($invoice, $array["invoice"]);
            $log = function ($array) {
                return new Invoice\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function depositLogResource()
    {
        return function ($array) {
            $deposit = function ($array) {
                return new Deposit($array);
            };
            $array["deposit"] = API::fromApiJson($deposit, $array["deposit"]);
            $log = function ($array) {
                return new Deposit\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }

    private static function brcodePaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new BrcodePayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            $log = function ($array) {
                return new BrcodePayment\Log($array);
            };
            return API::fromApiJson($log, $array);
        };
    }


    /**
    # Retrieve a specific notification Event

    Receive a single notification Event object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Event object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Event::resource(), $id);
    }

    /**
    # Retrieve notification Events

    Receive a enumerator of notification Event objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null]: date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null]: date filter for objects created only before specified date. ex: "2020-04-03"
        - isDelivered [bool, default null]: bool to filter successfully delivered events. ex: true or false
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of Event objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Event::resource(), $options);
    }

    /**
    # Retrieve paged Events

    Receive a list of up to 100 Event objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - isDelivered [boolean, default null]: bool to filter successfully delivered events. ex: True or False
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of Event objects with updated attributes
        - cursor to retrieve the next page of Event objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Event::resource(), $options);
    }

    /**
    # Delete notification Events

    Delete an array of notification Event entities previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Event unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - deleted Event object
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, Event::resource(), $id);
    }

    /**
    # Update notification Event entity

    Update notification Event by passing id.
    If isDelivered is true, the event will no longer be returned on queries with isDelivered=false.

    ## Parameters (required):
        - id [array of strings]: Event unique ids. ex: "5656565656565656"
        - isDelivered [bool]: If true and event hasn't been delivered already, event will be set as delivered. ex: true

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - target Event with updated attributes
     */
    public static function update($id, $options = [], $user = null)
    {
        return Rest::patchId($user, Event::resource(), $id, $options);
    }

    /**
    # Create single notification Event from a content string

    Create a single Event object received from event listening at subscribed user endpoint.
    If the provided digital signature does not check out with the StarkBank public key, a
    starkbank.exception.InvalidSignatureException will be raised.

    ## Parameters (required):
        - content [string]: response content from request received at user endpoint (not parsed)
        - signature [string]: base-64 digital signature received at response header "Digital-Signature"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Event object with updated attributes
     */
    public static function parse($content, $signature, $user = null) {
        return Parse::parseAndVerify($content, $signature, Event::resource(), $user);
    }

    private static function resource()
    {
        $event = function ($array) {
            return new Event($array);
        };
        return [
            "name" => "Event",
            "maker" => $event,
        ];
    }
}
