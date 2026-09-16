<?php

namespace StarkBank\MerchantPurchase;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\MerchantPurchase;


class Log extends Resource
{

    public $created;
    public $type;
    public $errors;
    public $purchase;

    /**
    # MerchantPurchase\Log object

    Every time a MerchantPurchase entity is updated, a corresponding MerchantPurchase\Log is generated for the entity.

    ## Attributes (return-only):
        - id [string]: unique id returned when the log is created.
        - purchase [MerchantPurchase]: MerchantPurchase entity to which the log refers to.
        - errors [array of strings]: array of errors linked to this MerchantPurchase event.
        - type [string]: type of the MerchantPurchase event which triggered the log creation.
        - created [DateTime]: creation datetime for the log.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->type = Checks::checkParam($params, "type");
        $this->errors = Checks::checkParam($params, "errors");
        $this->purchase = Checks::checkParam($params, "purchase");

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
        $purchaseLog = function ($array) {
            $purchase = function ($array) {
                return new MerchantPurchase($array);
            };
            $array["purchase"] = API::fromApiJson($purchase, $array["purchase"]);
            return new Log($array);
        };
        return [
            "name" => "MerchantPurchaseLog",
            "maker" => $purchaseLog,
        ];
    }
}
