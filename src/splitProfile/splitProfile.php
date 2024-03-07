<?php

namespace StarkBank;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;

class SplitProfile extends Resource
{
    public $interval;
    public $delay;
    public $tags;
    public $status;
    public $created;
    public $updated;
    
    /**
    # SplitProfile object
    
    When you initialize a SplitProfile, the entity will not be automatically
    created in the Stark Bank API    

    ## Parameters (required):
        - interval [string]: frequency of transfer, default "week". Options: "day", "week", "month"
        - delay [string]: how long the amount will stay at the workspace in milliseconds
    
    ## Attributes (return-only):
        - id [string]: unique id returned when the splitProfile is created. ex: "5656565656565656"
        - delay [DateInterval or integer]: ex: 604800,
        - interval [string]: ex: "month",
        - tags [array of strings, default: []]: ex: ["Pay weekly"],
        - status [string]: current splitProfile status. ex: "created"
        - created [datetime.datetime]: creation datetime for the splitProfile. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
        - updated [datetime.datetime]: latest update datetime for the splitProfile. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
     */
    function __construct(array $params)
    {
        parent::__construct($params);
        $this->interval = Checks::checkParam($params,"interval");
        $this->delay = Checks::checkParam($params,"delay");
        $this->tags = Checks::checkParam($params,"tags");
        $this->status = Checks::checkParam($params,"status");
        $this->created = Checks::checkDateTime(Checks::checkParam($params,"created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params,"updated"));

        Checks::checkParams($params);
    }

    /**
    # Update SplitProfile or create it if it doesn't exist

    Send a list of SplitProfile objects for creation in the Stark Bank API
    
    ## Parameters (required):
        - splitProfile [list of SplitProfile objects]: list of SplitProfile objects to be created in the API
    ## Parameters (optional):
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - list of SplitProfile objects with updated attributes
     */
    public static function put($splitProfile, $user = null)
    {
        return Rest::putMulti($user, SplitProfile::resource(), $splitProfile);
    }

    /**
    # Retrieve a specific SplitProfile

    Receive a single SplitProfile object previously created in the Stark Bank API by its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call
    
    ## Return:
        - SplitProfile object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, SplitProfile::resource(), $id);
    }

    /**
    # Retrieve SplitProfile
    
    Receive a enumerator of SplitProfile objects previously created in the Stark Bank API
    
    ## Parameters (optional):
        - limit [integer, default None]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        - after [datetime.date or string, default None]: date filter for objects created or updated only after specified date. ex: datetime.date(2020, 3, 10)
        - before [datetime.date or string, default None]: date filter for objects created or updated only before specified date. ex: datetime.date(2020, 3, 10)
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - enumerator of SplitProfile objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, SplitProfile::resource(), $options);
    }

    /**
    # Retrieve paged SplitProfiles

    Receive a list of up to 100 SplitProfile objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.
    
    ## Parameters (optional):
        - cursor [string, default None]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [datetime.date or string, default None]: date filter for objects created or updated only after specified date. ex: datetime.date(2020, 3, 10)
        - before [datetime.date or string, default None]: date filter for objects created or updated only before specified date. ex: datetime.date(2020, 3, 10)
        - status [string, default None]: filter for status of retrieved objects. ex: "success" or "failed"
        - tags [list of strings, default None]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default None]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - list of SplitProfile objects with updated attributes
        - cursor to retrieve the next page of SplitProfile objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, SplitProfile::resource(), $options);
    }

    private static function resource()
    {
        $splitProfile = function ($array) {
            return new SplitProfile($array);
        };
        return [
            "name" => "SplitProfile",
            "maker" => $splitProfile,
        ];
    }
}
