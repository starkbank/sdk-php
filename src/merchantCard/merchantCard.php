<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class MerchantCard extends Resource
{
    public $ending;
    public $fundingType;
    public $holderName;
    public $network;
    public $status;
    public $tags;
    public $expiration;
    public $created;
    public $updated;

    /**
    # MerchantCard object
    Check out our API Documentation at https://starkbank.com/docs/api#merchant-card
    */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->ending = Checks::checkParam($params, "ending");
        $this->fundingType = Checks::checkParam($params, "fundingType");
        $this->holderName = Checks::checkParam($params, "holderName");
        $this->network = Checks::checkParam($params, "network");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->expiration = Checks::checkParam($params, "expiration");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    public static function get($id, $user = null)
    {
        return Rest::getId($user, self::resource(), $id);
    }

    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, self::resource(), $options);
    }

    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, self::resource(), $options);
    }

    private static function resource()
    {
        $merchantCard = function ($array) {
            return new MerchantCard($array);
        };
        return [
            "name" => "MerchantCard",
            "maker" => $merchantCard,
        ];
    }
}
