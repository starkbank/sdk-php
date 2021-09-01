<?php

namespace StarkBank\DarfPayment;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\StarkBankDateTime;
use StarkBank\Utils\StarkBankDate;


class Log extends Resource
{
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->payment = Checks::checkParam($params, "type");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific DarfPayment\Log
    
    Receive a single DarfPayment\Log object previously created by the Stark Bank API by passing its id
    
    ## Parameters (required):
    - id [string]: object unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
    - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - DarfPayment\Log object with updated attributes
    */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve DarfPayment\Log's

    Receive a generator of DarfPayment\Log objects previously created in the Stark Bank API

    ## Parameters (optional):
    - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
    - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
    - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
    - types [list of strings, default null]: filter retrieved objects by event types. ex: "processing" or "success"
    - paymentIds [list of strings, default null]: list of DarfPayment ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
    - user [Project object, default null]: Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - list of DarfPayment\Log objects with updated attributes
    */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, Log::resource(), $options);
    }

    /**
    # Retrieve paged DarfPayment\Log's
    
    Receive a list of up to 100 DarfPayment\Log objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.
    
    ## Parameters (optional):
    - cursor [string, default null]: cursor returned on the previous page function call
    - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
    - after [datetime.date or string, default null] date filter for objects created only after specified date. ex: "2020-03-10"
    - before [datetime.date or string, default null] date filter for objects created only before specified date. ex: "2020-03-10"
    - types [list of strings, default null]: filter retrieved objects by types. ex: "success" or "failed"
    - paymentIds [list of strings, default null]: list of DarfPayment ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
    - user [Organization/Project object, default null, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - list of DarfPayment\Log objects with updated attributes
    - cursor to retrieve the next page of DarfPayment\Log objects
    */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $darfPaymentLog = function ($array) {
            return new Log($array);
        };
        return [
            "name" => "DarfPaymentLog",
            "maker" => $darfPaymentLog,
        ];
    }
}
