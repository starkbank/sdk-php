<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class UtilityPayment extends Resource
{
    /**
    # UtilityPayment object

    When you initialize a UtilityPayment, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (conditionally required):
        - line [string, default null]: Number sequence that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string, default null]: Bar code number that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34195819600000000621090063571277307144464000"

    ## Parameters (required):
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"

    ## Parameters (optional):
        - scheduled [DateTime, default today]: payment scheduled date.
        - tags [list of strings]: list of strings for tagging

    ## Attributes (return-only):
        - id [string, default null]: unique id returned when payment is created. ex: "5656565656565656"
        - status [string, default null]: current payment status. ex: "registered" or "paid"
        - amount [int, default null]: amount automatically calculated from line or bar_code. ex: 23456 (= R$ 234.56)
        - fee [integer, default null]: fee charged when utility payment is created. ex: 200 (= R$ 2.00)
        - created [DateTime, default null]: creation datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->line = $params["line"];
        unset($params["line"]);
        $this->barCode = $params["barCode"];
        unset($params["barCode"]);
        $this->description = $params["description"];
        unset($params["description"]);
        $this->tags = $params["tags"];
        unset($params["tags"]);
        $this->scheduled = Checks::checkDateTime($params["scheduled"]);
        unset($params["scheduled"]);
        $this->status = $params["status"];
        unset($params["status"]);
        $this->amount = $params["amount"];
        unset($params["amount"]);
        $this->fee = $params["fee"];
        unset($params["fee"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);

        Checks::checkParams($params);
    }

    /**
    # Create UtilityPayments

    Send a list of UtilityPayment objects for creation in the Stark Bank API

    ## Parameters (required):
        - payments [list of UtilityPayment objects]: list of UtilityPayment objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - list of UtilityPayment objects with updated attributes
     */
    public function create($payments, $user = null)
    {
        return Rest::post($user, UtilityPayment::resource(), $payments);
    }

    /**
    # Retrieve a specific UtilityPayment

    Receive a single UtilityPayment object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - UtilityPayment object with updated attributes
     */
    public function get($id, $user = null)
    {
        return Rest::getId($user, UtilityPayment::resource(), $id);
    }

    /**
    # Retrieve a specific UtilityPayment pdf file

    Receive a single UtilityPayment pdf file generated in the Stark Bank API by passing its id.
    Only valid for utility payments with "success" status.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - UtilityPayment pdf file
     */
    public function pdf($id, $user = null)
    {
        return Rest::getPdf($user, UtilityPayment::resource(), $id);
    }

    /**
    # Retrieve UtilityPayments

    Receive a enumerator of UtilityPayment objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - status [string, default null]: filter for status of retrieved objects. ex: "paid"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - after [DateTime, default null] date filter for objects created only after specified date.
        - before [DateTime, default null] date filter for objects only before specified date.
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of UtilityPayment objects with updated attributes
     */
    public function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime($options["after"]);
        $options["before"] = Checks::checkDateTime($options["before"]);
        return Rest::getList($user, UtilityPayment::resource(), $options);
    }

    /**
    # Delete a UtilityPayment entity

    Delete a UtilityPayment entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: UtilityPayment unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - deleted UtilityPayment with updated attributes
     */
    public function delete($id, $user = null)
    {
        return Rest::deleteId($user, UtilityPayment::resource(), $id);
    }

    private function resource()
    {
        $payment = function ($array) {
            return new UtilityPayment($array);
        };
        return [
            "name" => "UtilityPayment",
            "maker" => $payment,
        ];
    }
}
