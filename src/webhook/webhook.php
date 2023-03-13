<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;


class Webhook extends Resource
{

    public $url;
    public $subscriptions;

    /**
    # Webhook subscription object

    A Webhook is used to subscribe to notification events on a user-selected endpoint.
    Currently available services for subscription are transfer, boleto, boleto-holmes,
    boleto-payment, utility-payment, invoice, deposit and brcode-payment.

    ## Parameters (required):
        - url [string]: Url that will be notified when an event occurs.
        - subscriptions [array of strings]: array of any non-empty combination of the available services. ex: ["transfer", "invoice"]

    ## Attributes (return-only):
        - id [string]: unique id returned when the webhook is created. ex: "5656565656565656"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->url = Checks::checkParam($params, "url");
        $this->subscriptions = Checks::checkParam($params, "subscriptions");

        Checks::checkParams($params);
    }

    /**
    # Create Webhook subscription

    Send a single Webhook subscription for creation in the Stark Bank API

    ## Parameters (required):
        - url [string]: url to which notification events will be sent to. ex: "https://webhook.site/60e9c18e-4b5c-4369-bda1-ab5fcd8e1b29"
        - subscriptions [array of strings]: array of any non-empty combination of the available services. ex: ["transfer", "deposit"]

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Webhook object with updated attributes
     */
    public static function create(array $params, $user = null)
    {
        return Rest::postSingle($user, Webhook::resource(), new Webhook($params));
    }

    /**
    # Retrieve a specific Webhook subscription

    Receive a single Webhook subscription object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Webhook object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Webhook::resource(), $id);
    }

    /**
    # Retrieve Webhook subcriptions

    Receive a enumerator of Webhook subcription objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of Webhook objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, Webhook::resource(), $options);
    }

    /**
    # Retrieve paged Webhooks

    Receive a list of up to 100 Webhook objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of Webhook objects with updated attributes
        - cursor to retrieve the next page of Webhook objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Webhook::resource(), $options);
    }

    /**
    # Delete a Webhook subscription entity

    Delete a Webhook subscription entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Webhook unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - deleted Webhook object
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, Webhook::resource(), $id);
    }

    private static function resource()
    {
        $webhook = function ($array) {
            return new Webhook($array);
        };
        return [
            "name" => "Webhook",
            "maker" => $webhook,
        ];
    }
}
