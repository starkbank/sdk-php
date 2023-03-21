<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class UtilityPayment extends Resource
{

    public $line;
    public $barCode;
    public $description;
    public $tags;
    public $scheduled;
    public $status;
    public $amount;
    public $fee;
    public $type;
    public $transactionIds;
    public $created;
    public $updated;

    /**
    # UtilityPayment object

    When you initialize a UtilityPayment, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the array of created objects.

    ## Parameters (conditionally required):
        - line [string, default null]: Number sequence that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string, default null]: Bar code number that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34195819600000000621090063571277307144464000"

    ## Parameters (required):
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"

    ## Parameters (optional):
        - scheduled [DateTime or string, default today]: payment scheduled date.
        - tags [array of strings]: array of strings for tagging

    ## Attributes (return-only):
        - id [string]: unique id returned when payment is created. ex: "5656565656565656"
        - status [string]: current payment status. ex: "success" or "failed"
        - amount [integer]: amount automatically calculated from line or bar_code. ex: 23456 (= R$ 234.56)
        - fee [integer]: fee charged when utility payment is created. ex: 200 (= R$ 2.00)
        - type [string]: payment type. ex: "utility"
        - transactionIds [array of strings]: ledger transaction ids linked to this UtilityPayment. ex: ["19827356981273"]
        - created [DateTime]: creation datetime for the payment.
        - updated [DateTime]: updated datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->line = Checks::checkParam($params, "line");
        $this->barCode = Checks::checkParam($params, "barCode");
        $this->description = Checks::checkParam($params, "description");
        $this->tags = Checks::checkParam($params, "tags");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->status = Checks::checkParam($params, "status");
        $this->amount = Checks::checkParam($params, "amount");
        $this->fee = Checks::checkParam($params, "fee");
        $this->type = Checks::checkParam($params, "type");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    function __toArray()
    {
        $array = get_object_vars($this);
        $array["scheduled"] = new StarkDate($array["scheduled"]);
        return $array;
    }

    /**
    # Create UtilityPayments

    Send an array of UtilityPayment objects for creation in the Stark Bank API

    ## Parameters (required):
        - payments [array of UtilityPayment objects]: array of UtilityPayment objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - array of UtilityPayment objects with updated attributes
     */
    public static function create($payments, $user = null)
    {
        return Rest::post($user, UtilityPayment::resource(), $payments);
    }

    /**
    # Retrieve a specific UtilityPayment

    Receive a single UtilityPayment object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - UtilityPayment object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, UtilityPayment::resource(), $id);
    }

    /**
    # Retrieve a specific UtilityPayment pdf file

    Receive a single UtilityPayment pdf file generated in the Stark Bank API by passing its id.
    Only valid for utility payments with "success" status.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - UtilityPayment pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getContent($user, UtilityPayment::resource(), $id, "pdf");
    }

    /**
    # Retrieve UtilityPayments

    Receive a enumerator of UtilityPayment objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "paid"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of UtilityPayment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, UtilityPayment::resource(), $options);
    }


    /**
    # Retrieve paged UtilityPayments

    Receive a list of up to 100 UtilityPayment objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "paid"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of UtilityPayment objects with updated attributes
        - cursor to retrieve the next page of UtilityPayment objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, UtilityPayment::resource(), $options);
    }

    /**
    # Delete a UtilityPayment entity

    Delete a UtilityPayment entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: UtilityPayment unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - deleted UtilityPayment object
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, UtilityPayment::resource(), $id);
    }

    private static function resource()
    {
        $payment = function ($array) {
            return new UtilityPayment($array);
        };
        return [
            "name" => "UtilityPayment",
            "maker" => $payment,
        ];
    }
}
