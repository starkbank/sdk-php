<?php

namespace StarkBank\SplitProfile;
use StarkCore\Utils\API;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;
use StarkBank\SplitProfile;


class Log extends Resource
{

    public $created;
    public $type;
    public $errors;
    public $splitProfile;

    /**
    # SplitProfile\Log object

    Every time a SplitProfile entity is updated, a corresponding SplitProfile\Log
    is generated for the entity. This log is never generated by the
    user, but it can be retrieved to check additional information
    on the SplitProfile.

    ## Attributes (return-only):
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - SplitProfile [SplitProfile]: SplitProfile entity to which the log refers to.
        - errors [array of strings]: array of errors linked to this SplitProfile event
        - type [string]: type of the SplitProfile event which triggered the log creation. ex: "created" or "updated"
        - created [DateTime]: creation datetime for the log.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->splitProfile = Checks::checkParam($params, "splitProfile");

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific Log

    Receive a single Log object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Log object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve Logs

    Receive a enumerator of Log objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [list of strings, default None]: filter retrieved objects by event types. ex: "created" or "updated"
        - profileIds [array of strings, default null]: array of SplitProfile ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of Log objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Log::resource(), $options);
    }

    /**
    # Retrieve paged SplitProfile\Logs

    Receive a list of up to 100 SplitProfile\Log objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - types [list of strings, default None]: filter retrieved objects by event types. ex: "created" or "updated"
        - profileIds [array of strings, default null]: array of SplitProfile ids to filter logs. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of SplitProfile\Log objects with updated attributes
        - cursor to retrieve the next page of SplitProfile\Log objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $splitProfileLog = function ($array) {
            $splitProfile = function ($array) {
                return new SplitProfile($array);
            };
            $array["splitProfile"] = API::fromApiJson($splitProfile, $array["profile"]);
            return new Log($array);
        };
        return [
            "name" => "SplitProfileLog",
            "maker" => $splitProfileLog,
        ];
    }
}
