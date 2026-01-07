<?php

namespace Test\MerchantPurchase;
use \Exception;
use StarkBank\MerchantPurchase;
use StarkBank\Event;
use \DateTime;


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

    public function queryAndGet()
    {
        $purchases = iterator_to_array(MerchantPurchase::query(["limit" => 5, "before" => new DateTime("now")]));

        foreach ($purchases as $purchase) {
            $getPurchase = MerchantPurchase::get($purchase->id);
            
            if ($getPurchase->id != $purchase->id) {
                throw new Exception("failed");
            }
        }

        if (count($purchases) != 5) {
            throw new Exception("failed");
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

    public function parseMerchantPurchaseEvent() {
        $content = "{\"event\": {\"created\": \"2025-10-14T20:46:00.300285+00:00\", \"id\": \"5454126009810944\", \"log\": {\"created\": \"2025-10-14T20:45:58.347434+00:00\", \"description\": \"Purchase approved.\", \"errors\": [], \"id\": \"5669171517980672\", \"purchase\": {\"amount\": 1000, \"billingCity\": \"\", \"billingCountryCode\": \"\", \"billingStateCode\": \"\", \"billingStreetLine1\": \"\", \"billingStreetLine2\": \"\", \"billingZipCode\": \"\", \"cardEnding\": \"1625\", \"cardId\": \"5113758527520768\", \"challengeMode\": \"disabled\", \"challengeUrl\": \"\", \"created\": \"2025-10-14T20:45:56.936238+00:00\", \"currencyCode\": \"BRL\", \"endToEndId\": \"f02b36d6-7872-4a81-b2ce-cd1a0b89da69\", \"fee\": 0, \"fundingType\": \"credit\", \"holderEmail\": \"\", \"holderName\": \"Margaery Tyrell\", \"holderPhone\": \"\", \"id\": \"5903823029665792\", \"installmentCount\": 1, \"metadata\": {}, \"network\": \"diners\", \"softDescriptor\": \"\", \"source\": \"merchant-session/5047053356892160\", \"status\": \"approved\", \"tags\": [\"yourtags\"], \"transactionIds\": [], \"updated\": \"2025-10-14T20:45:58.347478+00:00\"}, \"transactionId\": \"\", \"type\": \"approved\"}, \"subscription\": \"merchant-purchase\", \"workspaceId\": \"6341320293482496\"}}";
        $validSignature = "MEYCIQCQ7cDmcaRxVpEwTbmGCiTKE6RiWHgtXvVs3sSITgF4wwIhAInXEEeRKoi3UuOm4BexLAG05RQiSG5iSZJW3UZ3jBE3";

        $event = Event::parse($content, $validSignature);

        if ($event->log->purchase->id != "5903823029665792") {
            throw new Exception("failed");
        }
    }
}

echo "\n\MerchantPurchase:";

$test = new TestMerchantPurchase();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";

echo "\n\t- update";
$test->update();
echo " - OK";

echo "\n\t- parse event";
$test->parseMerchantPurchaseEvent();
echo " - OK";
