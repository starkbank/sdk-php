<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\API;


class PaymentRequest extends Resource
{
    /**
    # PaymentRequest object

    A PaymentRequest is an indirect request to access a specific cash-out service
    (such as Transfers, BoletoPayments, etc.) which goes through the cost center
    approval flow on our website. To emit a PaymentRequest, you must direct it to
    a specific cost center by its ID, which can be retrieved on our website at the
    cost center page.

    ## Parameters (required):
        - centerId [string]: target cost center ID. ex: "5656565656565656"
        - payment [Transfer, Transaction, BoletoPayment, UtilityPayment or dictionary]: payment entity that should be approved and executed.

    ## Parameters (conditionally required):
        - type [string]: payment type, inferred from the payment parameter if it is not a dictionary. ex: "transfer", "boleto-payment"
    
    ## Parameters (optional):
        - due [DateTime or string, default today]: Payment target date in ISO format. ex: 2020-04-30
        - tags [array of strings]: array of strings for tagging

    ## Attributes (return-only):
        - id [string, default null]: unique id returned when PaymentRequest is created. ex: "5656565656565656"
        - amount [integer, default null]: PaymentRequest amount. ex: 100000 = R$1.000,00
        - status [string, default null]: current PaymentRequest status. ex: "pending" or "approved"
        - actions [array of dictionaries, default null]: array of actions that are affecting this PaymentRequest. ex: [["type" => "member", "id" => "56565656565656", "action" => "requested"]]
        - updated [DateTime, default null]: latest update datetime for the PaymentRequest.
        - created [DateTime, default null]: creation datetime for the PaymentRequest.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->centerId = Checks::checkParam($params, "centerId");
        $this->due = Checks::checkParam($params, "due");
        $this->description = Checks::checkParam($params, "description");
        $this->tags = Checks::checkParam($params, "tags");
        $this->amount = Checks::checkParam($params, "amount");
        $this->status = Checks::checkParam($params, "status");
        $this->actions = Checks::checkParam($params, "actions");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        $this->payment = Checks::checkParam($params, "payment");
        $this->type = Checks::checkParam($params, "type");
        Checks::checkParams($params);

        list($this->payment, $this->type) = self::parsePayment($this->payment, $this->type);
    }

    /**
    # Create PaymentRequests

    Send an array of PaymentRequest objects for creation in the Stark Bank API

    ## Parameters (required):
        - requests [array of PaymentRequest objects]: array of PaymentRequest objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - list of PaymentRequest objects with updated attributes
     */
    public static function create($requests, $user = null)
    {
        return Rest::post($user, PaymentRequest::resource(), $requests);
    }

    /**
    # Retrieve PaymentRequests

    Receive a enumerator of PaymentRequest objects previously created by this user in the Stark Bank API

    ## Parameters (required):
        - center_id [string]: target cost center ID. ex: "5656565656565656"

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - sort [string, default "-created"]: sort order considered in response. Valid options are "-created" or "-due".
        - status [string, default null]: filter for status of retrieved objects. ex: "success" or "failed"
        - type [string, default null]: payment type, inferred from the payment parameter if it is not a dictionary. ex: "transfer", "boleto-payment"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of PaymentRequest objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, PaymentRequest::resource(), $options);
    }

    private static function parsePayment($payment, $type)
    {
        if($payment instanceof Transfer)
            return [$payment, "transfer"];
        if($payment instanceof Transaction)
            return [$payment, "transaction"];
        if($payment instanceof BoletoPayment)
            return [$payment, "boleto-payment"];
        if($payment instanceof UtilityPayment)
            return [$payment, "utility-payment"];

        if(!is_array($payment))
            throw new \Exception("Payment must either be a Transfer, a Transaction, a BoletoPayment, a UtilityPayment or an array.");

        $makerOptions = [
            "transfer" => function ($array) {
                return new Transfer($array);
            },
            "transaction" => function ($array) {
                return new Transaction($array);
            },
            "boleto-payment" => function ($array) {
                return new BoletoPayment($array);
            },
            "utility-payment" => function ($array) {
                return new UtilityPayment($array);
            },
        ];

        if (isset($makerOptions[$type]))
            $payment = API::fromApiJson($makerOptions[$type], $payment);

        return [$payment, $type];
    }

    private static function resource()
    {
        $request = function ($array) {
            return new PaymentRequest($array);
        };
        return [
            "name" => "PaymentRequest",
            "maker" => $request,
        ];
    }
}
