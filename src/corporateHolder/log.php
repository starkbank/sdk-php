<?php

namespace StarkBank\CorporateHolder;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\CorporateHolder;


class Log extends Resource
{

    public $holder;
    public $type;
    public $created;

    /**
    # CorporateHolder\Log object

    Every time an CorporateHolder entity is updated, a corresponding CorporateHolder\Log
    is generated for the entity. This log is never generated by the
    user, but it can be retrieved to check additional information
    on the CorporateHolder.

    ## Attributes (return-only):
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - holder [CorporateHolder]: CorporateHolder entity to which the log refers to.
        - type [string]: type of the CorporateHolder event which triggered the log creation. ex: "blocked", "canceled", "created", "unblocked", "updated"
        - created [DateTime]: creation datetime for the log. 
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->holder = Checks::checkParam($params, "holder");
        $this->type = Checks::checkParam($params, "type");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific CorporateHolder\Log

    Receive a single CorporateHolder\Log object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateHolder\Log object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve CorporateHolder\Logs

    Receive an enumerator of CorporateHolder\Log objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [array of strings, default null]: filter for log event types. ex: "created", "blocked"
        - holderIds [array of strings, default null]: array of CorporateHolder ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - ids [array of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of CorporateHolder\Log objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Log::resource(), $options);
    }

    /**
    # Retrieve paged CorporateHolder\Logs

    Receive a list of up to 100 CorporateHolder\Log objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [array of strings, default null]: filter for log event types. ex: "created", "blocked"
        - holderIds [array of strings, default null]: array of CorporateHolder ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - ids [array of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of CorporateHolder\Log objects with updated attributes
        - cursor to retrieve the next page of CorporateHolder\Log objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $holderLog = function ($array) {
            $holder = function ($array) {
                return new CorporateHolder($array);
            };
            $array["holder"] = API::fromApiJson($holder, $array["holder"]);
            return new Log($array);
        };
        return [
            "name" => "CorporateHolderLog",
            "maker" => $holderLog,
        ];
    }
}
