<?php

namespace StarkBank;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkBank\Utils\Rest;


class DynamicBrcode extends Resource
{
    
    public $amount;
    public $expiration;
    public $tags;
    public $uuid;
    public $pictureUrl;
    public $updated;
    public $created;

    /**
    # DynamicBrcode object

    When you initialize a DynamicBrcode, the entity will not be automatically
    sent to the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    DynamicBrcodes are conciliated BR Codes that can be used to receive Pix transactions in a convenient way.
    When a DynamicBrcode is paid, a Deposit is created with the tags parameter containing the character “dynamic-brcode/” followed by the DynamicBrcode’s uuid "dynamic-brcode/{uuid}" for conciliation.
    Additionally, all tags passed on the DynamicBrcode will be transferred to the respective Deposit resource.

    ## Parameters (required):
        - amount [integer]: DynamicBrcode value in cents. Minimum = 0 (any value will be accepted). ex: 1234 (= R$ 12.34)

    ## Parameters (optional):
        - expiration [DateInterval or integer, default 3600 (1 hour)]: time interval in seconds between due date and expiration date. ex 123456789
        - tags [list of strings, default []]: list of strings for tagging, these will be passed to the respective Deposit resource when paid

    ## Attributes (return-only):
        - id [string]: id returned on creation, this is the BR code. ex: "00020126360014br.gov.bcb.pix0114+552840092118152040000530398654040.095802BR5915Jamie Lannister6009Sao Paulo620705038566304FC6C"
        - uuid [string]: unique uuid returned when the DynamicBrcode is created. ex: "4e2eab725ddd495f9c98ffd97440702d"
        - pictureUrl [string]: public QR Code (png image) URL. "https://sandbox.api.starkbank.com/v2/dynamic-brcode/d3ebb1bd92024df1ab6e5a353ee799a4.png"
        - updated [string]: creation datetime for the DynamicBrcode. ex: "2020-03-10 10:30:00.000"
        - created [string]: creation datetime for the DynamicBrcode. ex: "2020-03-10 10:30:00.000"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->expiration = Checks::checkDateInterval(Checks::checkParam($params, "expiration"));
        $this->tags = Checks::checkParam($params, "tags");
        $this->uuid = Checks::checkParam($params, "uuid");
        $this->pictureUrl = Checks::checkParam($params, "pictureUrl");
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Create DynamicBrcodes

    Send an array of DynamicBrcode objects for creation in the Stark Bank API

    ## Parameters (required):
        - brcodes [array of DynamicBrcode objects]: array of DynamicBrcode objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - array of DynamicBrcode objects with updated attributes
     */
    public static function create($brcodes, $user = null)
    {
        return Rest::post($user, DynamicBrcode::resource(), $brcodes);
    }

    /**
    # Retrieve a specific DynamicBrcode

    Receive a single DynamicBrcode object previously created in the Stark Bank API by passing its uuid

    ## Parameters (required):
        - uuid [string]: object unique uuid. ex: "4e2eab725ddd495f9c98ffd97440702d"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - DynamicBrcode object with updated attributes
     */
    public static function get($uuid, $user = null)
    {
        return Rest::getId($user, DynamicBrcode::resource(), $uuid);
    }

    /**
    # Retrieve DynamicBrcodes

    Receive an enumerator of DynamicBrcode objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - uuids [array of strings, default null]: array of uuids to filter retrieved objects. ex: ["901e71f2447c43c886f58366a5432c4b", "4e2eab725ddd495f9c98ffd97440702d"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of DynamicBrcode objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, DynamicBrcode::resource(), $options);
    }

    /**
    # Retrieve paged DynamicBrcodes

    Receive a list of up to 100 DynamicBrcode objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - uuids [array of strings, default null]: array of uuids to filter retrieved objects. ex: ["901e71f2447c43c886f58366a5432c4b", "4e2eab725ddd495f9c98ffd97440702d"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of DynamicBrcode objects with updated attributes
        - cursor to retrieve the next page of DynamicBrcode objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, DynamicBrcode::resource(), $options);
    }

    private static function resource()
    {
        $brcode = function ($array) {
            return new DynamicBrcode($array);
        };
        return [
            "name" => "DynamicBrcode",
            "maker" => $brcode,
        ];
    }
}
