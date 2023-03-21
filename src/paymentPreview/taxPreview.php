<?php

namespace StarkBank\PaymentPreview;

use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class TaxPreview extends SubResource
{

    public $amount;
    public $name;
    public $description;
    public $line;
    public $barCode;

    /**
    # TaxPreview object

    A TaxPreview is used to get information from a Tax Payment you received before confirming the payment.

    ## Attributes (return-only):
        - amount [integer]: final amount to be paid. ex: 23456 (= R$ 234.56)
        - name [string]: beneficiary full name. ex: "Iron Throne"
        - description [string]: tax payment description. ex: "ISS Payment - Iron Throne"
        - line [string]: Number sequence that identifies the payment. ex: "85660000006 6 67940064007 5 41190025511 7 00010601813 8"
        - barCode [string]: Bar code number that identifies the payment. ex: "85660000006679400640074119002551100010601813"
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
            return new TaxPreview($array);
        };
        return [
            "name" => "TaxPreview",
            "maker" => $preview,
        ];
    }
}
