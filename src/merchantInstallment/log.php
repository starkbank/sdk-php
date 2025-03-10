<?php

namespace StarkBank\MerchantInstallment;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\MerchantInstallment;


class Log extends Resource
{

    public $created;
    public $type;
    public $errors;
    public $installment;

    /**
    # MerchantInstallment\Log object    
      Check out our API Documentation at https://starkbank.com/docs/api#merchant-installment
    */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->installment = Checks::checkParam($params, "installment");

        Checks::checkParams($params);
    }

    public static function get($id, $user = null)
    {
        return Rest::getId($user, Log::resource(), $id);
    }

    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, Log::resource(), $options);
    }

    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, Log::resource(), $options);
    }

    private static function resource()
    {
        $installmentLog = function ($array) {
            $installment = function ($array) {
                return new MerchantInstallment($array);
            };
            $array["installment"] = API::fromApiJson($installment, $array["installment"]);
            return new Log($array);
        };
        return [
            "name" => "MerchantInstallmentLog",
            "maker" => $installmentLog,
        ];
    }
}
