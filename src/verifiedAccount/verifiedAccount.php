<?php

namespace StarkBank;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;


class VerifiedAccount extends Resource
{
    public $taxId;
    public $bankCode;
    public $branchCode;
    public $keyId;
    public $name;
    public $number;
    public $type;
    public $tags;
    public $bankName;
    public $status;
    public $created;
    public $updated;

    /**
    # VerifiedAccount object

    When you initialize a VerifiedAccount, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (required):
        - taxId [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"

    ## Parameters (conditionally required):
        - bankCode [string]: code of the receiver bank institution in Brazil. If an ISPB (8 digits) is informed, a Pix transfer will be created, else a TED will be issued. Required if verifying with bank details. ex: "20018183" or "341"
        - branchCode [string]: receiver bank account branch. Use '-' in case there is a verifier digit. Required if verifying with bank details. ex: "1357-9"
        - keyId [string]: pix key identifier. Required if verifying with Pix key. ex: "tony@starkbank.com", "012.345.678-90"
        - name [string]: receiver full name. Required if verifying with bank details. ex: "Anthony Edward Stark"
        - number [string]: receiver bank account number. Use '-' before the verifier digit. Required if verifying with bank details. ex: "876543-2"
        - type [string]: verified account type. Required if verifying with bank details. ex: "checking", "savings", "salary" or "payment"

    ## Parameters (optional):
        - tags [list of strings, default []]: list of strings for reference when searching for verified accounts. ex: ["employees", "monthly"]

    ## Attributes (return-only):
        - id [string]: unique id returned when the VerifiedAccount is created. ex: "5656565656565656"
        - bankName [string]: bank name associated with the verified account. ex: "Stark Bank"
        - status [string]: current verified account status. ex: "creating", "created", "processing", "active", "failed" or "canceled"
        - created [DateTime]: creation datetime for the verified account. ex: "2020-03-10 10:30:00.000"
        - updated [DateTime]: update datetime for the verified account. ex: "2020-03-10 10:30:00.000"
     */
    function __construct(array $params)
    {
        parent::__construct($params);
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->bankCode = Checks::checkParam($params, "bankCode");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->keyId = Checks::checkParam($params, "keyId");
        $this->name = Checks::checkParam($params, "name");
        $this->number = Checks::checkParam($params, "number");
        $this->type = Checks::checkParam($params, "type");
        $this->tags = Checks::checkParam($params, "tags");
        $this->bankName = Checks::checkParam($params, "bankName");
        $this->status = Checks::checkParam($params, "status");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create VerifiedAccounts

    Send a list of VerifiedAccount objects for creation in the Stark Bank API

    ## Parameters (required):
        - verifiedAccounts [list of VerifiedAccount objects]: list of VerifiedAccount objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - list of VerifiedAccount objects with updated attributes
     */
    public static function create($verifiedAccounts, $user = null)
    {
        return Rest::post($user, VerifiedAccount::resource(), $verifiedAccounts);
    }

    /**
    # Retrieve a specific VerifiedAccount

    Receive a single VerifiedAccount object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - VerifiedAccount object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, VerifiedAccount::resource(), $id);
    }

    /**
    # Cancel a VerifiedAccount entity

    Cancel a VerifiedAccount entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: VerifiedAccount unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - canceled VerifiedAccount object
     */
    public static function cancel($id, $user = null)
    {
        return Rest::deleteId($user, VerifiedAccount::resource(), $id);
    }

    /**
    # Retrieve VerifiedAccounts

    Receive a generator of VerifiedAccount objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null]: date filter for objects created or updated only after specified date. ex: "2020-03-10"
        - before [DateTime or string, default null]: date filter for objects created or updated only before specified date. ex: "2020-03-10"
        - status [string, default null]: filter for status of retrieved objects. ex: "creating", "created", "processing", "active", "failed" or "canceled"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - generator of VerifiedAccount objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, VerifiedAccount::resource(), $options);
    }

    /**
    # Retrieve paged VerifiedAccounts

    Receive a list of up to 100 VerifiedAccount objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null]: date filter for objects created or updated only after specified date. ex: "2020-03-10"
        - before [DateTime or string, default null]: date filter for objects created or updated only before specified date. ex: "2020-03-10"
        - status [string, default null]: filter for status of retrieved objects. ex: "creating", "created", "processing", "active", "failed" or "canceled"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - list of VerifiedAccount objects with updated attributes and cursor to retrieve the next page of VerifiedAccount objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, VerifiedAccount::resource(), $options);
    }

    public static function resource()
    {
        $verifiedAccount = function ($array) {
            return new VerifiedAccount($array);
        };
        return [
            "name" => "VerifiedAccount",
            "maker" => $verifiedAccount,
        ];
    }
    
}