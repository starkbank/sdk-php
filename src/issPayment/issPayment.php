<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class IssPayment extends Resource
{
    /**
    # IssPayment object

    When you initialize a IssPayment, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (conditionally required):
        - line [string, default null]: Number sequence that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string, default null]: Bar code number that describes the payment. Either 'line' or 'barCode' parameters are required. If both are sent, they must match. ex: "34195819600000000621090063571277307144464000"

    ## Parameters (required):
        - description [string]: Text to be displayed in your statement (min. 10 characters). ex: "payment ABC"

    ## Parameters (optional):
        - scheduled [DateTime or string, default today]: payment scheduled date.
        - tags [list of strings]: list of strings for tagging

    ## Attributes (return-only):
        - id [string, default null]: unique id returned when payment is created. ex: "5656565656565656"
        - status [string, default null]: current payment status. ex: "success" or "failed"
        - amount [int, default null]: amount automatically calculated from line or bar_code. ex: 23456 (= R$ 234.56)
        - fee [integer, default null]: fee charged when ISS payment is created. ex: 200 (= R$ 2.00)
        - created [DateTime, default null]: creation datetime for the payment.
     */
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
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Create IssPayments

    Send a list of IssPayment objects for creation in the Stark Bank API

    ## Parameters (required):
        - payments [list of IssPayment objects]: list of IssPayment objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - list of IssPayment objects with updated attributes
     */
    public static function create($payments, $user = null)
    {
        return Rest::post($user, IssPayment::resource(), $payments);
    }

    /**
    # Retrieve a specific IssPayment

    Receive a single IssPayment object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - IssPayment object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, IssPayment::resource(), $id);
    }

    /**
    # Retrieve a specific IssPayment pdf file

    Receive a single IssPayment pdf file generated in the Stark Bank API by passing its id.
    Only valid for ISS payments with "success" status.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - IssPayment pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getPdf($user, IssPayment::resource(), $id);
    }

    /**
    # Retrieve IssPayments

    Receive a enumerator of IssPayment objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "paid"
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of IssPayment objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, IssPayment::resource(), $options);
    }

    /**
    # Delete a IssPayment entity

    Delete a IssPayment entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: IssPayment unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - deleted IssPayment with updated attributes
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, IssPayment::resource(), $id);
    }

    private static function resource()
    {
        $payment = function ($array) {
            return new IssPayment($array);
        };
        return [
            "name" => "IssPayment",
            "maker" => $payment,
        ];
    }
}
