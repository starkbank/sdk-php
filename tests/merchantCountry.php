<?php

namespace Test\MerchantCountry;
use \Exception;
use StarkBank\MerchantCountry;


class TestMerchantCountry
{
    public function get()
    {
        $countries = MerchantCountry::query([
            "search" => "brazil"
        ]);

        foreach ($countries as $country) {
            if (is_null($country->code)) {
                throw new Exception("failed");
            }
        }
    }
}

echo "\n\nMerchantCountry:";

$test = new TestMerchantCountry();

echo "\n\t- query";
$test->get();
echo " - OK";
