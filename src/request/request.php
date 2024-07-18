<?php

namespace StarkBank;
use StarkBank\Utils\Rest;

class Request
{
    
    /**
    # Retrieve any StarkBank resource
        Receive a json of resources previously created in StarkBank's API
    ## Parameters (required):
        - path [string]: StarkBank resource's route. ex: "/invoice/"
        - query [array of Invoice objects]: Query parameters. ex: ["limit" => 1, "status" => paid]
    ## Parameters (optional):
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call
    ## Return:
        - array of StarkBank objects with updated attributes     */
    public static function get($path, $options = [], $user = null)
    {
        return Rest::getRaw($user, $path, $options, "Joker", false);
    }

    /**
    # Create any StarkBank resource
        - Send an array of strings and create any StarkBank resource objects
    
    ## Parameters (required):
        - path [string]: StarkBank resource's route. ex: "/invoice/"
        - body [array of strings]: request parameters. ex: ["invoices" => [["amount" => 100, "name" => "Iron Bank S.A.", "taxId" => "20.018.183/0001-80"]]]
    
    ## Parameters (optional):
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call
    
    ## Return:
        - Retrieve created resources
     */
    public static function post($path, $body = [], $user = null)
    {
        return Rest::postRaw($user, $path, $body, "Joker", false);
    }

    /**
    # Update any StarkBank resource
        - Send a json with parameters of a single StarkBank resource object and update it
    
    ## Parameters (required):
        - path [string]: StarkBank resource's route. ex: "/invoice/5699165527090460"
        - body [array of strings, default null]: request parameters. ex: ["amount" => 100]
    
    ## Parameters (optional):
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call
    
    ## Return:
        - Retrieve updated resource
     */
    public static function patch($path, $body = [], $user = null)
    {
        return Rest::patchRaw($user, $path, $body, "Joker", false);
    }

    /**
    # Put any StarkBank resource
        - Send a json with parameters of a single StarkBank resource object and update it
    
    ## Parameters (required):
        - path [string]: StarkBank resource's route. ex: "/invoice/5699165527090460"
        - body [array of strings, default null]: request parameters. ex: ["amount" => 100]
    
    ## Parameters (optional):
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call
    
    ## Return:
        - Retrieve updated resource
     */
    public static function put($path, $body = [], $user = null)
    {
        return Rest::putRaw($user, $path, $body, "Joker", false);
    }

        /**
    # Delete any StarkBank resource
        - Send a json with parameters of a single StarkBank resource object and delete it
    
    ## Parameters (required):
        - path [string]: StarkBank resource's route. ex: "/invoice/5699165527090460"
    
    ## Parameters (optional):
        - user [Organization/Project object, default None]: Organization or Project object. Not necessary if starkbank.user was set before function call
        - body [array of strings, default null]: request parameters. ex: ["amount" => 100]
    
    ## Return:
        - json of the resource with updated attributes
     */
    public static function delete($path, $body = [], $user = null)
    {
        return Rest::deleteRaw($user, $path, $body, "Joker", false);
    }
}
