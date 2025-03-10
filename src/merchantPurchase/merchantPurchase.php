<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;;


class MerchantPurchase extends Resource
{
    public $amount;
    public $cardId;
    public $fundingType;
    public $installmentCount;
    public $cardExpiration;
    public $cardNumber;
    public $cardSecurityCode;
    public $holderName;
    public $holderEmail;
    public $holderPhone;
    public $billingCountryCode;
    public $billingCity;
    public $billingStateCode;
    public $billingStreetLine1;
    public $billingStreetLine2;
    public $billingZipCode;
    public $metadata;
    public $cardEnding;
    public $challengeMode;
    public $challengeUrl;
    public $currencyCode;
    public $endToEndId;
    public $fee;
    public $network;
    public $source;
    public $status;
    public $tags;
    public $created;
    public $updated;

    /**
    # MerchantPurchase object
    Check out our API Documentation at https://starkbank.com/docs/api#merchant-purchase
    */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->installmentCount = Checks::checkParam($params, "installmentCount");
        $this->cardExpiration = Checks::checkParam($params, "cardExpiration");
        $this->cardNumber = Checks::checkParam($params, "cardNumber");
        $this->cardSecurityCode = Checks::checkParam($params, "cardSecurityCode");
        $this->holderName = Checks::checkParam($params, "holderName");
        $this->holderEmail = Checks::checkParam($params, "holderEmail");
        $this->holderPhone = Checks::checkParam($params, "holderPhone");
        $this->fundingType = Checks::checkParam($params, "fundingType");
        $this->billingCountryCode = Checks::checkParam($params, "billingCountryCode");
        $this->billingCity = Checks::checkParam($params, "billingCity");
        $this->billingStateCode = Checks::checkParam($params, "billingStateCode");
        $this->billingStreetLine1 = Checks::checkParam($params, "billingStreetLine1");
        $this->billingStreetLine2 = Checks::checkParam($params, "billingStreetLine2");
        $this->billingZipCode = Checks::checkParam($params, "billingZipCode");
        $this->metadata = Checks::checkParam($params, "metadata");
        $this->cardEnding = Checks::checkParam($params, "cardEnding");
        $this->cardId = Checks::checkParam($params, "cardId");
        $this->challengeMode = Checks::checkParam($params, "challengeMode");
        $this->challengeUrl = Checks::checkParam($params, "challengeUrl");
        $this->currencyCode = Checks::checkParam($params, "currencyCode");
        $this->endToEndId = Checks::checkParam($params, "endToEndId");
        $this->fee = Checks::checkParam($params, "fee");
        $this->network = Checks::checkParam($params, "network");
        $this->source = Checks::checkParam($params, "source");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    public static function create($merchantPurchase, $user = null)
    {
        return Rest::postSingle($user, self::resource(), $merchantPurchase);
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

    public static function update($id, $status = null, $amount = null, $user = null)
    {
        $payload = [
            "status" => $status,
            "amount" => $amount
        ];
        return Rest::patchId($user, self::resource(), $id, $payload);
    }

    private static function resource()
    {
        $merchantPurchase = function ($array) {
            return new MerchantPurchase($array);
        };
        return [
            "name" => "MerchantPurchase",
            "maker" => $merchantPurchase,
        ];
    }
}

