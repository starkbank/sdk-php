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
    public $holderId;
    public $softDescriptor;
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

    A MerchantPurchase charges a customer's credit or debit card. If the card has not been used before, it must first be approved through a MerchantSession Purchase; only then can it be charged directly through MerchantPurchase.create().

    ## Parameters (required):
        - amount [integer]: amount in cents to be received. ex: 100 (= R$1.00)
        - cardId [string]: id of the MerchantCard to be used.
        - fundingType [string]: "credit" or "debit".

    ## Parameters (conditionally required):
        - billingCity, billingCountryCode, billingStateCode, billingStreetLine1, billingStreetLine2, billingZipCode, holderEmail, holderPhone [string, default null]: required when challengeMode is "enabled", optional otherwise.
        - metadata [dictionary, default null]: when challengeMode is "enabled", must include the buyer device fields userAgent, timezoneOffset, userIp and language.

    ## Parameters (optional):
        - challengeMode [string, default "enabled"]: whether 3DS holder verification is used. Options: "enabled", "disabled".
        - installmentCount [integer, default 1]: number of purchase installments.

    ## Attributes (return-only):
        - id, cardEnding, challengeUrl, currencyCode, endToEndId, fee, network, source, status, tags, created, updated
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
        $this->holderId = Checks::checkParam($params, "holderId");
        $this->softDescriptor = Checks::checkParam($params, "softDescriptor");
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

    /**
    # Create a MerchantPurchase

    Charge a customer's credit or debit card. The card must already be approved through a MerchantSession Purchase before it can be charged directly through this function.

    ## Parameters (required):
        - merchantPurchase [MerchantPurchase object]: MerchantPurchase object to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - MerchantPurchase object with updated attributes
     */
    public static function create($merchantPurchase, $user = null)
    {
        return Rest::postSingle($user, self::resource(), $merchantPurchase);
    }

    /**
    # Retrieve a specific MerchantPurchase

    Receive a single MerchantPurchase object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id.

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - MerchantPurchase object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, self::resource(), $id);
    }

    /**
    # Retrieve MerchantPurchases

    Receive an enumerator of MerchantPurchase objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null.
        - after, before [DateTime or string, default null]: date filters.
        - status [string, default null]: filter for status of retrieved objects.
        - tags [array of strings, default null]: tags to filter retrieved objects.
        - ids [array of strings, default null]: array of ids to filter retrieved objects.
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of MerchantPurchase objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, self::resource(), $options);
    }

    /**
    # Retrieve paged MerchantPurchases

    Receive a list of up to 100 MerchantPurchase objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100.
        - (same filters as query: after, before, status, tags, ids)
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - list of MerchantPurchase objects with updated attributes
        - cursor to retrieve the next page of MerchantPurchase objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, self::resource(), $options);
    }

    /**
    # Update a MerchantPurchase entity

    Update a MerchantPurchase entity previously created in the Stark Bank API. If the purchase is "approved", the only allowed change is setting status to "canceled" with amount 0, which cancels the authorization. If the purchase is "confirmed", status can be set to "reversed" with a lower amount, which debits the difference; a partial reversal keeps status "confirmed", while a full reversal moves status to "voided".

    ## Parameters (required):
        - id [string]: MerchantPurchase unique id.

    ## Parameters (optional):
        - status [string, default null]: "canceled" or "reversed", per the rules above.
        - amount [integer, default null]: new amount in cents; 0 to cancel an approved purchase, or a lower amount to partially/fully reverse a confirmed one.
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - target MerchantPurchase with updated attributes
     */
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

