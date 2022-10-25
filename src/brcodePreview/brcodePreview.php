<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;


class BrcodePreview extends Resource
{
    /**
    # BrcodePreview object

    A BrcodePreview is used to get information from a BR Code you received to check the informations before paying it.

    ## Attributes (return-only):
        - status [string]: Payment status. ex: "active", "paid", "canceled" or "unknown"
        - name [string]: Payment receiver name. ex: "Tony Stark"
        - taxId [string]: Payment receiver tax ID. ex: "012.345.678-90"
        - bankCode [string]: Payment receiver bank code. ex: "20018183"
        - branchCode [string]: Payment receiver branch code. ex: "0001"
        - accountNumber [string]: Payment receiver account number. ex: "1234567"
        - accountType [string]: Payment receiver account type. ex: "checking"
        - allowChange [bool]: If True, the payment is able to receive amounts that are different from the nominal one. ex: True or False
        - amount [integer]: Value in cents that this payment is expecting to receive. If 0, any value is accepted. ex: 123 (= R$1,23)
        - reconciliationId [string]: Reconciliation ID linked to this payment. ex: "txId", "payment-123"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->status = Checks::checkParam($params, "status");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->bankCode = Checks::checkParam($params, "bankCode");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->accountNumber = Checks::checkParam($params, "accountNumber");
        $this->accountType = Checks::checkParam($params, "accountType");
        $this->allowChange = Checks::checkParam($params, "allowChange");
        $this->amount = Checks::checkParam($params, "amount");
        $this->reconciliationId = Checks::checkParam($params, "reconciliationId");

        Checks::checkParams($params);
    }

    /**
     * @deprecated
    # Retrieve BrcodePreviews

    Process BR Codes before creating BrcodePayments

    ## Parameters (optional):
        - brcodes [array of strings]: List of brcodes to preview. ex: ["00020126580014br.gov.bcb.pix0136a629532e-7693-4846-852d-1bbff817b5a8520400005303986540510.005802BR5908T'Challa6009Sao Paulo62090505123456304B14A"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of BrcodePreview objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        trigger_error('BrcodePreview is deprecated, use PaymentPreview instead', E_USER_DEPRECATED);
        $brcodes = Checks::checkParam($options, "brcodes");
        if (!is_null($brcodes)) {
            $urlsafe = [];
            foreach($brcodes as $brcode)
                array_push($urlsafe, urlencode($brcode));
            $brcodes = $urlsafe;
        }
        $options["brcodes"] = $brcodes;
        return Rest::getList($user, BrcodePreview::resource(), $options);
    }

    private static function resource()
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
