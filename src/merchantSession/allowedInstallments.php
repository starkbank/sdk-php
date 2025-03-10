<?php

namespace StarkBank\MerchantSession;
use StarkCore\Utils\API;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class AllowedInstallment extends SubResource
{

    public $totalAmount;
    public $count;

    function __construct(array $params)
    {
        $this->totalAmount = Checks::checkParam($params, "totalAmount");
        $this->count = Checks::checkParam($params, "count");

        Checks::checkParams($params);
    }

    public static function parseAllowedInstallment($installments) {
        if (is_null($installments)){
            return null;
        }
        $parsedInstallments = [];
        foreach($installments as $installment) {
            if($installment instanceof AllowedInstallment) {
                array_push($parsedInstallments, $installment);
                continue;
            }
            $parsedInstallment = function ($array) {
                $installmentMaker = function ($array) {
                    return new AllowedInstallment($array);
                };
                return API::fromApiJson($installmentMaker, $array);
            };
            array_push($parsedInstallments, $parsedInstallment($installment));
        }    
        return $parsedInstallments;
    }
}
