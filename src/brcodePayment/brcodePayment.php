<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\BrcodePayment\Rule;


class BrcodePayment extends Resource
{

    public $brcode;
    public $taxId;
    public $description;
    public $amount;
    public $scheduled;
    public $name;
    public $tags;
    public $rules;
    public $status;
    public $type;
    public $transactionIds;
    public $fee;
    public $updated;
    public $created;

    /**
    # BrcodePayment object

    When you initialize a BrcodePayment, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (required):
        - brcode [string]: String loaded directly from the QRCode or copied from the invoice. ex: "00020126580014br.gov.bcb.pix0136a629532e-7693-4846-852d-1bbff817b5a8520400005303986540510.005802BR5908T"Challa6009Sao Paulo62090505123456304B14A"
        - taxId [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"
    
    ## Parameters (conditionally required):
        - amount [integer, default null]: amount automatically calculated from line or barCode. ex: 23456 (= R$ 234.56)

    ## Parameters (optional):    
        - scheduled [DateTime or string, default now]: payment scheduled date or datetime. ex: "2020-11-25T17:59:26.249976+00:00"
        - tags [list of strings, default null]: list of strings for tagging  
        - rules [list of BrcodePayment\Rules, default []]: list of BrcodePayment\Rule objects for modifying payment behavior. ex: [BrcodePayment\Rule(key=>"resendingLimit", value=>5)]
    
    ## Attributes (return-only):
        - id [string, default null]: unique id returned when payment is created. ex: "5656565656565656"
        - name [string]: receiver name. ex: "Jon Snow"
        - status [string]: current payment status. ex: "success" or "failed"
        - type [string]: brcode type. ex: "static" or "dynamic"
        - transactionIds [list of strings]: ledger transaction ids linked to this brcode payment (if there are more than one, all but first are reversals). ex: ["19827356981273"]
        - fee [integer]: fee charged when the brcode payment is created. ex: 200 (= R$ 2.00)
        - updated [DateTime]: latest update datetime for the payment.
        - created [DateTime]: creation datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params);
        
        $this->brcode = Checks::checkParam($params, "brcode");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->description = Checks::checkParam($params, "description");
        $this->amount = Checks::checkParam($params, "amount");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->name = Checks::checkParam($params, "name");
        $this->tags = Checks::checkParam($params, "tags");
        $this->rules = Rule::parseRules(Checks::checkParam($params, "rules"));
        $this->status = Checks::checkParam($params, "status");
        $this->type = Checks::checkParam($params, "type");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->fee = Checks:: checkParam($paramsm, "fee");
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        $this->created = Checks::checkDateTime(Checks::checkParam($prams, "created"));

        Checks::checkParams($params);
    }

    function __toArray()
    {
        $array = get_object_vars($this);
        $array["scheduled"] = new StarkDate($array["scheduled"]);
        return $array;
    }

    /**
    # Create BrcodePayments

    Send an array of BrcodePayment objects for creation in the Stark Bank API

    ## Parameters (required):
        - payments [array of BrcodePayment objects]: array of BrcodePayment objects to be created in the API
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - array of BrcodePayment objects with updated attributes
     */
    public static function create($payments, $user = null)
    {
        return Rest::post($user, BrcodePayment::resource(), $payments);
    }

    /**
    # Retrieve a specific BrcodePayment

    Receive a single BrcodePayment object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - BrcodePayment object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, BrcodePayment::resource(), $id);
    }

    /**
    # Retrieve a specific BrcodePayment pdf file

    Receive a single BrcodePayment pdf receipt file generated in the Stark Bank API by passing its id.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - BrcodePayment pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getContent($user, BrcodePayment::resource(), $id, "pdf");
    }

    /**
    # Update notification BrcodePayment entity

    Update notification BrcodePayment by passing id.

    ## Parameters (required):
        - id [array of strings]: BrcodePayment unique ids. ex: "5656565656565656"
        - status [string]: If the BrcodePayment hasn't been paid yet, you may cancel it by passing "canceled" in the status
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - target BrcodePayment with updated attributes
     */
    public static function update($id, $options = [], $user = null)
    {
        return Rest::patchId($user, BrcodePayment::resource(), $id, $options);
    }

    /**
    # Retrieve BrcodePayments

    Receive an enumerator of BrcodePayment objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of BrcodePayment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, BrcodePayment::resource(), $options);
    }

    /**
    # Retrieve paged BrcodePayments

    Receive a list of up to 100 BrcodePayment objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "success"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of BrcodePayment objects with updated attributes
        - cursor to retrieve the next page of BrcodePayment objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, BrcodePayment::resource(), $options);
    }

    private static function resource()
    {
        $payment = function ($array) {
            return new BrcodePayment($array);
        };
        return [
            "name" => "BrcodePayment",
            "maker" => $payment,
        ];
    }
}
