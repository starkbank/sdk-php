<?php

namespace StarkBank\MerchantSession;

use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;


class Purchase extends Resource
{   
    public $installmentCount;
    public $amount;
    public $cardExpiration;
    public $cardNumber;
    public $cardSecurityCode;
    public $holderName;
    public $holderEmail;
    public $holderPhone;
    public $holderId;
    public $softDescriptor;
    public $fundingType;
    public $billingCountryCode;
    public $billingCity;
    public $billingStateCode;
    public $billingStreetLine1;
    public $billingStreetLine2;
    public $billingZipCode;
    public $metadata;
    public $cardEnding;
    public $cardId;
    public $challengeMode;
    public $challengeUrl;
    public $created;
    public $currencyCode;
    public $endToEndId;
    public $fee;
    public $network;
    public $source;
    public $status;
    public $tags;
    public $updated;
    
    /**
    # MerchantSession\Purchase object

    Carries the card and purchase data submitted through MerchantSession::purchase() to charge a card directly from the payer's client application.

    ## Parameters (required):
        - amount [integer]: amount in cents to be received.
        - cardExpiration [string]: card expiration in "YYYY-MM" format.
        - cardNumber [string]: card number.
        - cardSecurityCode [string]: card security code.
        - fundingType [string]: "credit" or "debit".
        - holderName [string]: card holder name as printed on the card.

    ## Parameters (conditionally required):
        - billingCity, billingCountryCode, billingStateCode, billingStreetLine1, billingStreetLine2, billingZipCode, holderEmail, holderPhone: required when the session's challengeMode is "enabled", optional otherwise.
        - metadata [dictionary]: when challengeMode is "enabled", must include the payer device fields userAgent, timezoneOffset, userIp and language.

    ## Parameters (optional):
        - installmentCount [integer, default 1]: number of purchase installments.

    ## Attributes (return-only):
        - cardEnding, cardId, challengeUrl, created, currencyCode, endToEndId, fee, network, source, status, tags, updated
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->installmentCount = Checks::checkParam($params, "installmentCount");
        $this->amount = Checks::checkParam($params, "amount");
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
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->currencyCode = Checks::checkParam($params, "currencyCode");
        $this->endToEndId = Checks::checkParam($params, "endToEndId");
        $this->fee = Checks::checkParam($params, "fee");
        $this->network = Checks::checkParam($params, "network");
        $this->source = Checks::checkParam($params, "source");
        $this->status = Checks::checkParam($params, "status");
        $this->tags = Checks::checkParam($params, "tags");
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        
        
        Checks::checkParams($params);
    }

    public static function resource()
    {
        $purchase = function ($array) {
            return new Purchase($array);
        };
        return [
            "name" => "Purchase",
            "maker" => $purchase,
        ];
    }
}
