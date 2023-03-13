<?php

namespace StarkBank\Event;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;


class Attempt extends Resource
{

    public $code;
    public $message;
    public $webhookId;
    public $eventId;
    public $created;

    /**
    # Event->Attempt object
    
    When an Event delivery fails, an event attempt will be registered.
    It carries information meant to help you debug event reception issues.
    
    ## Attributes (return-only):
        - id [string]: unique id that identifies the delivery attempt. ex: "5656565656565656"
        - code [string]: delivery error code. ex: badHttpStatus, badConnection, timeout
        - message [string]: delivery error full description. ex: "HTTP POST request returned status 404"
        - eventId [string]: ID of the Event whose delivery failed. ex: "4848484848484848"
        - webhookId [string]: ID of the Webhook that triggered this event. ex: "5656565656565656"
        - created [string]: datetime representing the moment when the attempt was made. ex: "2021-01-13T16:50:23.976155+00:00"
     */
    function __construct(array $params)
    {
        parent::__construct($params);
        
        $this->code = Checks::checkParam($params, "code");
        $this->message = Checks::checkParam($params, "message");
        $this->webhookId = Checks::checkParam($params, "webhookId");
        $this->eventId = Checks::checkParam($params, "eventId");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific Event->Attempt

    Receive a single Event->Attempt object previously created by the Stark Bank API by its id
    
    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - Event->Attempt object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Attempt::resource(), $id);
    }

    /**
    # Retrieve Event->Attempts

    Receive a generator of Event->Attempt objects previously created in the Stark Bank API
    
    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [string, default null]: date filter for objects created only after specified date. ex: "2020-03-10"
        - before [string, default null]: date filter for objects created only before specified date. ex: "2020-03-10"
        - eventIds [list of strings, default null]: list of Event ids to filter attempts. ex: ["5656565656565656", "4545454545454545"]
        - webhookIds [list of strings, default null]: list of Webhook ids to filter attempts. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - generator of Event->Attempt objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, Attempt::resource(), $options);
    }

    /**
    # Retrieve paged Event\Attempts

    Receive a list of up to 100 Event\Attempt objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - eventIds [list of strings, default null]: list of Event ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - webhookIds [list of strings, default null]: list of Webhook ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of Event\Attempt objects with updated attributes
        - cursor to retrieve the next page of Event\Attempt objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Attempt::resource(), $options);
    }

    private static function resource()
    {
        $attempt = function ($array) {
            return new Attempt($array);
        };
        return [
            "name" => "EventAttempt",
            "maker" => $attempt,
        ];
    }

}
