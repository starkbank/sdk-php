<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\StarkBankDateTime;
use StarkBank\Utils\StarkBankDate;


class TaxPayment extends Resource
{
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->line = Checks::checkParam($params, "line");
        $this->barCode = Checks::checkParam($params, "barCode");
        $this->description = Checks::checkParam($params, "description");
        $this->tags = Checks::checkParam($params, "tags");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->status = Checks::checkParam($params, "status");
        $this->amount = Checks::checkParam($params, "amount");
        $this->fee = Checks::checkParam($params, "fee");
        $this->type = Checks::checkParam($params, "type");
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }


    /**
    # Create TaxPayments

    Send a list of TaxPayment objects for creation in the Stark Bank API
    
    ## Parameters (required):
    - payments [list of TaxPayment objects]: list of TaxPayment objects to be created in the API
    
    ## Parameters (optional):
    - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - list of TaxPayment objects with updated attributes     
     */
    public static function create($payments, $user = null)
    {
        return Rest::post($user, TaxPayment::resource(), $payments);
    }

    /**
    # Retrieve a specific TaxPayment

    Receive a single TaxPayment object previously created by the Stark Bank API by passing its id
    
    ## Parameters (required):
    - id [string]: object unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
    - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - TaxPayment object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, TaxPayment::resource(), $id);
    }

    /**
    # Retrieve a specific TaxPayment pdf file

    Receive a single TaxPayment pdf file generated in the Stark Bank API by passing its id.
    Only valid for tax payments with "success" or "processing" status.

    ## Parameters (required):
    - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
    - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - TaxPayment pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getContent($user, TaxPayment::resource(), $id, "pdf");
    }

    /**
    # Retrieve TaxPayments

    Receive a generator of TaxPayment objects previously created in the Stark Bank API
    
    ## Parameters (optional):
    - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
    - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
    - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
    - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
    - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
    - status [string, default null]: filter for status of retrieved objects. ex: "success"
    - user [Project object, default null]: Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - generator of TaxPayment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, TaxPayment::resource(), $options);
    }

    /**
    # Retrieve paged TaxPayments
    Receive a list of up to 100 TaxPayment objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.
    ## Parameters (optional):
    - cursor [string, default null]: cursor returned on the previous page function call
    - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
    - after [DateTime or string or string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
    - before [DateTime or string or string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
    - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
    - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
    - status [string, default null]: filter for status of retrieved objects. ex: "success"
    - user [Organization/Project object, default null, default null]: Organization or Project object. Not necessary if starkbank.user was set before function call
    ## Return:
    - list of TaxPayment objects with updated attributes
    - cursor to retrieve the next page of TaxPayment objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, TaxPayment::resource(), $options);
    }

    /**
    # Delete a TaxPayment entity

    Delete a TaxPayment entity previously created in the Stark Bank API

    ## Parameters (required):
    - id [string]: TaxPayment unique id. ex: "5656565656565656"

    ## Parameters (optional):
    - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - deleted TaxPayment with updated attributes
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, TaxPayment::resource(), $id);
    }

    private static function resource()
    {
        $taxPayment = function ($array) {
            return new TaxPayment($array);
        };
        return [
            "name" => "TaxPayment",
            "maker" => $taxPayment,
        ];
    }
}
