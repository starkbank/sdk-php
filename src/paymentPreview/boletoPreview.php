<?php

namespace StarkBank\PaymentPreview;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class BoletoPreview extends SubResource
{

    public $status;
    public $amount;
    public $discountAmount;
    public $fineAmount;
    public $interestAmount;
    public $due;
    public $expiration;
    public $name;
    public $taxId;
    public $receiverName;
    public $receiverTaxId;
    public $payerName;
    public $payerTaxId;
    public $line;
    public $barCode;

    /**
    # BoletoPreview object

    A BoletoPreview is used to get information from a Boleto payment you received before confirming the payment.

    ## Attributes (return-only):
        - status [string]: current boleto status. ex: "active", "expired" or "inactive"
        - amount [integer]: final amount to be paid. ex: 23456 (= R$ 234.56)
        - discountAmount [integer]: discount amount to be paid. ex: 23456 (= R$ 234.56)
        - fineAmount [integer]: fine amount to be paid. ex: 23456 (= R$ 234.56)
        - interestAmount [integer]: interest amount to be paid. ex: 23456 (= R$ 234.56)
        - due [DateTime]: Boleto due date. DateTime('2020-01-01T15:03:01.012345Z')
        - expiration [DateTime]: Boleto expiration date. DateTime('2020-01-01T15:03:01.012345Z')
        - name [string]: beneficiary full name. ex: "Anthony Edward Stark"
        - taxId [string]: beneficiary tax ID (CPF or CNPJ). ex: "20.018.183/0001-80"
        - receiverName [string]: receiver (Sacador Avalista) full name. ex: "Anthony Edward Stark"
        - receiverTaxId [string]: receiver (Sacador Avalista) tax ID (CPF or CNPJ). ex: "20.018.183/0001-80"
        - payerName [string]: payer full name. ex: "Anthony Edward Stark"
        - payerTaxId [string]: payer tax ID (CPF or CNPJ). ex: "20.018.183/0001-80"
        - line [string]: Number sequence that identifies the payment. ex: "34191.09008 63571.277308 71444.640008 5 81960000000062"
        - barCode [string]: Bar code number that identifies the payment. ex: "34195819600000000621090063571277307144464000"
     */
    function __construct(array $params)
    {
        $this->status = Checks::checkParam($params, "status");
        $this->amount = Checks::checkParam($params, "amount");
        $this->discountAmount = Checks::checkParam($params, "discountAmount");
        $this->fineAmount = Checks::checkParam($params, "fineAmount");
        $this->interestAmount = Checks::checkParam($params, "interestAmount");
        $this->due = Checks::checkParam($params, "due");
        $this->expiration = Checks::checkParam($params, "expiration");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->receiverName = Checks::checkParam($params, "receiverName");
        $this->receiverTaxId = Checks::checkParam($params, "receiverTaxId");
        $this->payerName = Checks::checkParam($params, "payerName");
        $this->payerTaxId = Checks::checkParam($params, "payerTaxId");
        $this->line = Checks::checkParam($params, "line");
        $this->barCode = Checks::checkParam($params, "barCode");

        Checks::checkParams($params);
    }

    static function resource()
    {
        $preview = function ($array) {
            return new BoletoPreview($array);
        };
        return [
            "name" => "BoletoPreview",
            "maker" => $preview,
        ];
    }
}
