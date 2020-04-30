<?php

namespace StarkBank\Transfer;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;
use StarkBank\Utils\API;
use StarkBank\Transfer;


class Log extends Resource
{
    /**
    # Transfer\Log object

    Every time a Transfer entity is modified, a corresponding Transfer\Log
    is generated for the entity. This log is never generated by the
    user.

    ## Attributes:
        - id [string]: unique id returned when the log is created. ex: "5656565656565656"
        - transfer [Transfer]: Transfer entity to which the log refers to.
        - errors [list of strings]: list of errors linked to this BoletoPayment event.
        - type [string]: type of the Transfer event which triggered the log creation. ex: "processing" or "success"
        - created [DateTime]: creation datetime for the transfer.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->transfer = Checks::checkParam($params, "transfer");

        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific Log

    Receive a single Log object previously created by the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - Log object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
    # Retrieve Logs

    Receive a enumerator of Log objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date.
        - before [DateTime or string, default null] date filter for objects created only before specified date.
        - types [list of strings, default null]: filter retrieved objects by types. ex: "success" or "failed"
        - transferIds [list of strings, default null]: list of Transfer ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - enumerator of Log objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $transferLog = function ($array) {
            $transfer = function ($array) {
                return new Transfer($array);
            };
            $array["transfer"] = API::fromApiJson($transfer, $array["transfer"]);
            return new Log($array);
        };
        return [
            "name" => "TransferLog",
            "maker" => $transferLog,
        ];
    }
}
