<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class BoletoHolmes extends Resource
{

    public $boletoId;
    public $tags;
    public $status;
    public $result;
    public $created;
    public $updated;

    /**
    # BoletoHolmes object

    When you initialize a BoletoHolmes, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the array of created objects.

    ## Parameters (required):
        - boletoId [string]: investigated boleto entity ID. ex: "5656565656565656"

    ## Parameters (optional):
        - tags [array of strings]: array of strings for tagging

    ## Attributes (return-only):
        - id [string]: unique id returned when holmes is created. ex: "5656565656565656"
        - status [string]: current holmes status. ex: "solving" or "solved"
        - result [string]: result of boleto status investigation. ex: "paid" or "cancelled"
        - created [DateTime]: creation datetime for the holmes. ex: DateTime('2020-01-01T15:03:01.012345Z')
        - updated [DateTime]: latest update datetime for the holmes. ex: DateTime('2020-01-01T15:03:01.012345Z')
     */

    function __construct(array $params)
    {
        parent::__construct($params);

        $this->boletoId = Checks::checkParam($params, "boletoId");
        $this->tags = Checks::checkParam($params, "tags");
        $this->status = Checks::checkParam($params, "status");
        $this->result = Checks::checkParam($params, "result");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create BoletoHolmes
    
    Send an array of BoletoHolmes objects for creation in the Stark Bank API

    ## Parameters (required):
        - holmes [array of BoletoHolmes objects]: array of BoletoHolmes objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - array of BoletoHolmes objects with updated attributes
     */
    public static function create($holmes, $user = null)
    {
        return Rest::post($user, BoletoHolmes::resource(), $holmes);
    }

    /**
    # Retrieve a specific BoletoHolmes

    Receive a single BoletoHolmes object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - BoletoHolmes object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, BoletoHolmes::resource(), $id);
    }

    /**
    # Retrieve BoletoHolmes

    Receive a generator of BoletoHolmes objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "solved"
        - boletoId [string, default null]: filter for holmes that investigate a specific boleto by its ID. ex: "5656565656565656"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - generator of BoletoHolmes objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, BoletoHolmes::resource(), $options);
    }

    /**
    # Retrieve paged BoletoHolmes

    Receive a list of up to 100 BoletoHolmes objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "solved"
        - boletoId [string, default null]: filter for holmes that investigate a specific boleto by its ID. ex: "5656565656565656"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of BoletoHolmes objects with updated attributes
        - cursor to retrieve the next page of BoletoHolmes objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, BoletoHolmes::resource(), $options);
    }

    private static function resource()
    {
        $sherlock = function ($array) {
            return new BoletoHolmes($array);
        };
        return [
            "name" => "BoletoHolmes",
            "maker" => $sherlock,
        ];
    }

}



