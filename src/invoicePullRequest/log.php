<?php

namespace StarkBank\InvoicePullRequest;
use StarkCore\Utils\API;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;
use StarkBank\InvoicePullRequest;


class Log extends Resource
{

    public $created;
    public $type;
    public $errors;
    public $request;
    public $reason;
    public $description;

    /**

     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->request = Checks::checkParam($params, "request");
        $this->reason = Checks::checkParam($params, "reason");
        $this->description = Checks::checkParam($params, "description");


        Checks::checkParams($params);
    }

    /**
 
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    /**
 
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Log::resource(), $options);
    }

    /**

     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $invoicePullRequestLog = function ($array) {
            $request = function ($array) {
                return new InvoicePullRequest($array);
            };
            $array["request"] = API::fromApiJson($request, $array["request"]);
            return new Log($array);
        };
        return [
            "name" => "InvoicePullRequestLog",
            "maker" => $invoicePullRequestLog,
        ];
    }
}
