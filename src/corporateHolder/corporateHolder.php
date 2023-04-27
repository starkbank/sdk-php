<?php

namespace StarkBank;

use StarkBank\CorporateHolder\Permission;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class CorporateHolder extends Resource
{

    public $name;
    public $centerId;
    public $permissions;
    public $rules;
    public $tags;
    public $status;
    public $created;
    public $updated;

    /**
    # CorporateHolder object

    The CorporateHolder describes a card holder that may group several cards.

    When you initialize a CorporateHolder, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the created object.

    ## Parameters (required):
        - name [string]: card holder name. ex: "Tony Stark"

    ## Parameters (optional):
        - centerId [string, default null]: target cost center ID. ex: "5656565656565656"
        - permissions [array of Permission object, default []]: array of Permission object representing access granted to an user for a particular cardholder
        - rules [array of CorporateRule, default []]: [EXPANDABLE] list of holder spending rules.
        - tags [array of strings, default []]: list of strings for tagging. ex: ["travel", "food"]

    ## Attributes (return-only):
        - id [string]: unique id returned when CorporateHolder is created. ex: "5656565656565656"
        - status [string]: current CorporateHolder status. ex: "active", "blocked", "canceled"
        - created [DateTime]: creation datetime for the CorporateHolder. 
        - updated [DateTime]: latest update datetime for the CorporateHolder. 
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->name = Checks::checkParam($params, "name");
        $this->centerId = Checks::checkParam($params, "centerId");
        $this->permissions = Permission::parsePermissions(Checks::checkParam($params, "permissions"));
        $this->rules = CorporateRule::parseRules(Checks::checkParam($params, "rules"));
        $this->tags = Checks::checkParam($params, "tags");
        $this->status = Checks::checkParam($params, "status");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create CorporateHolders

    Send a list of CorporateHolder objects for creation in the Stark Bank API

    ## Parameters (required):
        - holders [array of CorporateHolder objects]: list of CorporateHolder objects to be created in the API

    ## Parameters (optional):
        - params [dictionary of optional parameters]:
            - expand [array of strings, default null]: fields to to expand information. ex: ["rules"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - list of CorporateHolder objects with updated attributes
     */
    public static function create($holders, $params = null, $user = null)
    {
        return Rest::post($user, CorporateHolder::resource(), $holders, $params);
    }

    /**
    # Retrieve a specific CorporateHolder

    Receive a single CorporateHolder object previously created in the Stark Bank API by its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - params [dictionary of optional parameters]:
            - expand [array of strings, default []]: fields to to expand information. ex: ["rules", "securityCode", "number", "expiration"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateHolder object with updated attributes
     */
    public static function get($id, $param = null, $user = null)
    {
        return Rest::getId($user, CorporateHolder::resource(), $id, $param);
    }

    /**
    # Retrieve CorporateHolders

    Receive an enumerator of CorporateHolder objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [Date or string, default null] date filter for objects created only after specified date. 
        - before [Date or string, default null] date filter for objects created only before specified date. 
        - status [string, default null]: filter for status of retrieved objects. ex: "active", "blocked", "canceled"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - expand [array of strings, default []]: fields to to expand information. ex: ["rules"]
        - ids [array of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of CorporateHolder objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, CorporateHolder::resource(), $options);
    }

    /**
    # Retrieve paged Holders

    Receive a list of CorporateHolder objects previously created in the Stark Bank API and the cursor to the next page.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "active", "blocked", "canceled"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - expand [array of strings, default []]: fields to to expand information. ex: ["rules"]
        - ids [array of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of CorporateHolder objects with updated attributes
        - cursor to retrieve the next page of CorporateHolder objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, CorporateHolder::resource(), $options);
    }

    /**
    # Update CorporateHolder entity

    Update a CorporateHolder by passing id, if it hasn't been paid yet.

    ## Parameters (required):
        - id [array of strings]: CorporateHolder unique ids. ex: "5656565656565656"

    ## Parameters (optional):
        - centerId [string, default null]: target cost center ID. ex: "5656565656565656"
        - permissions [array of Permission object, default null]: array of Permission object representing access granted to an user for a particular cardholder.
        - status [string, default null]: You may block the CorporateHolder by passing 'blocked' in the status. ex: "blocked"
        - name [string, default null]: card holder name. ex: "Jaime Lannister"
        - tags [array of strings, default null]: Slice of strings for tagging
        - rules [array of maps, default null]: Slice of maps with "amount": int, "currencyCode": string, "id": string, "interval": string, "name": string pairs

    ## Return:
        - target CorporateHolder with updated attributes
     */
    public static function update($id, $options = [], $user = null)
    {
        return Rest::patchId($user, CorporateHolder::resource(), $id, $options);
    }

    /**
    # Cancel a CorporateHolder entity

    Cancel a CorporateHolder entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: CorporateHolder unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - canceled CorporateHolder object
     */
    public static function cancel($id, $user = null)
    {
        return Rest::deleteId($user, CorporateHolder::resource(), $id);
    }

    private static function resource()
    {
        $holder = function ($array) {
            return new CorporateHolder($array);
        };
        return [
            "name" => "CorporateHolder",
            "maker" => $holder,
        ];
    }
}
