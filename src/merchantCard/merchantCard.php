<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class MerchantCard extends Resource
{
    public $ending;
    public $fundingType;
    public $holderName;
    public $network;
    public $status;
    public $tags;
    public $expiration;
    public $created;
    public $updated;

    /**
    # MerchantCard object

    The MerchantCard resource stores information about cards used in approved purchases. These cards can be reused in new purchases without creating a new session.

    ## Attributes (return-only):
        - id [string]: unique id for the merchant card.
        - ending [string]: last 4 digits of the card number.
        - fundingType [string]: funding type. Options: "credit", "debit".
        - holderName [string]: name of the card holder.
        - network [string]: card network.
        - status [string]: current card status. Options: "active", "expired", "canceled", "blocked".
        - tags [array of strings]: tags associated with the card.
        - expiration [string]: card expiration date. ex: "2025-06"
        - created [DateTime]: creation datetime for the card.
        - updated [DateTime]: latest update datetime for the card.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->ending = Checks::checkParam($params, "ending");
        $this->fundingType = Checks::checkParam($params, "fundingType");
        $this->holderName = Checks::checkParam($params, "holderName");
        $this->network = Checks::checkParam($params, "network");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->expiration = Checks::checkParam($params, "expiration");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific MerchantCard

    Receive a single MerchantCard object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id.

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - MerchantCard object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, self::resource(), $id);
    }

    /**
    # Retrieve MerchantCards

    Receive an enumerator of MerchantCard objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null.
        - after, before [DateTime or string, default null]: date filters.
        - status [string, default null]: filter for status of retrieved objects.
        - tags [array of strings, default null]: tags to filter retrieved objects.
        - ids [array of strings, default null]: array of ids to filter retrieved objects.
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of MerchantCard objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, self::resource(), $options);
    }

    /**
    # Retrieve paged MerchantCards

    Receive a list of up to 100 MerchantCard objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100.
        - (same filters as query: after, before, status, tags, ids)
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - list of MerchantCard objects with updated attributes
        - cursor to retrieve the next page of MerchantCard objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, self::resource(), $options);
    }

    private static function resource()
    {
        $merchantCard = function ($array) {
            return new MerchantCard($array);
        };
        return [
            "name" => "MerchantCard",
            "maker" => $merchantCard,
        ];
    }
}
