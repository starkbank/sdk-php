<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class DictKey extends Resource
{

    public $type;
    public $name;
    public $taxId;
    public $ownerType;
    public $bankName;
    public $ispb;
    public $branchCode;
    public $accountNumber;
    public $accountType;
    public $status;

    /**
    # DictKey object
    
    DictKey represents a Pix key registered in Bacen's DICT system.
    
    ## Parameters (optional):
        - id [string]: DictKey object unique id and Pix key itself. ex: "tony@starkbank.com", "722.461.430-04", "20.018.183/0001-80", "+5511988887777", "b6295ee1-f054-47d1-9e90-ee57b74f60d9"
    
    ## Attributes (return-only):
        - type [string]: DICT key type. ex: "email", "cpf", "cnpj", "phone" or "evp"
        - name [string]: key owner full name. ex: "Tony Stark"
        - taxId [string]: key owner tax ID (CNPJ or masked CPF). ex: "***.345.678-**" or "20.018.183/0001-80"
        - ownerType [string]: DICT key owner type. ex "naturalPerson" or "legalPerson"
        - bankName [string]: bank name associated with the DICT key. ex: "Stark Bank"
        - ispb [string]: bank ISPB associated with the DICT key. ex: "20018183"
        - branchCode [string]: encrypted bank account branch code associated with the DICT key. ex: "ZW5jcnlwdGVkLWJyYW5jaC1jb2Rl"
        - accountNumber [string]: encrypted bank account number associated with the DICT key. ex: "ZW5jcnlwdGVkLWFjY291bnQtbnVtYmVy"
        - accountType [string]: bank account type associated with the DICT key. ex: "checking", "savings", "salary" or "payment"
        - status [string]: current DICT key status. ex: "created", "registered", "canceled" or "failed"
     */
    function __construct(array $params)
    {
        parent:: __construct($params);
        
        $this->type = Checks::checkParam($params, "type");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->ownerType = Checks::checkParam($params, "ownerType");
        $this->bankName = Checks::checkParam($params, "bankName");
        $this->ispb = Checks::checkParam($params, "ispb");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->accountNumber = Checks::checkParam($params, "accountNumber");
        $this->accountType = Checks::checkParam($params, "accountType");
        $this->status = Checks::checkParam($params, "status");

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific DictKey

    Receive a single DictKey object by passing its id

    ## Parameters (required):
        - id [string]: DictKey object unique id and Pix key itself. ex: 'tony@starkbank.com', '722.461.430-04', '20.018.183/0001-80', '+5511988887777', 'b6295ee1-f054-47d1-9e90-ee57b74f60d9'

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - DictKey object with updated attributes
        */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, DictKey::resource(), $id);
    }

    /**
    # Retrieve DictKeys

    Receive an enumerator of DictKey objects associated with your Stark Bank Workspace

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - type [string, default null]: DictKey type. ex: "cpf", "cnpj", "phone", "email" or "evp"
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "success"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of DictKey objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, DictKey::resource(), $options);
    }

    /**
    # Retrieve paged DictKey

    Receive a list of up to 100 DictKey objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - type [string, default null]: DictKey type. ex: "cpf", "cnpj", "phone", "email" or "evp"
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of DictKey objects with updated attributes
        - cursor to retrieve the next page of DictKey objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, DictKey::resource(), $options);
    }
    
    private static function resource()
    {
        $dictKey = function ($array) {
            return new DictKey($array);
        };
        return [
            "name" => "DictKey",
            "maker" => $dictKey,
        ];
    }
}
