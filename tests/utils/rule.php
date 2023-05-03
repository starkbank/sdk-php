<?php

namespace Test\Utils;
use StarkBank\CardMethod;
use StarkBank\MerchantCountry;
use StarkBank\MerchantCategory;
use StarkBank\CorporateRule;


class TestCorporateRule {
    public static function generateExampleRulesJson($n=1)
    {
        $rules = [];

        $intervals = ["day", "week", "month", "instant"];
        $currencies = ["BRL", "USD"];
    
        foreach (range(1, $n) as $index) {
            $rule = new CorporateRule([
                "name" => "Example Rule",
                "interval" => $intervals[array_rand($intervals)],
                "amount" => random_int(1000, 100000),
                "currencyCode" => $currencies[array_rand($currencies)],
                "categories" => [new MerchantCategory([
                    "code" => "veterinaryServices"
                ])],
                "countries" => [new MerchantCountry([
                    "code" => "BRA"
                ])],
                "methods" => [new CardMethod([
                    "code" => "token"
                ])],
            ]);
            array_push($rules, $rule);
        }
        return $rules;
    }
}
