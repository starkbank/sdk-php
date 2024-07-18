<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\API;
use StarkCore\Utils\StarkDate;


class CorporateCard extends Resource
{

    public $holderId;
    public $holderName;
    public $displayName;
    public $rules;
    public $tags;
    public $streetLine1;
    public $streetLine2;
    public $district;
    public $city;
    public $stateCode;
    public $zipCode;
    public $type;
    public $status;
    public $number;
    public $securityCode;
    public $expiration;
    public $created;
    public $updated;

    /**
    # CorporateCard object

    The CorporateCard struct displays the information of the cards created in your Workspace.
    Sensitive information will only be returned when the "expand" parameter is used, to avoid security concerns.

    When you initialize a CorporateCard, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the created object.

    ## Parameters (required):
        - holderId [string]: card holder unique id. ex: "5656565656565656"
        
    ## Attributes (return-only):
        - id [string]: unique id returned when CorporateCard is created. ex: "5656565656565656"
        - holderName [string]: card holder name. ex: "Tony Stark"
        - displayName [string]: card displayed name. ex: "ANTHONY STARK"
        - rules [array of CorporateRule]: [EXPANDABLE] array of card spending rules.
        - tags [array of strings]: array of strings for tagging. ex: ["travel", "food"]
        - streetLine1 [string, default sub-issuer street line 1]: card holder main address. ex: "Av. Paulista, 200"
        - streetLine2 [string, default sub-issuer street line 2]: card holder address complement. ex: "Apto. 123"
        - district [string, default sub-issuer district]: card holder address district / neighbourhood. ex: "Bela Vista"
        - city [string, default sub-issuer city]: card holder address city. ex: "Rio de Janeiro"
        - stateCode [string, default sub-issuer state code]: card holder address state. ex: "GO"
        - zipCode [string, default sub-issuer zip code]: card holder address zip code. ex: "01311-200"
        - type [string]: card type. ex: "virtual"
        - status [string]: current CorporateCard status. ex: "active", "blocked", "canceled", "expired".
        - number [string]: [EXPANDABLE] masked card number. Expand to unmask the value. ex: "123".
        - securityCode [string]: [EXPANDABLE] masked card verification value (cvv). Expand to unmask the value. ex: "123".
        - expiration [string]: [EXPANDABLE] masked card expiration datetime. Expand to unmask the value. 
        - created [DateTime]: creation datetime for the CorporateCard. 
        - updated [DateTime]: latest update datetime for the CorporateCard. 
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->holderId = Checks::checkParam($params, "holderId");
        $this->holderName = Checks::checkParam($params, "holderName");
        $this->displayName = Checks::checkParam($params, "displayName");
        $this->rules = CorporateRule::parseRules(Checks::checkParam($params, "rules"));
        $this->tags = Checks::checkParam($params, "tags");
        $this->streetLine1 = Checks::checkParam($params, "streetLine1");
        $this->streetLine2 = Checks::checkParam($params, "streetLine2");
        $this->district = Checks::checkParam($params, "district");
        $this->city = Checks::checkParam($params, "city");
        $this->stateCode = Checks::checkParam($params, "stateCode");
        $this->zipCode = Checks::checkParam($params, "zipCode");
        $this->type = Checks::checkParam($params, "type");
        $this->status = Checks::checkParam($params, "status");
        $this->number = Checks::checkParam($params, "number");
        $this->securityCode = Checks::checkParam($params, "securityCode");
        $expiration = Checks::checkParam($params, "expiration");
        if (!is_null($expiration) && preg_match("/(\*+)/", $expiration) === 1)
            $expiration = null;
        $this->expiration = Checks::checkDateTime($expiration);
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create CorporateCard

    Send a CorporateCard object for creation in the Stark Bank API

    ## Parameters (required):
        - card [CorporateCard objects]: CorporateCard object to be created in the API

    ## Parameters (optional):
        - params [dictionary of optional parameters]:
            - expand [array of strings, default null]: fields to to expand information. ex: ["rules", "securityCode", "number", "expiration"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateCard object with updated attributes
     */
    public static function create($card, $params = [], $user = null)
    {
        $path = API::endpoint(CorporateCard::resource()["name"]) . "/" . "token";
        $json = Rest::postRaw($user, $path, API::apiJson($card), null, true, $params)->json();
        $entityJson = $json[API::lastName(CorporateCard::resource()["name"])];
        return API::fromApiJson(CorporateCard::resource()["maker"], $entityJson);
    }

    /**
    # Retrieve CorporateCards

    Receive an enumerator of CorporateCard objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "active", "blocked", "canceled", "expired"
        - types [string, default null]: card type. ex: "virtual"
        - holderIds [array of strings, default null]: card holder IDs. ex: ["5656565656565656", "4545454545454545"]
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - expand [array of strings, default []]: fields to to expand information. ex: ["rules", "securityCode", "number", "expiration"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of CorporateCard objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));

        return Rest::getList($user, CorporateCard::resource(), $options);
    }

    /**
    # Retrieve paged CorporateCards

    Receive an array of CorporateCard objects previously created in the Stark Bank API and the cursor to the next page.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"  
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "active", "blocked", "canceled" or "expired"
        - types [string, default null]: card type. ex: "virtual"
        - holderIds [array of strings, default null]: card holder IDs. ex: ["5656565656565656", "4545454545454545"]
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - expand [array of strings, default []]: fields to to expand information. ex: ["rules", "securityCode", "number", "expiration"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - array of CorporateCard objects with updated attributes
        - cursor to retrieve the next page of CorporateCard objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));

        return Rest::getPage($user, CorporateCard::resource(), $options);
    }

    /**
    # Retrieve a specific CorporateCard

    Receive a single CorporateCard object previously created in the Stark Bank API by its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - params [dictionary of optional parameters]:
            - expand [array of strings, default null]: fields to expand information. ex: ["rules", "securityCode", "number", "expiration"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateCard object with updated attributes
     */
    public static function get($id, $params=null, $user = null)
    {
        return Rest::getId($user, CorporateCard::resource(), $id, $params);
    }

    /**
    # Update CorporateCard entity

    Update a CorporateCard by passing its id.

    ## Parameters (required):
        - id [string]: CorporateCard id. ex: "5656565656565656"

    ## Parameters (optional):
        - status [string, default null]: You may block the CorporateCard by passing 'blocked' in the status
        - pin [string, default null]: You may unlock your physical card by passing its PIN. This is also the PIN you use to authorize a purchase.
        - displayName [string, default null]: card displayed name
        - rules [array of dictionaries, default null]: array of dictionaries with "amount": int, "currencyCode": string, "id": string, "interval": string, "name": string pairs.
        - tags [array of strings, default null]: array of strings for tagging
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - target CorporateCard with updated attributes
     */
    public static function update($id, $options = [], $user = null)
    {
        return Rest::patchId($user, CorporateCard::resource(), $id, $options);
    }

    /**
    # Cancel a CorporateCard entity

    Cancel a CorporateCard entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: CorporateCard unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - canceled CorporateCard object
     */
    public static function cancel($id, $user = null)
    {
        return Rest::deleteId($user, CorporateCard::resource(), $id);
    }

    private static function resource()
    {
        $card = function ($array) {
            return new CorporateCard($array);
        };
        return [
            "name" => "CorporateCard",
            "maker" => $card,
        ];
    }
}
