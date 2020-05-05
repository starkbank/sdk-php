<?php

namespace StarkBank;

use \Exception;
use EllipticCurve\PublicKey;
use EllipticCurve\Signature;
use EllipticCurve\Ecdsa;
use StarkBank\Error\InvalidSignatureError;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\API;
use StarkBank\Utils\Request;
use StarkBank\Utils\Cache;


class Event extends Resource
{
    /**
    # Webhook Event object

    An Event is the notification received from the subscription to the Webhook.
    Events cannot be created, but may be retrieved from the Stark Bank API to
    list all generated updates on entities.

    ## Attributes:
        - id [string]: unique id returned when the event is created. ex: "5656565656565656"
        - log [Log]: a Log object from one the subscription services (Transfer\Log, Boleto\Log, BoletoPayment\log or UtilityPayment\Log)
        - created [DateTime]: creation datetime for the notification event.
        - isDelivered [bool]: true if the event has been successfully delivered to the user url. ex: false
        - subscription [string]: service that triggered this event. ex: "transfer", "utility-payment"
     */
    function __construct(array $params)
    {
        parent::__construct($params);
        
        $this->isDelivered = Checks::checkParam($params, "isDelivered");
        $this->subscription = Checks::checkParam($params, "subscription");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->log = Event::buildLog($this->subscription, Checks::checkParam($params, "log"));

        Checks::checkParams($params);
    }

    private static function buildLog($subscription, $log)
    {
        $maker = [
            "transfer" => Event::transferLogResource(),
            "boleto" => Event::boletoLogResource(),
            "boleto-payment" => Event::boletoPaymentLogResource(),
            "utility-payment" => Event::utilityPaymentLogResource()
        ][$subscription];
        return $maker($log);
    }

    private static function transferLogResource()
    {
        return function ($array) {
            $transfer = function ($array) {
                return new Transfer($array);
            };
            $array["transfer"] = API::fromApiJson($transfer, $array["transfer"]);
            return new Transfer\Log($array);
        };
    }

    private static function boletoLogResource()
    {
        return function ($array) {
            $boleto = function ($array) {
                return new Boleto($array);
            };
            $array["boleto"] = API::fromApiJson($boleto, $array["boleto"]);
            return new Boleto\Log($array);
        };
    }

    private static function boletoPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new BoletoPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            return new BoletoPayment\Log($array);
        };
    }

    private static function utilityPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new UtilityPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            return new UtilityPayment\Log($array);
        };
    }

    /**
    # Retrieve a specific notification Event

    Receive a single notification Event object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

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
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of Event objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, Event::resource(), $options);
    }

    /**
    # Delete notification Events

    Delete a list of notification Event entities previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Event unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - deleted Event with updated attributes
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
        - id [list of strings]: Event unique ids. ex: "5656565656565656"
        - isDelivered [bool]: If true and event hasn't been delivered already, event will be set as delivered. ex: true

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

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
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - Event object with updated attributes
     */
    public static function parse($content, $signature, $user = null)
    {
        $event = API::fromApiJson(Event::resource()["maker"], json_decode($content, true)["event"]);

        try {
            $signature = Signature::fromBase64($signature);
        } catch (Exception $e) {
            throw new InvalidSignatureError("The provided signature is not valid");
        }

        if (Event::verifySignature($user, $content, $signature)) {
            return $event;
        }
        if (Event::verifySignature($user, $content, $signature, true)) {
            return $event;
        }

        throw new InvalidSignatureError("The provided signature and content do not match the Stark Bank public key");
    }

    private static function verifySignature($user, $content, $signature, $refresh = false)
    {
        $publicKey = Cache::getStarkBankPublicKey();
        if (is_null($publicKey) | $refresh) {
            $pem = Event::getPublicKeyPem($user);
            $publicKey = PublicKey::fromPem($pem);
            Cache::setStarkBankPublicKey($publicKey);
        }
        return Ecdsa::verify($content, $signature, $publicKey);
    }

    private static function getPublicKeyPem($user)
    {
        return Request::fetch($user, "GET", "/public-key", null, ["limit" => 1])->json()["publicKeys"][0]["content"];
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
