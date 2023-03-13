<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class DarfPayment extends Resource
{
    
    public $revenueCode;
    public $taxId;
    public $competence;
    public $referenceNumber;
    public $fineAmount;
    public $interestAmount;
    public $due;
    public $description;
    public $tags;
    public $scheduled;
    public $status;
    public $amount;
    public $nominalAmount;
    public $fee;
    public $transactionIds;
    public $updated;
    public $created;

    /**
    # DarfPayment object

    When you initialize a DarfPayment, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (required):
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"
        - revenueCode [string]: 4-digit tax code assigned by Federal Revenue. ex: "5948"
        - taxId [string]: tax id (formatted or unformatted) of the payer. ex: "12.345.678/0001-95"
        - competence [string]: competence month of the service. ex: "2020-03-10"
        - nominalAmount [integer]: amount due in cents without fee or interest. ex: 23456 (= R$ 234.56)
        - fineAmount [integer]: fixed amount due in cents for fines. ex: 234 (= R$ 2.34)
        - interestAmount [integer]: amount due in cents for interest. ex: 456 (= R$ 4.56)
        - due [string]: due date for payment. ex: "2020-03-10"

    ## Parameters (optional):
        - referenceNumber [string]: number assigned to the region of the tax. ex: "08.1.17.00-4"
        - scheduled [string, default today]: payment scheduled date. ex: "2020-03-10"
        - tags [list of strings]: list of strings for tagging

    ## Attributes (return-only):
        - id [string]: unique id returned when payment is created. ex: "5656565656565656"
        - status [string]: current payment status. ex: "success" or "failed"
        - amount [integer]: Total amount due calculated from other amounts. ex: 24146 (= R$ 241.46)
        - fee [integer]: fee charged when the DarfPayment is processed. ex: 0 (= R$ 0.00)
        - transactionIds [list of strings]: ledger transaction ids linked to this DarfPayment. ex: ["19827356981273"]
        - updated [string]: creation datetime for the payment. ex: "2020-03-10 10:30:00.000"
        - created [string]: creation datetime for the payment. ex: "2020-03-10 10:30:00.000"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->revenueCode = Checks::checkParam($params, "revenueCode");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->competence = Checks::checkDateTime(Checks::checkParam($params, "competence"));
        $this->referenceNumber = Checks::checkParam($params, "referenceNumber");
        $this->fineAmount = Checks::checkParam($params, "fineAmount");
        $this->interestAmount = Checks::checkParam($params, "interestAmount");
        $this->due = Checks::checkDateTime(Checks::checkParam($params, "due"));
        $this->description = Checks::checkParam($params, "description");
        $this->tags = Checks::checkParam($params, "tags");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->status = Checks::checkParam($params, "status");
        $this->amount = Checks::checkParam($params, "amount");
        $this->nominalAmount = Checks::checkParam($params, "nominalAmount");
        $this->fee = Checks::checkParam($params, "fee");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Create DarfPayments

    Send a list of DarfPayment objects for creation in the Stark Bank API

    ## Parameters (required):
        - payments [list of DarfPayment objects]: list of DarfPayment objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - list of DarfPayment objects with updated attributes
     */
    public static function create($payments, $user = null)
    {
        return Rest::post($user, DarfPayment::resource(), $payments);
    }

    /**
    # Retrieve a specific DarfPayment

    Receive a single DarfPayment object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - DarfPayment object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, DarfPayment::resource(), $id);
    }

    /**
    # Retrieve a specific DarfPayment pdf file

    Receive a single DarfPayment pdf file generated in the Stark Bank API by passing its id.
    Only valid for darf payments with "success" or "processing" status.
    
    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - DarfPayment pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getContent($user, DarfPayment::resource(), $id, "pdf");
    }

    /**
    # Retrieve DarfPayments

    Receive a generator of DarfPayment objects previously created in the Stark Bank API
    
    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
        - before [string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "success"
        - user [Project object, default null]: Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - generator of DarfPayment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, DarfPayment::resource(), $options);
    }

    /**
    # Retrieve paged DarfPayments

    Receive a list of up to 100 DarfPayment objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.
    
    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
        - before [string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "success"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of DarfPayment objects with updated attributes
        - cursor to retrieve the next page of DarfPayment objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, DarfPayment::resource(), $options);
    }

    /**
    # Delete a DarfPayment entity

    Delete a DarfPayment entity previously created in the Stark Bank API
    
    ## Parameters (required):
        - id [string]: DarfPayment unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - deleted DarfPayment with updated attributes
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, DarfPayment::resource(), $id);
    }

    private static function resource()
    {
        $darfPayment = function ($array) {
            return new DarfPayment($array);
        };
        return [
            "name" => "DarfPayment",
            "maker" => $darfPayment,
        ];
    }
}
