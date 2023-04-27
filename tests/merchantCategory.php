<?php

namespace Test\MerchantCategory;
use \Exception;
use StarkBank\MerchantCategory;


class TestMerchantCategory
{
    public function get()
    {
        $categories = MerchantCategory::query([
            "search" => "food"
        ]);

        foreach ($categories as $category) {
            if (is_null($category->type)) {
                throw new Exception("failed");
            }
        }
    }
}

echo "\n\nMerchantCategory:";

$test = new TestMerchantCategory();

echo "\n\t- query";
$test->get();
echo " - OK";
