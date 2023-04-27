<?php

namespace StarkBank\CorporatePurchase;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\CorporatePurchase;


class Log extends Resource
{

    public $type;
    public $purchase;
    public $corporateTransactionId;
    public $errors;
    public $created;

    /**
    # CorporatePurchase\Log object

    Every time an CorporatePurchase entity is updated, a corresponding CorporatePurchase\Log
    is generated for the entity. This log is never generated by the
    user, but it can be retrieved to check additional information
    on the CorporatePurchase.

    ## Attributes (return-only):
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - purchase [CorporatePurchase]: CorporatePurchase entity to which the log refers to.
        - corporateTransactionId [string]: transaction ID related to the CorporatePurchase.
        - errors [array of strings]: list of errors linked to this CorporatePurchase event
        - type [string]: type of the CorporatePurchase event which triggered the log creation. ex: "approved", "canceled", "confirmed", "denied", "reversed", "voided".
        - created [DateTime]: creation datetime for the CorporatePurchase. 
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->type = Checks::checkParam($params, "type");
        $this->purchase = Checks::checkParam($params, "purchase");
        $this->corporateTransactionId = Checks::checkParam($params, "corporateTransactionId");
        $this->errors = Checks::checkParam($params, "errors");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific CorporatePurchase\Log

    Receive a single CorporatePurchase\Log object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporatePurchase\Log object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve CorporatePurchase\Logs

    Receive an enumerator of CorporatePurchase\Log objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [array of strings, default null]: filter for log event types. ex: "approved", "canceled", "confirmed", "denied", "reversed", "voided"
        - purchaseIds [array of strings, default null]: array of CorporatePurchase ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - ids [list of strings, default null]: array of CorporatePurchase ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of CorporatePurchase\Log objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Log::resource(), $options);
    }

    /**
    # Retrieve paged CorporatePurchase\Logs

    Receive a list of up to 100 CorporatePurchase\Log objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [array of strings, default null]: filter for log event types. ex: "approved", "canceled", "confirmed", "denied", "reversed", "voided"
        - purchaseIds [array of strings, default null]: array of CorporatePurchase ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - ids [list of strings, default null]: array of CorporatePurchase ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of CorporatePurchase\Log objects with updated attributes
        - cursor to retrieve the next page of CorporatePurchase\Log objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $purchaseLog = function ($array) {
            $purchase = function ($array) {
                return new CorporatePurchase($array);
            };
            $array["purchase"] = API::fromApiJson($purchase, $array["purchase"]);
            return new Log($array);
        };
        return [
            "name" => "CorporatePurchaseLog",
            "maker" => $purchaseLog,
        ];
    }
}
