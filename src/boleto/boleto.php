<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class Boleto extends Resource
{

    public $amount;
    public $name;
    public $taxId;
    public $streetLine1;
    public $streetLine2;
    public $district;
    public $city;
    public $stateCode;
    public $zipCode;
    public $due;
    public $fine;
    public $interest;
    public $overdueLimit;
    public $receiverName;
    public $receiverTaxId;
    public $descriptions;
    public $discounts;
    public $tags;
    public $splits;
    public $id;
    public $fee;
    public $line;
    public $barCode;
    public $status;
    public $transactionIds;
    public $workspaceId;
    public $created;
    public $ourNumber;

    /**
    # Boleto object

    When you initialize a Boleto, the entity will not be automatically
    sent to the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the array of created objects.

    ## Parameters (required):
        - amount [integer]: Boleto value in cents. Minimum = 200 (R$2,00). ex: 1234 (= R$ 12.34)
        - name [string]: payer full name. ex: "Anthony Edward Stark"
        - taxId [string]: payer tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - streetLine1 [string]: payer main address. ex: Av. Paulista, 200
        - streetLine2 [string]: payer address complement. ex: Apto. 123
        - district [string]: payer address district / neighbourhood. ex: Bela Vista
        - city [string]: payer address city. ex: Rio de Janeiro
        - stateCode [string]: payer address state. ex: GO
        - zipCode [string]: payer address zip code. ex: 01311-200
    
    ## Parameters (optional):
        - due [DateTime or string, default today + 2 days]: Boleto due date in ISO format. ex: "2020-04-30"
        - fine [float, default 2.0]: Boleto fine for overdue payment in %. ex: 2.5
        - interest [float, default 1.0]: Boleto monthly interest for overdue payment in %. ex: 5.2
        - overdueLimit [integer, default 59]: limit in days for payment after due date. ex: 7 (max: 59)
        - receiverName [string]: receiver (Sacador Avalista) full name. ex: "Anthony Edward Stark"
        - receiverTaxId [string]: receiver (Sacador Avalista) tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - descriptions [array of dictionaries, default null]: array of dictionaries with "text":string and (optional) "amount":integer pairs
        - discounts [array of dictionaries, default null]: array of dictionaries with "percentage":float and "date":DateTime or string pairs
        - tags [array of strings]: array of strings for tagging
    
    ## Attributes (return-only):
        - id [string]: unique id returned when Boleto is created. ex: "5656565656565656"
        - fee [integer]: fee charged when Boleto is paid. ex: 200 (= R$ 2.00)
        - line [string]: generated Boleto line for payment. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string]: generated Boleto bar-code for payment. ex: "34195819600000000621090063571277307144464000"
        - status [string]: current Boleto status. ex: "registered" or "paid"
        - transactionIds [list of strings]: ledger transaction ids linked to this boleto. ex: ["19827356981273"]
        - workspaceId [string]: ID of the Workspace that generated this Boleto. ex: "4545454545454545"
        - created [DateTime]: creation datetime for the Boleto.
        - ourNumber [string]: Reference number registered at the settlement bank. ex:"10131474"
     */
    function __construct(array $params)
    {
        parent::__construct($params);
        
        $this->amount = Checks::checkParam($params, "amount");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->streetLine1 = Checks::checkParam($params, "streetLine1");
        $this->streetLine2 = Checks::checkParam($params, "streetLine2");
        $this->district = Checks::checkParam($params, "district");
        $this->city = Checks::checkParam($params, "city");
        $this->stateCode = Checks::checkParam($params, "stateCode");
        $this->zipCode = Checks::checkParam($params, "zipCode");
        $this->due = Checks::checkDatetime(Checks::checkParam($params, "due"));
        $this->fine = Checks::checkParam($params, "fine");
        $this->interest = Checks::checkParam($params, "interest");
        $this->overdueLimit = Checks::checkParam($params, "overdueLimit");
        $this->receiverName = Checks::checkParam($params, "receiverName");
        $this->receiverTaxId = Checks::checkParam($params, "receiverTaxId");
        $this->tags = Checks::checkParam($params, "tags");
        $this->splits = Checks::checkParam($params, "splits");
        $this->descriptions = Checks::checkParam($params, "descriptions");
        $this->fee = Checks::checkParam($params, "fee");
        $this->line = Checks::checkParam($params, "line");
        $this->barCode = Checks::checkParam($params, "barCode");
        $this->status = Checks::checkParam($params, "status");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->workspaceId = Checks::checkParam($params, "workspaceId");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->ourNumber = Checks::checkParam($params, "ourNumber");

        $discounts = Checks::checkParam($params, "discounts");
        if (!is_null($discounts)) {
            $checkedDiscounts = [];
            foreach ($discounts as $discount) {
                $discount["date"] = Checks::checkDateTime(Checks::checkParam($discount, "date"));
                array_push($checkedDiscounts, $discount);
            }
            $discounts = $checkedDiscounts;
        }
        $this->discounts = $discounts;

        Checks::checkParams($params);
    }

    function __toArray()
    {
        $array = get_object_vars($this);
        $array["due"] = new StarkDate($array["due"]);
        if (!is_null($array["discounts"])) {
            $checkedDiscounts = [];
            foreach ($array["discounts"] as $discount) {
                $discount["date"] = new StarkDate(Checks::checkParam($discount, "date"));
                array_push($checkedDiscounts, $discount);
            }
            $array["discounts"] = $checkedDiscounts;
        }
        return $array;
    }

    /**
    # Create Boletos

    Send an array of Boleto objects for creation in the Stark Bank API

    ## Parameters (required):
        - boletos [array of Boleto objects]: array of Boleto objects to be created in the API
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - array of Boleto objects with updated attributes
     */
    public static function create($boletos, $user = null)
    {
        return Rest::post($user, Boleto::resource(), $boletos);
    }

    /**
    # Retrieve a specific Boleto

    Receive a single Boleto object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Boleto object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Boleto::resource(), $id);
    }

    /**
    # Retrieve a specific Boleto pdf file

    Receive a single Boleto pdf file generated in the Stark Bank API by passing its id.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - layout [string]: Layout specification. Available options are "default" and "booklet"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Boleto pdf file
     */
    public static function pdf($id, $options = [], $user = null)
    {
        $options["layout"] = Checks::checkParam($options, "layout");
        return Rest::getContent($user, Boleto::resource(), $id, "pdf", $options);
    }

    /**
    # Retrieve Boletos

    Receive an enumerator of Boleto objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of Boleto objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Boleto::resource(), $options);
    }

    /**
    # Retrieve paged Boletos

    Receive a list of up to 100 Boleto objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of Boleto objects with updated attributes
        - cursor to retrieve the next page of Boleto objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Boleto::resource(), $options);
    }

    /**
    # Delete a Boleto entity

    Delete a Boleto entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Boleto unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - deleted Boleto object
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, Boleto::resource(), $id);
    }

    private static function resource()
    {
        $boleto = function ($array) {
            return new Boleto($array);
        };
        return [
            "name" => "Boleto",
            "maker" => $boleto,
        ];
    }
}
