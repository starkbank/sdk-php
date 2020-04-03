<?php

namespace StarkBank;

use EllipticCurve\PublicKey;
use EllipticCurve\Signature;
use EllipticCurve\Ecdsa;
use StarkBank\Exception\InvalidSignatureError;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\API;
use StarkBank\Utils\Request;
use StarkBank\Utils\Cache;


class Event extends Resource
{
    /**
    Webhook Event object

    An Event is the notification received from the subscription to the Webhook.
    Events cannot be created, but may be retrieved from the Stark Bank API to
    list all generated updates on entities.

    Attributes:
        id [string]: unique id returned when the log is created. ex: "5656565656565656"
        log [Log]: a Log object from one the subscription services (TransferLog, BoletoLog, BoletoPaymentlog or UtilityPaymentLog)
        created [datetime.datetime]: creation datetime for the notification event. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
        is_delivered [bool]: true if the event has been successfully delivered to the user url. ex: False
        subscription [string]: service that triggered this event. ex: "transfer", "utility-payment"
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->isDelivered = $params["isDelivered"];
        unset($params["isDelivered"]);
        $this->subscription = $params["subscription"];
        unset($params["subscription"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);
        $this->log = Event::buildLog($this->subscription, $params["log"]);
        unset($params["log"]);

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
            return new TransferLog($array);
        };
    }

    private static function boletoLogResource()
    {
        return function ($array) {
            $boleto = function ($array) {
                return new Boleto($array);
            };
            $array["boleto"] = API::fromApiJson($boleto, $array["boleto"]);
            return new BoletoLog($array);
        };
    }

    private static function boletoPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new BoletoPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            return new BoletoPaymentLog($array);
        };
    }

    private static function utilityPaymentLogResource()
    {
        return function ($array) {
            $payment = function ($array) {
                return new UtilityPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            return new UtilityPaymentLog($array);
        };
    }

    /**
    Retrieve a specific notification Event

    Receive a single notification Event object previously created in the Stark Bank API by passing its id

    Parameters (required):
        id [string]: object unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        Event object with updated attributes
     */
    public static function get($user, $id)
    {
        return Rest::getId($user, Event::resource(), $id);
    }

    /**
    Retrieve notification Events

    Receive a generator of notification Event objects previously created in the Stark Bank API

    Parameters (optional):
        limit [integer, default None]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        is_delivered [bool, default None]: bool to filter successfully delivered events. ex: True or False
        after [datetime.date, default None]: date filter for objects created only after specified date. ex: datetime.date(2020, 3, 10)
        before [datetime.date, default None]: date filter for objects only before specified date. ex: datetime.date(2020, 3, 10)
        user [Project object, default None]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        generator of Event objects with updated attributes
     */
    public static function query($user, $options = [])
    {
        $options["after"] = Checks::checkDateTime($options["after"]);
        $options["before"] = Checks::checkDateTime($options["before"]);
        return Rest::getList($user, Event::resource(), $options);
    }

    /**
    Delete notification Events

    Delete a list of notification Event entities previously created in the Stark Bank API

    Parameters (required):
        id [string]: Event unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        deleted Event with updated attributes
     */
    public static function delete($user, $id)
    {
        return Rest::deleteId($user, Event::resource(), $id);
    }

    /**
    Update notification Event entity

    Update notification Event by passing id.
    If is_delivered is True, the event will no longer be returned on queries with is_delivered=False.

    Parameters (required):
        id [list of strings]: Event unique ids. ex: "5656565656565656"
        is_delivered [bool]: If True and event hasn't been delivered already, event will be set as delivered. ex: True
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        target Event with updated attributes
     */
    public static function update($user, $id, $options = [])
    {
        return Rest::patchId($user, Event::resource(), $id, $options);
    }

    /**
    Create single notification Event from a content string

    Create a single Event object received from event listening at subscribed user endpoint.
    If the provided digital signature does not check out with the StarkBank public key, a
    starkbank.exception.InvalidSignatureException will be raised.

    Parameters (required):
        content [string]: response content from request received at user endpoint (not parsed)
        signature [string]: base-64 digital signature received at response header "Digital-Signature"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        Event object with updated attributes
     */
    public static function parse($user, $content, $signature)
    {
        $event = API::fromApiJson(Event::resource()["maker"], json_decode($content, true)["event"]);

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
        $signature = Signature::fromBase64($signature);
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

    private function resource()
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
