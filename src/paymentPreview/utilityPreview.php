<?php

namespace StarkBank\PaymentPreview;

use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class UtilityPreview extends SubResource
{

    public $amount;
    public $name;
    public $description;
    public $line;
    public $barCode;

    /**
    # UtilityPreview object

    A UtilityPreview is used to get information from a Utility Payment you received before confirming the payment.

    ## Attributes (return-only):
        - amount [integer]: final amount to be paid. ex: 23456 (= R$ 234.56)
        - name [string]: beneficiary full name. ex: "Iron Throne"
        - description [string]: utility payment description. ex: "Utility Payment - Light Company"
        - line [string]: Number sequence that identifies the payment. ex: "82660000002 8 44361143007 7 41190025511 7 00010601813 8"
        - barCode [string]: Bar code number that identifies the payment. ex: "82660000002443611430074119002551100010601813"
     */
    function __construct(array $params)
    {
        $this->amount = Checks::checkParam($params, "amount");
        $this->name = Checks::checkParam($params, "name");
        $this->description = Checks::checkParam($params, "description");
        $this->line = Checks::checkParam($params, "line");
        $this->barCode = Checks::checkParam($params, "barCode");

        Checks::checkParams($params);
    }

    static function resource()
    {
        $preview = function ($array) {
            return new UtilityPreview($array);
        };
        return [
            "name" => "UtilityPreview",
            "maker" => $preview,
        ];
    }
}
