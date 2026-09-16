<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;;


class MerchantInstallment extends Resource
{
    public $amount;
    public $due;
    public $fee;
    public $fundingType;
    public $network;
    public $purchaseId;
    public $status;
    public $tags;
    public $transactionIds;
    public $created;
    public $updated;

    /**
    # MerchantInstallment object

    A MerchantInstallment is created for every installment in a MerchantPurchase and tracks its own due date and settlement lifecycle.

    ## Attributes (return-only):
        - id [string]: unique id for the installment.
        - amount [integer]: installment amount in cents.
        - due [DateTime]: expected settlement date.
        - fee [integer]: fee charged in cents.
        - fundingType [string]: funding type. Options: "credit", "debit".
        - network [string]: card network.
        - purchaseId [string]: id of the MerchantPurchase linked to the installment.
        - status [string]: current installment status. Options: "created", "paid", "canceled", "voided".
        - tags [array of strings]: tags associated with the installment.
        - transactionIds [array of strings]: ledger transaction ids linked to the installment.
        - created [DateTime]: creation datetime.
        - updated [DateTime]: latest update datetime.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->due = Checks::checkParam($params, "due");
        $this->fee = Checks::checkParam($params, "fee");
        $this->fundingType = Checks::checkParam($params, "fundingType");
        $this->network = Checks::checkParam($params, "network");
        $this->purchaseId = Checks::checkParam($params, "purchaseId");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific MerchantInstallment

    Receive a single MerchantInstallment object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id.

    ## Return:
        - MerchantInstallment object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, self::resource(), $id);
    }

    /**
    # Retrieve MerchantInstallments

    Receive an enumerator of MerchantInstallment objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null.
        - after, before [DateTime or string, default null]: date filters.
        - status [string, default null]: filter for status of retrieved objects.
        - tags [array of strings, default null]: tags to filter retrieved objects.
        - ids [array of strings, default null]: array of ids to filter retrieved objects.
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of MerchantInstallment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, self::resource(), $options);
    }

    /**
    # Retrieve paged MerchantInstallments

    Receive a list of up to 100 MerchantInstallment objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100.
        - (same filters as query: after, before, status, tags, ids)
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - list of MerchantInstallment objects with updated attributes
        - cursor to retrieve the next page of MerchantInstallment objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, self::resource(), $options);
    }

    private static function resource()
    {
        $merchantInstallment = function ($array) {
            return new MerchantInstallment($array);
        };
        return [
            "name" => "MerchantInstallment",
            "maker" => $merchantInstallment,
        ];
    }
}

