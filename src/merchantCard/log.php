<?php

namespace StarkBank\MerchantCard;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\MerchantCard;


class Log extends Resource
{

    public $created;
    public $type;
    public $errors;
    public $card;

    /**
    # MerchantCard\Log object  
      Check out our API Documentation at https://starkbank.com/docs/api#merchant-card
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->card = Checks::checkParam($params, "card");

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
        $cardLog = function ($array) {
            $card = function ($array) {
                return new MerchantCard($array);
            };
            $array["card"] = API::fromApiJson($card, $array["card"]);
            return new Log($array);
        };
        return [
            "name" => "MerchantCardLog",
            "maker" => $cardLog,
        ];
    }
}
