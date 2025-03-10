<?php

namespace Test\MerchantInstallment;
use \Exception;
use StarkBank\MerchantInstallment;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestMerchantInstallment
{

    public function query()
    {
        $purchases = iterator_to_array(MerchantInstallment::query(["limit" => 5, "before" => new DateTime("now")]));
        $index = 0;

        foreach ($purchases as $purchase) {
            $testSession = MerchantInstallment::get($purchase->id);
            
            if ($purchases[$index]->id != $purchase->id) {
                throw new Exception("failed");
            }
            $index = $index + 1;
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = MerchantInstallment::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $purchase) {
                if (in_array($purchase->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $purchase->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }
}

echo "\n\MerchantInstallment:";

$test = new TestMerchantInstallment();

echo "\n\t- query and get";
$test->query();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
