<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;;


class MerchantInstallment extends Resource
{
    public $amount;
    public $due;
    public $fee;
    public $fundingType;
    public $network;
    public $purchaseId;
    public $status;
    public $tags;
    public $transactionIds;
    public $created;
    public $updated;

    /**
    # MerchantInstallment object
    Check out our API Documentation at https://starkbank.com/docs/api#merchant-installment
    */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->due = Checks::checkParam($params, "due");
        $this->fee = Checks::checkParam($params, "fee");
        $this->fundingType = Checks::checkParam($params, "fundingType");
        $this->network = Checks::checkParam($params, "network");
        $this->purchaseId = Checks::checkParam($params, "purchaseId");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
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
        $merchantInstallment = function ($array) {
            return new MerchantInstallment($array);
        };
        return [
            "name" => "MerchantInstallment",
            "maker" => $merchantInstallment,
        ];
    }
}

