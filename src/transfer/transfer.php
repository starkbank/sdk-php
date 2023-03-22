<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Transfer\Rule;
use StarkBank\Transfer\Metadata;


class Transfer extends Resource
{

    public $amount;
    public $name;
    public $taxId;
    public $bankCode;
    public $branchCode;
    public $accountNumber;
    public $accountType;
    public $externalId;
    public $scheduled;
    public $description;
    public $tags;
    public $rules;
    public $fee;
    public $status;
    public $transactionIds;
    public $metadata;
    public $created;
    public $updated;

    /**
    # Transfer object

    When you initialize a Transfer, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the array of created objects.

    ## Parameters (required):
        - amount [integer]: amount in cents to be transferred. ex: 1234 (= R$ 12.34)
        - name [string]: receiver full name. ex: "Anthony Edward Stark"
        - taxId [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - bankCode [string]: code of the receiver bank institution in Brazil. If an ISPB (8 digits) is informed, a Pix transfer will be created, else a TED will be issued. ex: "20018183" or "341"
        - branchCode [string]: receiver bank account branch. Use '-' in case there is a verifier digit. ex: "1357-9"
        - accountNumber [string]: receiver bank account number. Use '-' before the verifier digit. ex: "876543-2"

    ## Parameters (optional):
        - accountType [string, default "checking"]: receiver bank account type. This parameter only has effect on Pix Transfers. ex: "checking", "savings", "salary" or "payment"
        - externalId [string, default null]: url safe string that must be unique among all your transfers. Duplicated externalIds will cause failures. By default, this parameter will block any transfer that repeats amount and receiver information on the same date. ex: "my-internal-id-123456"
        - scheduled [DateTime or date, default now]: date or datetime when the transfer will be processed. May be pushed to next business day if necessary. ex: "2020-11-30"
        - description [string]: optional description to override default description to be shown in the bank statement. ex: "Payment for service #1234"
        - tags [array of strings]: array of strings for reference when searching for transfers. ex: ["employees", "monthly"]
        - rules [list of Transfer\Rules, default []]: list of Transfer\Rule objects for modifying transfer behavior. ex: [Transfer\Rule(key=>"resendingLimit", value=>5)]

    ## Attributes (return-only):
        - id [string]: unique id returned when Transfer is created. ex: "5656565656565656"
        - fee [integer]: fee charged when transfer is created. ex: 200 (= R$ 2.00)
        - status [string]: current transfer status. ex: "success" or "failed"
        - transactionIds [array of strings]: ledger transaction ids linked to this transfer (if there are two, second is the chargeback). ex: ["19827356981273"]
        - metadata [dictionary object]: dictionary object used to store additional information about the Transfer object.
        - created [DateTime]: creation datetime for the transfer.
        - updated [DateTime]: latest update datetime for the transfer.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->bankCode = Checks::checkParam($params, "bankCode");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->accountNumber = Checks::checkParam($params, "accountNumber");
        $this->accountType = Checks::checkParam($params, "accountType");
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->description = Checks::checkParam($params, "description");
        $this->tags = Checks::checkParam($params, "tags");
        $this->rules = Rule::parseRules(Checks::checkParam($params, "rules"));
        $this->fee = Checks::checkParam($params, "fee");
        $this->status = Checks::checkParam($params, "status");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->metadata = Checks::checkParam($params, "metadata");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create Transfers

    Send an array of Transfer objects for creation in the Stark Bank API

    ## Parameters (required):
        - transfers [array of Transfer objects]: array of Transfer objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - array of Transfer objects with updated attributes
     */
    public static function create($transfers, $user = null)
    {
        return Rest::post($user, Transfer::resource(), $transfers);
    }

    /**
    # Retrieve a specific Transfer

    Receive a single Transfer object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Transfer object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Transfer::resource(), $id);
    }

    /**
    # Delete a Transfer entity

    Delete a Transfer entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Transfer unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - deleted Transfer object
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, Transfer::resource(), $id);
    }

    /**
    # Retrieve a specific Transfer pdf file

    Receive a single Transfer pdf receipt file generated in the Stark Bank API by passing its id.
    Only valid for transfers with "processing" and "success" status.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Transfer pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getContent($user, Transfer::resource(), $id, "pdf");
    }

    /**
    # Retrieve Transfers

    Receive a enumerator of Transfer objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null]: date filter for objects created or updated only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null]: date filter for objects created or updated only before specified date. ex: "2020-04-03"
        - transactionIds [array of strings, default null]: array of transaction IDs linked to the desired transfers. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - taxId [string, default null]: filter for transfers sent to the specified tax ID. ex: "012.345.678-90"
        - sort [string, default "-created"]: sort order considered in response. Valid options are 'created', '-created', 'updated' or '-updated'.
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of Transfer objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Transfer::resource(), $options);
    }

    /**
    # Retrieve paged Transfers

    Receive a list of up to 100 Transfer objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - transactionIds [list of strings, default null]: list of transaction IDs linked to the desired transfers. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "success" or "failed"
        - taxId [string, default null]: filter for transfers sent to the specified tax ID. ex: "012.345.678-90"
        - sort [string, default "-created"]: sort order considered in response. Valid options are "created", "-created", "updated" or "-updated".
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of Transfer objects with updated attributes
        - cursor to retrieve the next page of Transfer objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Transfer::resource(), $options);
    }

    private static function resource()
    {
        $transfer = function ($array) {
            return new Transfer($array);
        };
        return [
            "name" => "Transfer",
            "maker" => $transfer,
        ];
    }
}
