<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class BoletoPayment extends Resource
{

    public $line;
    public $taxId;
    public $barCode;
    public $description;
    public $tags;
    public $amount;
    public $scheduled;
    public $status;
    public $fee;
    public $transactionIds;
    public $created;

    /**
    # BoletoPayment object

    When you initialize a BoletoPayment, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the array of created objects.

    ## Parameters (conditionally required):
        - line [string, default null]: Number sequence that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string, default null]: Bar code number that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34195819600000000621090063571277307144464000"

    ## Parameters (required):
        - taxId [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"

    ## Parameters (optional):
        - amount [integer, default null]: amount to be paid. If none is informed, the current boleto value will be used. ex: 23456 (= R$ 234.56)
        - scheduled [DateTime or string, default today]: payment scheduled date.
        - tags [array of strings]: array of strings for tagging

    ## Attributes (return-only):
        - id [string]: unique id returned when payment is created. ex: "5656565656565656"
        - status [string]: current payment status. ex: "success" or "failed"
        - fee [integer]: fee charged when the boleto payment is created. ex: 200 (= R$ 2.00)
        - transactionIds [list of strings]: ledger transaction ids linked to this BoletoPayment. ex: ["19827356981273"]
        - created [DateTime]: creation datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->line = Checks::checkParam($params, "line");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->barCode = Checks::checkParam($params, "barCode");
        $this->description = Checks::checkParam($params, "description");
        $this->tags = Checks::checkParam($params, "tags");
        $this->amount = Checks::checkParam($params, "amount");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->status = Checks::checkParam($params, "status");
        $this->fee = Checks::checkParam($params, "fee");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    function __toArray()
    {
        $array = get_object_vars($this);
        $array["scheduled"] = new StarkDate($array["scheduled"]);
        return $array;
    }

    /**
    # Create BoletoPayments

    Send an array of BoletoPayment objects for creation in the Stark Bank API

    ## Parameters (required):
        - payments [array of BoletoPayment objects]: array of BoletoPayment objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - array of BoletoPayment objects with updated attributes
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
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

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
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - BoletoPayment pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getContent($user, BoletoPayment::resource(), $id, "pdf");
    }

    /**
    # Retrieve BoletoPayments

    Receive a enumerator of BoletoPayment objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "success"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of BoletoPayment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, BoletoPayment::resource(), $options);
    }

    /**
    # Retrieve paged BoletoPayment

    Receive a list of up to 100 BoletoPayment objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "success"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of BoletoPayment objects with updated attributes
        - cursor to retrieve the next page of BoletoPayment objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, BoletoPayment::resource(), $options);
    }

    /**
    # Delete a BoletoPayment entity

    Delete a BoletoPayment entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: BoletoPayment unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - deleted BoletoPayment object
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
