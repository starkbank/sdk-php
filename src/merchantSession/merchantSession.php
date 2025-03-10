<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\MerchantSession\Purchase;
use StarkBank\MerchantSession\AllowedInstallment;


class MerchantSession extends Resource
{
    public $allowedFundingTypes;
    public $allowedInstallments;
    public $allowedIps;
    public $challengeMode;
    public $expiration;
    public $status;
    public $tags;
    public $uuid;
    public $created;
    public $updated;

    /**
    # MerchantSession object
    Check out our API Documentation at https://starkbank.com/docs/api#merchant-session
    */
    function __construct(array $params)
    {
        parent::__construct($params);
        
        $this->allowedFundingTypes = Checks::checkParam($params, "allowedFundingTypes");
        $this->allowedInstallments = AllowedInstallment::parseAllowedInstallment(Checks::checkParam($params, "allowedInstallments"));
        $this->allowedIps = Checks::checkParam($params, "allowedIps");
        $this->challengeMode = Checks::checkParam($params, "challengeMode");
        $this->expiration = Checks::checkParam($params, "expiration");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->uuid = Checks::checkParam($params, "uuid");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        
        Checks::checkParams($params);
    }

    public static function create($merchantSession, $user = null)
    {
        return Rest::postSingle($user, MerchantSession::resource(), $merchantSession);
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

    public static function purchase($uuid, $purchase, $user = null)
    {
        return Rest::postSubResource($user, self::resource(), $uuid, Purchase::resource(), $purchase);
    }

    private static function resource()
    {
        $merchantSession = function ($array) {
            return new MerchantSession($array);
        };
        return [
            "name" => "MerchantSession",
            "maker" => $merchantSession,
        ];
    }
}
