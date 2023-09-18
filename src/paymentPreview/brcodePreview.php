<?php

namespace StarkBank\PaymentPreview;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class BrcodePreview extends SubResource
{

    public $status;
    public $name;
    public $taxId;
    public $bankCode;
    public $accountType;
    public $allowChange;
    public $amount;
    public $nominalAmount;
    public $interestAmount;
    public $fineAmount;
    public $reductionAmount;
    public $discountAmount;
    public $reconciliationId;

    /**
    # BrcodePreview object

    A BrcodePreview is used to get information from a BR Code you received before confirming the payment.

    ## Attributes (return-only):
        - status [string]: Payment status. ex: "active", "paid", "canceled" or "unknown"
        - name [string]: Payment receiver name. ex: "Tony Stark"
        - taxId [string]: Payment receiver tax ID. ex: "012.345.678-90"
        - bankCode [string]: Payment receiver bank code. ex: "20018183"
        - accountType [string]: Payment receiver account type. ex: "checking"
        - allowChange [bool]: If True, the payment is able to receive amounts that are different from the nominal one. ex: True or False
        - amount [integer]: Value in cents that this payment is expecting to receive. If 0, any value is accepted. ex: 123 (= R$1,23)
        - nominalAmount [integer]: Original value in cents that this payment was expecting to receive without the discounts, fines, etc.. If 0, any value is accepted. ex: 123 (= R$1,23)
        - interestAmount [integer]: Current interest value in cents that this payment is charging. If 0, any value is accepted. ex: 123 (= R$1,23)
        - fineAmount [integer]: Current fine value in cents that this payment is charging. ex: 123 (= R$1,23)
        - reductionAmount [integer]: Current value reduction value in cents that this payment is expecting. ex: 123 (= R$1,23)
        - discountAmount [integer]: Current discount value in cents that this payment is expecting. ex: 123 (= R$1,23)
        - reconciliationId [string]: Reconciliation ID linked to this payment. ex: "txId", "payment-123"
     */
    function __construct(array $params)
    {
        $this->status = Checks::checkParam($params, "status");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->bankCode = Checks::checkParam($params, "bankCode");
        $this->accountType = Checks::checkParam($params, "accountType");
        $this->allowChange = Checks::checkParam($params, "allowChange");
        $this->amount = Checks::checkParam($params, "amount");
        $this->nominalAmount = Checks::checkParam($params, "nominalAmount");
        $this->interestAmount = Checks::checkParam($params, "interestAmount");
        $this->fineAmount = Checks::checkParam($params, "fineAmount");
        $this->reductionAmount = Checks::checkParam($params, "reductionAmount");
        $this->discountAmount = Checks::checkParam($params, "discountAmount");
        $this->reconciliationId = Checks::checkParam($params, "reconciliationId");

        Checks::checkParams($params);
    }

    static function resource()
    {
        $preview = function ($array) {
            return new BrcodePreview($array);
        };
        return [
            "name" => "BrcodePreview",
            "maker" => $preview,
        ];
    }
}
