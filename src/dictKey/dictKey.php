<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;

use function PHPSTORM_META\type;

class DictKey extends Resource
{
  /**
    # DictKey object
     
    DictKey represents a PIX key registered in Bacen's DICT system.
    
    ## Parameters (required):
        - id [string]: DictKey object unique id and PIX key itself. ex: "tony@starkbank.com", "722.461.430-04", "20.018.183/0001-80", "+5511988887777", "b6295ee1-f054-47d1-9e90-ee57b74f60d9"
    
    ## Attributes (return-only):
        - type [string, default null]: PIX key type. ex: "email", "cpf", "cnpj", "phone" or "evp"
        - accountCreated [DateTime, default null]: creation datetime of the bank account associated with the PIX key.
        - accountType [string, default null]: bank account type associated with the PIX key. ex: "checking", "saving" e "salary"
        - name [string, default null]: account owner full name. ex: "Tony Stark"
        - taxId [string, default null]: tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - ownerType [string, default null]: PIX key owner type. ex "naturalPerson" or "legalPerson"
        - ispb [string, default null]: ISPB code used for transactions. ex: "20018183"
        - branchCode [string, default null]: bank account branch code associated with the PIX key. ex: "9585"
        - accountNumber [string, default null]: bank account number associated with the PIX key. ex: "9828282578010513"
        - status [string, default null]: current PIX key status. ex: "created", "registered", "canceled" or "failed"
        - owned [DateTime, default null]: datetime since when the current owner hold this PIX key.
        - created [DateTime, default null]: creation datetime for the PIX key.
   */
    function __construct(array $params)
    {
        parent:: __construct($params);
        
        $this->type = Checks::checkParam($params, "type");
        $this->accountCreated = Checks::checkDateTime(Checks::checkParam($params, "accountCreated"));
        $this->accountType = Checks::checkParam($params, "accountType");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->ownerType = Checks::checkParam($params, "ownerType");
        $this->ispb = Checks::checkParam($params, "ispb");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->accountNumber = Checks::checkParam($params, "accountNumber");
        $this->status = Checks::checkParam($params, "status");
        $this->owned = Checks::checkDateTime(Checks::checkParam($params, "owned"));
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
    }
  
    /**
    # Retrieve a specific DictKey

    Receive a single DictKey object by passing its id

    ## Parameters (required):
        - id [string]: DictKey object unique id and PIX key itself. ex: 'tony@starkbank.com', '722.461.430-04', '20.018.183/0001-80', '+5511988887777', 'b6295ee1-f054-47d1-9e90-ee57b74f60d9'

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - DictKey object with updated attributes
        */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, DictKey::resource(), $id);
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
    