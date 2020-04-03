<?php

namespace StarkBank;

use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Webhook extends Resource
{
    /**
    # Webhook subscription object

    A Webhook is used to subscribe to notification events on a user-selected endpoint.
    Currently available services for subscription are transfer, boleto, boleto-payment,
    and utility-payment

    ## Parameters (required):
        - url [string]: Url that will be notified when an event occurs.
        - subscriptions [list of strings]: list of any non-empty combination of the available services. ex: ["transfer", "boleto-payment"]

    ## Attributes:
        - id [string, default None]: unique id returned when the log is created. ex: "5656565656565656"
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->url = $params["url"];
        unset($params["url"]);
        $this->subscriptions = $params["subscriptions"];
        unset($params["subscriptions"]);

        Checks::checkParams($params);
    }

    /**
    # Create Webhook subscription

    Send a single Webhook subscription for creation in the Stark Bank API

    ## Parameters (required):
        - url [string]: url to which notification events will be sent to. ex: "https://webhook.site/60e9c18e-4b5c-4369-bda1-ab5fcd8e1b29"
        - subscriptions [list of strings]: list of any non-empty combination of the available services. ex: ["transfer", "boleto-payment"]

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - Webhook object with updated attributes
     */
    public function create($user, $url, $subscriptions)
    {
        return Rest::postSingle($user, Webhook::resource(), new Webhook(["url" => $url, "subscriptions" => $subscriptions]));
    }

    /**
    # Retrieve a specific Webhook subscription

    Receive a single Webhook subscription object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - Webhook object with updated attributes
     */
    public function get($user, $id)
    {
        return Rest::getId($user, Webhook::resource(), $id);
    }

    /**
    # Retrieve Webhook subcriptions

    Receive a generator of Webhook subcription objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default None]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        - user [Project object, default None]: Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - generator of Webhook objects with updated attributes
     */
    public function query($user, $options = [])
    {
        return Rest::getList($user, Webhook::resource(), $options);
    }

    /**
    # Delete a Webhook subscription entity

    Delete a Webhook subscription entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Webhook unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - deleted Webhook with updated attributes
     */
    public function delete($user, $id)
    {
        return Rest::deleteId($user, Webhook::resource(), $id);
    }

    private function resource()
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
