<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Boleto extends Resource
{
    /**
    Boleto object

    When you initialize a Boleto, the entity will not be automatically
    sent to the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    Parameters (required):
        amount [integer]: Boleto value in cents. Minimum = 200 (R$2,00). ex: 1234 (= R$ 12.34)
        name [string]: payer full name. ex: "Anthony Edward Stark"
        tax_id [string]: payer tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        street_line_1 [string]: payer main address. ex: Av. Paulista, 200
        street_line_2 [string]: payer address complement. ex: Apto. 123
        district [string]: payer address district / neighbourhood. ex: Bela Vista
        city [string]: payer address city. ex: Rio de Janeiro
        state_code [string]: payer address state. ex: GO
        zip_code [string]: payer address zip code. ex: 01311-200
        due [datetime.date, default today + 2 days]: Boleto due date in ISO format. ex: 2020-04-30
    Parameters (optional):
        fine [float, default 0.0]: Boleto fine for overdue payment in %. ex: 2.5
        interest [float, default 0.0]: Boleto monthly interest for overdue payment in %. ex: 5.2
        overdue_limit [integer, default 59]: limit in days for automatic Boleto cancellation after due date. ex: 7 (max: 59)
        descriptions [list of dictionaries, default None]: list of dictionaries with "text":string and (optional) "amount":int pairs
        tags [list of strings]: list of strings for tagging
    Attributes (return-only):
        id [string, default None]: unique id returned when Boleto is created. ex: "5656565656565656"
        fee [integer, default None]: fee charged when Boleto is paid. ex: 200 (= R$ 2.00)
        line [string, default None]: generated Boleto line for payment. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        bar_code [string, default None]: generated Boleto bar-code for payment. ex: "34195819600000000621090063571277307144464000"
        status [string, default None]: current Boleto status. ex: "registered" or "paid"
        created [datetime.datetime, default None]: creation datetime for the Boleto. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->amount = $params["amount"];
        unset($params["amount"]);
        $this->name = $params["name"];
        unset($params["name"]);
        $this->taxId = $params["taxId"];
        unset($params["taxId"]);
        $this->streetLine1 = $params["streetLine1"];
        unset($params["streetLine1"]);
        $this->streetLine2 = $params["streetLine2"];
        unset($params["streetLine2"]);
        $this->district = $params["district"];
        unset($params["district"]);
        $this->city = $params["city"];
        unset($params["city"]);
        $this->stateCode = $params["stateCode"];
        unset($params["stateCode"]);
        $this->zipCode = $params["zipCode"];
        unset($params["zipCode"]);
        $this->due = Checks::checkDateTime($params["due"]);
        unset($params["due"]);
        $this->fine = $params["fine"];
        unset($params["fine"]);
        $this->interest = $params["interest"];
        unset($params["interest"]);
        $this->overdueLimit = $params["overdueLimit"];
        unset($params["overdueLimit"]);
        $this->tags = $params["tags"];
        unset($params["tags"]);
        $this->descriptions = $params["descriptions"];
        unset($params["descriptions"]);
        $this->fee = $params["fee"];
        unset($params["fee"]);
        $this->line = $params["line"];
        unset($params["line"]);
        $this->barCode = $params["barCode"];
        unset($params["barCode"]);
        $this->status = $params["status"];
        unset($params["status"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);

        Checks::checkParams($params);
    }

    /**
    Create Boletos

    Send a list of Boleto objects for creation in the Stark Bank API

    Parameters (required):
        boletos [list of Boleto objects]: list of Boleto objects to be created in the API
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        list of Boleto objects with updated attributes
     */
    public function create($user, $boletos)
    {
        return Rest::post($user, Boleto::resource(), $boletos);
    }

    /**
    Retrieve a specific Boleto

    Receive a single Boleto object previously created in the Stark Bank API by passing its id

    Parameters (required):
        id [string]: object unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        Boleto object with updated attributes
     */
    public function get($user, $id)
    {
        return Rest::getId($user, Boleto::resource(), $id);
    }

    /**
    Retrieve a specific Boleto pdf file

    Receive a single Boleto pdf file generated in the Stark Bank API by passing its id.

    Parameters (required):
        id [string]: object unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        Boleto pdf file
     */
    public function pdf($user, $id)
    {
        return Rest::getPdf($user, Boleto::resource(), $id);
    }

    /**
    Retrieve Boletos

    Receive a generator of Boleto objects previously created in the Stark Bank API

    Parameters (optional):
        limit [integer, default None]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        status [string, default None]: filter for status of retrieved objects. ex: "paid" or "registered"
        tags [list of strings, default None]: tags to filter retrieved objects. ex: ["tony", "stark"]
        ids [list of strings, default None]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        after [datetime.date, default None] date filter for objects created only after specified date. ex: datetime.date(2020, 3, 10)
        before [datetime.date, default None] date filter for objects only before specified date. ex: datetime.date(2020, 3, 10)
        user [Project object, default None]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        generator of Boleto objects with updated attributes
     */
    public function query($user, $options = [])
    {
        $options["after"] = Checks::checkDateTime($options["after"]);
        $options["before"] = Checks::checkDateTime($options["before"]);
        return Rest::getList($user, Boleto::resource(), $options);
    }

    /**
    Delete a Boleto entity

    Delete a Boleto entity previously created in the Stark Bank API

    Parameters (required):
        id [string]: Boleto unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        deleted Boleto with updated attributes
     */
    public function delete($user, $id)
    {
        return Rest::deleteId($user, Boleto::resource(), $id);
    }

    private function resource()
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
