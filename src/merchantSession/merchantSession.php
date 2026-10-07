<?php

namespace StarkBank;
use Exception;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
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
    public $softDescriptor;
    public $holderId;
    public $created;
    public $updated;

    /**
    # MerchantSession object

    A MerchantSession is created by a merchant and used by the card holder to submit card data directly to Stark Bank (via the Merchant Session Purchase route) without the merchant ever handling raw card data. The returned uuid must be used to create the corresponding MerchantSession Purchase.

    ## Parameters (required):
        - allowedFundingTypes [array of strings]: funding types allowed for the purchase. Options: "credit", "debit".
        - allowedInstallments [array of dictionaries]: allowed installment configurations, each with an amount in cents and a number of installments.
        - expiration [integer]: time in seconds from creation until the session expires; a purchase can no longer be created with it afterwards. ex: 3600 (1 hour)

    ## Parameters (optional):
        - allowedIps [array of strings, default null]: IP addresses allowed to create a purchase with this session.
        - challengeMode [string, default "enabled"]: whether 3DS holder verification is used. Options: "enabled", "disabled".
        - tags [array of strings, default null]: array of strings for tagging

    ## Attributes (return-only):
        - id, uuid, status, created, updated
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
        $this->softDescriptor = Checks::checkParam($params, "softDescriptor");
        $this->holderId = Checks::checkParam($params, "holderId");
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

    /**
     * @deprecated Function deprecated since v2.26.0
    # Create a MerchantSession Purchase (removed)

    purchase is deprecated and disabled in this SDK: calling it raises an Exception immediately, without making any request. Card data must be sent directly from the payer's front-end to the Stark Bank API through the POST /v2/merchant-session/:uuid/purchase route, so that sensitive card data never passes through the back-end of your integration.
     */
    public static function purchase($uuid, $purchase, $user = null)
    {
        throw new Exception("Function deprecated since v2.26.0");
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
