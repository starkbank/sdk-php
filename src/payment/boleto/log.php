<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\API;
use StarkBank\BoletoPayment;


class BoletoPaymentLog extends Resource
{
    /**
    # BoletoPaymentLog object

    Every time a BoletoPayment entity is modified, a corresponding BoletoPaymentLog
    is generated for the entity. This log is never generated by the
    user, but it can be retrieved to check additional information
    on the BoletoPayment.

    ## Attributes:
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - payment [BoletoPayment]: BoletoPayment entity to which the log refers to.
        - errors [list of strings]: list of errors linked to this BoletoPayment event.
        - type [string]: type of the BoletoPayment event which triggered the log creation. ex: "registered" or "paid"
        - created [DateTime]: creation datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);
        $this->type = $params["type"];
        unset($params["type"]);
        $this->errors = $params["errors"];
        unset($params["errors"]);
        $this->payment = $params["payment"];
        unset($params["payment"]);

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific BoletoPaymentLog

    Receive a single BoletoPaymentLog object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - BoletoPaymentLog object with updated attributes
     */
    public function get($user, $id)
    {
        return Rest::getId($user, BoletoPaymentLog::resource(), $id);
    }

    /**
    # Retrieve BoletoPaymentLogs

    Receive a generator of BoletoPaymentLog objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - payment_ids [list of strings, default null]: list of BoletoPayment ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - types [list of strings, default null]: filter retrieved objects by event types. ex: "paid" or "registered"
        - user [Project object, default null]: Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - list of BoletoPaymentLog objects with updated attributes
     */
    public function query($user, $options = [])
    {
        return Rest::getList($user, BoletoPaymentLog::resource(), $options);
    }

    private function resource()
    {
        $boletoPaymentLog = function ($array) {
            $payment = function ($array) {
                return new BoletoPayment($array);
            };
            $array["payment"] = API::fromApiJson($payment, $array["payment"]);
            return new BoletoPaymentLog($array);
        };
        return [
            "name" => "BoletoPaymentLog",
            "maker" => $boletoPaymentLog,
        ];
    }
}
