<?php

namespace StarkBank\TaxPayment;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;


class Log extends Resource
{

    public $type;
    public $errors;
    public $payment;
    public $created;

    /**
    # TaxPayment\Log object

    Every time a TaxPayment entity is updated, a corresponding TaxPayment\Log
    is generated for the entity. This log is never generated by the
    user, but it can be retrieved to check additional information
    on the TaxPayment.

    ## Attributes (return-only):
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - payment [TaxPayment]: TaxPayment entity to which the log refers to.
        - errors [array of strings]: array of errors linked to this TaxPayment event
        - type [string]: type of the TaxPayment event which triggered the log creation. ex: "processing" or "success"
        - created [DateTime]: creation datetime for the log.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->payment = Checks::checkParam($params, "payment");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific TaxPayment\Log

    Receive a single TaxPayment\Log object previously created by the Stark Bank API by passing its id
    
    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - TaxPayment\Log object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve Logs

    Receive a generator of TaxPayment\Log objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
        - types [list of strings, default null]: filter retrieved objects by event types. ex: "processing" or "success"
        - paymentIds [list of strings, default null]: list of TaxPayment ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Project object, default null]: Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - generator of TaxPayment\Log objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, Log::resource(), $options);
    }

    /**
    # Retrieve paged Logs

    Receive a list of up to 100 TaxPayment\Log objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [datetime.date or string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
        - before [datetime.date or string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
        - types [list of strings, default null]: filter retrieved objects by types. ex: "success" or "failed"
        - paymentIds [list of strings, default null]: list of TaxPayment ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - list of TaxPayment\Log objects with updated attributes
        - cursor to retrieve the next page of TaxPayment\Log objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $taxPaymentLog = function ($array) {
            return new Log($array);
        };
        return [
            "name" => "TaxPaymentLog",
            "maker" => $taxPaymentLog,
        ];
    }
}
