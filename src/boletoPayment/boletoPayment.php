<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class BoletoPayment extends Resource
{
    /**
    # BoletoPayment object

    When you initialize a BoletoPayment, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (conditionally required):
        - line [string, default null]: Number sequence that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string, default null]: Bar code number that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34195819600000000621090063571277307144464000"

    ## Parameters (required):
        - taxId [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"

    ## Parameters (optional):
        - scheduled [DateTime or string, default today]: payment scheduled date.
        - tags [list of strings]: list of strings for tagging

    ## Attributes (return-only):
        - id [string, default null]: unique id returned when payment is created. ex: "5656565656565656"
        - status [string, default null]: current payment status. ex: "registered" or "paid"
        - amount [int, default null]: amount automatically calculated from line or bar_code. ex: 23456 (= R$ 234.56)
        - fee [integer, default null]: fee charged when boleto payment is created. ex: 200 (= R$ 2.00)
        - created [DateTime, default null]: creation datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->line = Checks::checkParam($params, "line");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->barCode = Checks::checkParam($params, "barCode");
        $this->description = Checks::checkParam($params, "description");
        $this->tags = Checks::checkParam($params, "tags");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->status = Checks::checkParam($params, "status");
        $this->amount = Checks::checkParam($params, "amount");
        $this->fee = Checks::checkParam($params, "fee");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Create BoletoPayments

    Send a list of BoletoPayment objects for creation in the Stark Bank API

    ## Parameters (required):
        - payments [list of BoletoPayment objects]: list of BoletoPayment objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - list of BoletoPayment objects with updated attributes
     */
    public static function create($payments, $user = null)
    {
        return Rest::post($user, BoletoPayment::resource(), $payments);
    }

    /**
    # Retrieve a specific BoletoPayment

    Receive a single BoletoPayment object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - BoletoPayment object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, BoletoPayment::resource(), $id);
    }

    /**
    # Retrieve a specific BoletoPayment pdf file

    Receive a single BoletoPayment pdf file generated in the Stark Bank API by passing its id.
    Only valid for boleto payments with "success" status.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - BoletoPayment pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getPdf($user, BoletoPayment::resource(), $id);
    }

    /**
    # Retrieve BoletoPayments

    Receive a enumerator of BoletoPayment objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date.
        - before [DateTime or string, default null] date filter for objects created only before specified date.
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "paid"
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of BoletoPayment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, BoletoPayment::resource(), $options);
    }

    /**
    # Delete a BoletoPayment entity

    Delete a BoletoPayment entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: BoletoPayment unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - deleted BoletoPayment with updated attributes
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, BoletoPayment::resource(), $id);
    }

    private static function resource()
    {
        $payment = function ($array) {
            return new BoletoPayment($array);
        };
        return [
            "name" => "BoletoPayment",
            "maker" => $payment,
        ];
    }
}
