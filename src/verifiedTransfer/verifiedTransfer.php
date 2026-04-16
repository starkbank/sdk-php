<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Transfer\Rule;


class VerifiedTransfer extends Resource
{
    public $amount;
    public $accountId;
    public $accountType;
    public $externalId;
    public $scheduled;
    public $description;
    public $displayDescription;
    public $tags;
    public $rules;
    public $id;
    public $fee;
    public $status;
    public $transactionIds;
    public $metadata;
    public $created;
    public $updated;

    /**
    # VerifiedTransfer object

    When you initialize a VerifiedTransfer, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (required):
        - amount [integer]: amount in cents to be transferred. ex: 1234 (= R$ 12.34)
        - accountId [string]: receiver's account id in the Stark Bank API. ex: "5656565656565656"

    ## Parameters (optional):
        - accountType [string, default "checking"]: receiver bank account type. ex: "checking", "savings", "salary" or "payment"
        - externalId [string, default null]: url safe string that must be unique among all your transfers. Duplicated externalIds will cause failures. ex: "my-internal-id-123456"
        - scheduled [DateTime or date, default now]: date or datetime when the transfer will be processed. ex: "2020-11-30"
        - description [string, default null]: optional description to override default description to be shown in the bank statement. ex: "Payment for service #1234"
        - displayDescription [string, default null]: description to be shown in the receiver bank interface. ex: "Payment for service #1234"
        - tags [array of strings, default []]: array of strings for reference when searching for transfers. ex: ["employees", "monthly"]
        - rules [list of Transfer\Rules, default []]: list of Transfer\Rule objects for modifying transfer behavior. ex: [Transfer\Rule(key=>"resendingLimit", value=>5)]

    ## Attributes (return-only):
        - id [string]: unique id returned when VerifiedTransfer is created. ex: "5656565656565656"
        - fee [integer]: fee charged when transfer is created. ex: 200 (= R$ 2.00)
        - status [string]: current transfer status. ex: "success" or "failed"
        - transactionIds [array of strings]: ledger transaction ids linked to this transfer (if there are two, second is the chargeback). ex: ["19827356981273"]
        - metadata [dictionary object]: dictionary object used to store additional information about the VerifiedTransfer object.
        - created [DateTime]: creation datetime for the transfer.
        - updated [DateTime]: latest update datetime for the transfer.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->accountId = Checks::checkParam($params, "accountId");
        $this->accountType = Checks::checkParam($params, "accountType");
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->description = Checks::checkParam($params, "description");
        $this->displayDescription = Checks::checkParam($params, "displayDescription");
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
    # Create VerifiedTransfers

    Send a list of VerifiedTransfer objects for creation in the Stark Bank API

    ## Parameters (required):
        - transfers [list of VerifiedTransfer objects]: list of VerifiedTransfer objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - list of VerifiedTransfer objects with updated attributes
     */
    public static function create($transfers, $user = null)
    {
        return Rest::post($user, VerifiedTransfer::resource(), $transfers);
    }

    private static function resource()
    {
        $transfer = function ($array) {
            return new VerifiedTransfer($array);
        };
        return [
            "name" => "VerifiedTransfer",
            "maker" => $transfer,
        ];
    }
}
