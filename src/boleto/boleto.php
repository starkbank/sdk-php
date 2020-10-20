<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Boleto extends Resource
{
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
        - fine [float, default 0.0]: Boleto fine for overdue payment in %. ex: 2.5
        - interest [float, default 0.0]: Boleto monthly interest for overdue payment in %. ex: 5.2
        - overdueLimit [integer, default 59]: limit in days for payment after due date. ex: 7 (max: 59)
        - receiverName [string]: receiver (Sacador Avalista) full name. ex: "Anthony Edward Stark"
        - receiverTaxId [string]: receiver (Sacador Avalista) tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - descriptions [array of dictionaries, default null]: array of dictionaries with "text":string and (optional) "amount":int pairs
        - discounts [array of dictionaries, default null]: array of dictionaries with "percentage":float and "date":DateTime or string pairs
        - tags [array of strings]: array of strings for tagging
    
    ## Attributes (return-only):
        - id [string, default null]: unique id returned when Boleto is created. ex: "5656565656565656"
        - fee [integer, default null]: fee charged when Boleto is paid. ex: 200 (= R$ 2.00)
        - line [string, default null]: generated Boleto line for payment. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string, default null]: generated Boleto bar-code for payment. ex: "34195819600000000621090063571277307144464000"
        - status [string, default null]: current Boleto status. ex: "registered" or "paid"
        - created [DateTime, default null]: creation datetime for the Boleto.
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
        $this->due = Checks::checkDateTime(Checks::checkParam($params, "due"));
        $this->fine = Checks::checkParam($params, "fine");
        $this->interest = Checks::checkParam($params, "interest");
        $this->overdueLimit = Checks::checkParam($params, "overdueLimit");
        $this->receiverName = Checks::checkParam($params, "receiverName");
        $this->receiverTaxId = Checks::checkParam($params, "receiverTaxId");
        $this->tags = Checks::checkParam($params, "tags");
        $this->descriptions = Checks::checkParam($params, "descriptions");
        $this->discounts = Checks::checkParam($params, "discounts");
        $this->fee = Checks::checkParam($params, "fee");
        $this->line = Checks::checkParam($params, "line");
        $this->barCode = Checks::checkParam($params, "barCode");
        $this->status = Checks::checkParam($params, "status");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Create Boletos

    Send an array of Boleto objects for creation in the Stark Bank API

    ## Parameters (required):
        - boletos [array of Boleto objects]: array of Boleto objects to be created in the API
    
    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
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
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

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
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - Boleto pdf file
     */
    public static function pdf($id, $options = [], $user = null)
    {
        $options["layout"] = Checks::checkParam($options, "layout");
        return Rest::getPdf($user, Boleto::resource(), $id, $options);
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
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of Boleto objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, Boleto::resource(), $options);
    }

    /**
    # Delete a Boleto entity

    Delete a Boleto entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Boleto unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
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
