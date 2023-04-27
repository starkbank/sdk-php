<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkBank\Utils\Parse;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class CorporatePurchase extends Resource
{

    public $holderId;
    public $holderName;
    public $centerId;
    public $cardId;
    public $cardEnding;
    public $description;
    public $amount;
    public $tax;
    public $issuerAmount;
    public $issuerCurrencyCode;
    public $issuerCurrencySymbol;
    public $merchantAmount;
    public $merchantCurrencyCode;
    public $merchantCurrencySymbol;
    public $merchantCategoryCode;
    public $merchantCategoryType;
    public $merchantCountryCode;
    public $merchantName;
    public $merchantDisplayName;
    public $merchantDisplayUrl;
    public $merchantFee;
    public $methodCode;
    public $tags;
    public $corporateTransactionIds;
    public $status;
    public $updated;
    public $created;

    /**
    # CorporatePurchase object

    Displays the CorporatePurchase objects created to your Workspace.

    ## Attributes (return-only):
        - id [string]: unique id returned when CorporatePurchase is created. ex: "5656565656565656"
        - holderId [string]: card holder unique id. ex: "5656565656565656"
        - holderName [string]: card holder name. ex: "Tony Stark"
        - centerId [string]: target cost center ID. ex: "5656565656565656"
        - cardId [string]: unique id returned when CorporateCard is created. ex: "5656565656565656"
        - cardEnding [string]: last 4 digits of the card number. ex: "1234"
        - description [string]: purchase description. ex: "my_description"
        - amount [integer]: CorporatePurchase value in cents. Minimum = 0. ex: 1234 (= R$ 12.34)
        - tax [integer]: IOF amount taxed for international purchases. ex: 1234 (= R$ 12.34)
        - issuerAmount [integer]: issuer amount. ex: 1234 (= R$ 12.34)
        - issuerCurrencyCode [string]: issuer currency code. ex: "USD"
        - issuerCurrencySymbol [string]: issuer currency symbol. ex: "$"
        - merchantAmount [integer]: merchant amount. ex: 1234 (= R$ 12.34)
        - merchantCurrencyCode [string]: merchant currency code. ex: "USD"
        - merchantCurrencySymbol [string]: merchant currency symbol. ex: "$"
        - merchantCategoryCode [string]: merchant category code. ex: "fastFoodRestaurants"
        - merchantCategoryType [string]: merchant category type. ex: "health"
        - merchantCountryCode [string]: merchant country code. ex: "USA"
        - merchantName [string]: merchant name. ex: "Google Cloud Platform"
        - merchantDisplayName [string]: merchant name. ex: "Google Cloud Platform"
        - merchantDisplayUrl [string]: public merchant icon (png image). ex: "https://sandbox.api.starkbank.com/v2/corporate-icon/merchant/ifood.png"
        - merchantFee [integer]: fee charged by the merchant to cover specific costs, such as ATM withdrawal logistics, etc. ex: 200 (= R$ 2.00)
        - methodCode [string]: method code. Options: "chip", "token", "server", "manual" or "contactless"
        - tags [array of string]: array of strings for tagging returned by the sub-issuer during the authorization. ex: ["travel", "food"]
        - corporateTransactionIds [string]: ledger transaction ids linked to this Purchase
        - status [string]: current CorporateCard status. Options: "approved", "canceled", "denied", "confirmed" or "voided"
        - updated [DateTime]: latest update datetime for the CorporatePurchase.
        - created [DateTime]: creation datetime for the CorporatePurchase.
    */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->holderId = Checks::checkParam($params, "holderId");
        $this->holderName = Checks::checkParam($params, "holderName");
        $this->centerId = Checks::checkParam($params, "centerId");
        $this->cardId = Checks::checkParam($params, "cardId");
        $this->cardEnding = Checks::checkParam($params, "cardEnding");
        $this->description = Checks::checkParam($params, "description");
        $this->amount = Checks::checkParam($params, "amount");
        $this->tax = Checks::checkParam($params, "tax");
        $this->issuerAmount = Checks::checkParam($params, "issuerAmount");
        $this->issuerCurrencyCode = Checks::checkParam($params, "issuerCurrencyCode");
        $this->issuerCurrencySymbol = Checks::checkParam($params, "issuerCurrencySymbol");
        $this->merchantAmount = Checks::checkParam($params, "merchantAmount");
        $this->merchantCurrencyCode = Checks::checkParam($params, "merchantCurrencyCode");
        $this->merchantCurrencySymbol = Checks::checkParam($params, "merchantCurrencySymbol");
        $this->merchantCategoryCode = Checks::checkParam($params, "merchantCategoryCode");
        $this->merchantCategoryType = Checks::checkParam($params, "merchantCategoryType");
        $this->merchantCountryCode = Checks::checkParam($params, "merchantCountryCode");
        $this->merchantName = Checks::checkParam($params, "merchantName");
        $this->merchantDisplayName = Checks::checkParam($params, "merchantDisplayName");
        $this->merchantDisplayUrl = Checks::checkParam($params, "merchantDisplayUrl");
        $this->merchantFee = Checks::checkParam($params, "merchantFee");
        $this->methodCode = Checks::checkParam($params, "methodCode");
        $this->tags = Checks::checkParam($params, "tags");
        $this->corporateTransactionIds = Checks::checkParam($params, "corporateTransactionIds");
        $this->status = Checks::checkParam($params, "status");
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        
        Checks::checkParams($params);
    }

    /**
    # Retrieve a specific CorporatePurchase

    Receive a single CorporatePurchase object previously created in the Stark Bank API by its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporatePurchase object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, CorporatePurchase::resource(), $id);
    }

    /**
    # Retrieve CorporatePurchases

    Receive an enumerator of CorporatePurchase objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - merchantCategoryTypes [array of strings, default null]: merchant category type. ex: ["health"]
        - holderIds [array of strings, default null]: card holder IDs. ex: ["5656565656565656", "4545454545454545"]
        - cardIds [array of strings, default null]: card  IDs. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "approved", "canceled", "denied", "confirmed" or "voided"
        - ids [array of strings, default null]: purchase IDs
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of CorporatePurchase objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, CorporatePurchase::resource(), $options);
    }

    /**
    # Retrieve paged CorporatePurchases

    Receive a list of up to 100 CorporatePurchases objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - merchantCategoryTypes [array of strings, default null]: merchant category type. ex: ["health"]
        - holderIds [array of strings, default null]: card holder IDs. ex: ["5656565656565656", "4545454545454545"]
        - cardIds [array of strings, default null]: card  IDs. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "approved", "canceled", "denied", "confirmed" or "voided"
        - ids [array of strings, default null]: purchase IDs
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - list of CorporatePurchase objects with updated attributes
        - cursor to retrieve the next page of CorporatePurchase objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, CorporatePurchase::resource(), $options);
    }

    /**
    # Create a single verified CorporatePurchase authorization request from a content string

    Use this method to parse and verify the authenticity of the authorization request received at the informed endpoint.
    Authorization requests are posted to your registered endpoint whenever CorporatePurchases are received.
    They present CorporatePurchase data that must be analyzed and answered with approval or declination.
    If the provided digital signature does not check out with the StarkBank public key, a stark.exception.InvalidSignatureException will be raised.
    If the authorization request is not answered within 2 seconds or is not answered with a HTTP status code 200 the CorporatePurchase will go through the pre-configured stand-in validation.

    ## Parameters (required):
        - content [string]: response content from request received at user endpoint (not parsed)
        - signature [string]: base-64 digital signature received at response header "Digital-Signature"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - Parsed CorporatePurchase object
     */
    public static function parse($content, $signature, $user = null)
    {
        return Parse::parseAndVerify($content, $signature, CorporatePurchase::resource(), $user);
    }

    /** 
    # Helps you respond CorporatePurchase requests

    ## Parameters (required):
        - status [string]: sub-issuer response to the authorization. ex: "approved" or "denied"
    
    ## Parameters (conditionally required):
        - reason [string, default null]: denial reason. Options: "other", "blocked", "lostCard", "stolenCard", "invalidPin", "invalidCard", "cardExpired", "issuerError", "concurrency", "standInDenial", "subIssuerError", "invalidPurpose", "invalidZipCode", "invalidWalletId", "inconsistentCard", "settlementFailed", "cardRuleMismatch", "invalidExpiration", "prepaidInstallment", "holderRuleMismatch", "insufficientBalance", "tooManyTransactions", "invalidSecurityCode", "invalidPaymentMethod", "confirmationDeadline", "withdrawalAmountLimit", "insufficientCardLimit", "insufficientHolderLimit"

    ## Parameters (optional):
        - amount [integer, default null]: amount in cents that was authorized. ex: 1234 (= R$ 12.34)
        - tags [array of strings, default null]: tags to filter retrieved object. ex: ["tony", "stark"]

    ## Return:
        - Dumped JSON string that must be returned to us on the CorporatePurchase request
    */
    public static function response($params)
    {
        $params = ([
            "authorization" => [
                "status" => Checks::checkParam($params, "status"),
                "amount" => Checks::checkParam($params, "amount"),
                "reason" => Checks::checkParam($params, "reason"),
                "tags" => Checks::checkParam($params, "tags"),
            ]
        ]);
        return json_encode(API::apiJson($params));
    }

    private static function resource()
    {
        $purchase = function ($array) {
            return new CorporatePurchase($array);
        };
        return [
            "name" => "CorporatePurchase",
            "maker" => $purchase,
        ];
    }
}
