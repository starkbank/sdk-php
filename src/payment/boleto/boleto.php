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
        - line [string, default None]: Number sequence that describes the payment. Either 'line' or 'bar_code' parameters are required. If both are sent, they must match. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - bar_code [string, default None]: Bar code number that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34195819600000000621090063571277307144464000"

    ## Parameters (required):
        - tax_id [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"

    ## Parameters (optional):
        - scheduled [datetime.date, default today]: payment scheduled date. ex: datetime.date(2020, 3, 10)
        - tags [list of strings]: list of strings for tagging

    ## Attributes (return-only):
        - id [string, default None]: unique id returned when payment is created. ex: "5656565656565656"
        - status [string, default None]: current payment status. ex: "registered" or "paid"
        - amount [int, default None]: amount automatically calculated from line or bar_code. ex: 23456 (= R$ 234.56)
        - fee [integer, default None]: fee charged when boleto payment is created. ex: 200 (= R$ 2.00)
        - created [datetime.datetime, default None]: creation datetime for the payment. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->line = $params["line"];
        unset($params["line"]);
        $this->taxId = $params["taxId"];
        unset($params["taxId"]);
        $this->barCode = $params["barCode"];
        unset($params["barCode"]);
        $this->description = $params["description"];
        unset($params["description"]);
        $this->tags = $params["tags"];
        unset($params["tags"]);
        $this->scheduled = $params["scheduled"];
        unset($params["scheduled"]);
        $this->status = $params["status"];
        unset($params["status"]);
        $this->amount = $params["amount"];
        unset($params["amount"]);
        $this->fee = $params["fee"];
        unset($params["fee"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);

        Checks::checkParams($params);
    }

    /**
    Create BoletoPayments

    Send a list of BoletoPayment objects for creation in the Stark Bank API

    Parameters (required):
        payments [list of BoletoPayment objects]: list of BoletoPayment objects to be created in the API
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        list of BoletoPayment objects with updated attributes
     */
    public function create($user, $payments)
    {
        return Rest::post($user, BoletoPayment::resource(), $payments);
    }

    /**
    Retrieve a specific BoletoPayment

    Receive a single BoletoPayment object previously created by the Stark Bank API by passing its id

    Parameters (required):
        id [string]: object unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        BoletoPayment object with updated attributes
     */
    public function get($user, $id)
    {
        return Rest::getId($user, BoletoPayment::resource(), $id);
    }

    /**
    Retrieve a specific BoletoPayment pdf file

    Receive a single BoletoPayment pdf file generated in the Stark Bank API by passing its id.
    Only valid for boleto payments with "success" status.

    Parameters (required):
        id [string]: object unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        BoletoPayment pdf file
     */
    public function pdf($user, $id)
    {
        return Rest::getPdf($user, BoletoPayment::resource(), $id);
    }

    /**
    Retrieve BoletoPayments

    Receive a generator of BoletoPayment objects previously created in the Stark Bank API

    Parameters (optional):
        limit [integer, default None]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        status [string, default None]: filter for status of retrieved objects. ex: "paid"
        tags [list of strings, default None]: tags to filter retrieved objects. ex: ["tony", "stark"]
        after [datetime.date, default None] date filter for objects created only after specified date. ex: datetime.date(2020, 3, 10)
        before [datetime.date, default None] date filter for objects only before specified date. ex: datetime.date(2020, 3, 10)
        user [Project object, default None]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        generator of BoletoPayment objects with updated attributes
     */
    public function query($user, $options = [])
    {
        $options["after"] = Checks::checkDateTime($options["after"]);
        $options["before"] = Checks::checkDateTime($options["before"]);
        return Rest::getList($user, BoletoPayment::resource(), $options);
    }

    /**
    Delete a BoletoPayment entity

    Delete a BoletoPayment entity previously created in the Stark Bank API

    Parameters (required):
        id [string]: BoletoPayment unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        deleted BoletoPayment with updated attributes
     */
    public function delete($user, $id)
    {
        return Rest::deleteId($user, BoletoPayment::resource(), $id);
    }

    private function resource()
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
