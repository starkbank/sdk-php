<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class CorporateWithdrawal extends Resource
{

    public $amount;
    public $externalId;
    public $tags;
    public $transactionId;
    public $corporateTransactionId;
    public $created;
    public $updated;

    /**
    # CorporateWithdrawal object

    The CorporateWithdrawal objects created in your Workspace return cash from your Corporate balance to your
    Banking balance.

    ## Parameters (required):
        - amount [integer]: CorporateWithdrawal value in cents. Minimum = 0 (any value will be accepted). ex: 1234 (= R$ 12.34)
        - externalId [string] CorporateWithdrawal external ID. ex: "12345"

    ## Parameters (optional):
        - tags [array of strings, default null]: array of strings for tagging. ex: ["tony", "stark"]

    ## Attributes (return-only):
        - id [string]: unique id returned when CorporateWithdrawal is created. ex: "5656565656565656"
        - transactionId [string]: Stark Bank ledger transaction ids linked to this CorporateWithdrawal
        - corporateTransactionId [string]: ledger transaction ids linked to this CorporateWithdrawal. ex: "corporate-withdrawal/5656565656565656"
        - created [DateTime]: creation datetime for the CorporateWithdrawal. 
        - updated [DateTime]: latest update datetime for the CorporateWithdrawal. 
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->tags = Checks::checkParam($params, "tags");
        $this->transactionId = Checks::checkParam($params, "transactionId");
        $this->corporateTransactionId = Checks::checkParam($params, "corporateTransactionId");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create a CorporateWithdrawal

    Send an CorporateWithdrawal object for creation in the Stark Bank API

    ## Parameters (required):
        - withdrawal [CorporateWithdrawal object]: CorporateWithdrawal objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateWithdrawal object with updated attributes
     */
    public static function create($withdrawal, $user = null)
    {
        return Rest::postSingle($user, CorporateWithdrawal::resource(), $withdrawal);
    }

    /**
    # Retrieve a specific CorporateWithdrawal

    Receive a single CorporateWithdrawal object previously created in the Stark Bank API by its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateWithdrawal object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, CorporateWithdrawal::resource(), $id);
    }

    /**
    # Retrieve CorporateWithdrawals

    Receive an enumerator of CorporateWithdrawal objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - externalIds [array of strings, default []]: external IDs. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of CorporateWithdrawal objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, CorporateWithdrawal::resource(), $options);
    }

    /**
    # Retrieve CorporateWithdrawal

    Receive a list of CorporateWithdrawal objects previously created in the Stark Bank API and the cursor to the next page.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - externalIds [array of strings, default []]: external IDs. ex: ["5656565656565656", "4545454545454545"]
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of CorporateWithdrawal objects with updated attributes
        - cursor to retrieve the next page of CorporateWithdrawal objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, CorporateWithdrawal::resource(), $options);
    }

    private static function resource()
    {
        $withdrawal = function ($array) {
            return new CorporateWithdrawal($array);
        };
        return [
            "name" => "CorporateWithdrawal",
            "maker" => $withdrawal,
        ];
    }
}
