<?php

namespace Test\MerchantPurchase;
use \Exception;
use StarkBank\MerchantPurchase;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestMerchantPurchase
{
    public function create()
    {
        $cardIds = MerchantPurchase::query(["limit" => 1, "status" => "confirmed"]);
        foreach ($cardIds as $id) {
            $merchantPurchaseExample = self::generateExampleMerchantPurchaseJson($id->cardId);
            $merchantPurchase = MerchantPurchase::create($merchantPurchaseExample);
            if (is_null($merchantPurchase->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function query()
    {
        $purchases = iterator_to_array(MerchantPurchase::query(["limit" => 5, "before" => new DateTime("now")]));
        $index = 0;

        foreach ($purchases as $purchase) {
            $testSession = MerchantPurchase::get($purchase->id);
            
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
            list($page, $cursor) = MerchantPurchase::page($options = ["limit" => 5, "cursor" => $cursor]);
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

    public function update()
    {
        $purchases = iterator_to_array(MerchantPurchase::query(["limit" => 5, "status"=> "paid", "before" => new DateTime("now")]));

        foreach ($purchases as $purchase) {
            if ($purchase->amount != 0) {
                $merchantPurchase = MerchantPurchase::update($purchase->id, "reversed", 0);
                if (is_null($merchantPurchase->id)) {
                    throw new Exception("failed");
                }
            }
        }
    }

    public static function generateExampleMerchantPurchaseJson($cardId) 
    {
        return new MerchantPurchase([
            "amount" => 10000,
            "installmentCount" => 5,
            "cardId" => $cardId,
            "fundingType" => "credit",
            "challengeMode" => "disabled",
            "billingCity" => "Sao Paulo",
            "billingCountryCode" => "BRA",
            "billingStateCode" => "SP",
            "billingStreetLine1" => "Rua do Holder Name, 123",
            "billingStreetLine2" => "1 andar",
            "billingZipCode" => "11111-111",
            "holderEmail" => "holdeName@email.com",
            "holderPhone" => "11111111111",
            "metadata" => [
                "userAgent" => "userAgent",
                "userIp" => "255.255.255.255",
                "language" => "pt-BR",
                "timezoneOffset" => 3,
                "extraData" => "extraData"
            ],
            "tags" => [
                "teste"
            ]
        ]);
    }
}

echo "\n\MerchantPurchase:";

$test = new TestMerchantPurchase();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->query();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";

echo "\n\t- update";
$test->update();
echo " - OK";
