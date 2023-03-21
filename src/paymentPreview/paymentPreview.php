<?php

namespace StarkBank;
use StarkCore\Utils\Api;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkBank\PaymentPreview\TaxPreview;
use StarkBank\PaymentPreview\BrcodePreview;
use StarkBank\PaymentPreview\BoletoPreview;
use StarkBank\PaymentPreview\UtilityPreview;


class PaymentPreview extends Resource
{

    public $scheduled;
    public $type;
    public $payment;

    /**
    # PaymentPreview object

    A PaymentPreview is used to get information from a payment code before confirming the payment.
    This resource can be used to preview BR Codes and bar codes of boleto, tax and utility payments

    ## Parameters (required):
        - id [string]: Main identification of the payment. This should be the BR Code for Pix payments and lines or bar codes for payment slips. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062", "00020126580014br.gov.bcb.pix0136a629532e-7693-4846-852d-1bbff817b5a8520400005303986540510.005802BR5908T'Challa6009Sao Paulo62090505123456304B14A"
        
    ## Parameters (optional):
        - scheduled [Date or string, default now]: intended payment date. Right now, this parameter only has effect on BrcodePreviews. ex: "2020-11-30"

    ## Attributes (return-only):
        - type [string]: Payment type. ex: "brcode-payment", "boleto-payment", "utility-payment" or "tax-payment"
        - payment [BrcodePreview, BoletoPreview, UtilityPreview or TaxPreview]: Information preview of the informed payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params);
        
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->type = Checks::checkParam($params, "type");
        $this->payment = Checks::checkParam($params, "payment");

        $subResourceByType = array(
            "brcode-payment" => function ($array) { return new BrcodePreview($array); },
            "boleto-payment" => function ($array) { return new BoletoPreview($array); },
            "tax-payment" => function ($array) { return new TaxPreview($array); },
            "utility-payment" => function ($array) { return new UtilityPreview($array); }
        );

        if (array_key_exists($this->type, $subResourceByType)) {
            $this->payment = API::fromApiJson($subResourceByType[$this->type], $this->payment);
        }

        Checks::checkParams($params);
    }

    /**
    # Create PaymentPreviews

    Send a list of PaymentPreviews objects for processing in the Stark Bank API

    ## Parameters (required):
        - previews [list of PaymentPreviews objects]: list of PaymentPreviews objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - array of PaymentPreview objects with updated attributes
     */
    public static function create($previews, $user = null)
    {
        return Rest::post($user, PaymentPreview::resource(), $previews);
    }

    private static function resource()
    {
        $paymentPreview = function ($array) {
            return new PaymentPreview($array);
        };
        return [
            "name" => "PaymentPreview",
            "maker" => $paymentPreview,
        ];
    }
}
