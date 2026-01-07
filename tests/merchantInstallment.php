<?php

namespace Test\MerchantInstallment;
use \Exception;
use StarkBank\MerchantInstallment;
use StarkBank\Event;
use \DateTime;


class TestMerchantInstallment
{

    public function queryAndGet()
    {
        $installments = iterator_to_array(MerchantInstallment::query(["limit" => 5, "before" => new DateTime("now")]));

        foreach ($installments as $installment) {
            $getInstallment = MerchantInstallment::get($installment->id);
            
            if ($installment->id != $getInstallment->id) {
                throw new Exception("failed");
            }
        }

        if (count($installments) != 5) {
            throw new Exception("failed");
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

    public function parseMerchantInstallmentEvent() {
        $content = "{\"event\": {\"created\": \"2025-10-14T20:45:55.357314+00:00\", \"id\": \"6007986671583232\", \"log\": {\"created\": \"2025-10-14T20:45:53.697574+00:00\", \"description\": \"Installment created with a nominal amount of R$ 10,00.\", \"errors\": [], \"id\": \"6655369694674944\", \"installment\": {\"amount\": 1000, \"created\": \"2025-10-14T20:45:53.610204+00:00\", \"due\": \"2025-11-17T03:00:00+00:00\", \"fee\": 24, \"fundingType\": \"credit\", \"id\": \"5529469787832320\", \"isProtected\": false, \"network\": \"diners\", \"nominalDue\": \"2025-11-17T03:00:00+00:00\", \"purchaseId\": \"5022074565296128\", \"status\": \"created\", \"tags\": [\"yourtags\"], \"transactionIds\": [], \"updated\": \"2025-10-14T20:45:53.697608+00:00\"}, \"type\": \"created\"}, \"subscription\": \"merchant-installment\", \"workspaceId\": \"6341320293482496\"}}";
        $validSignature = "MEUCIQD8azmNmlsG+baoqAh4xmX9G538cGrDhTT0VvU85rz8bwIgBEdIr6SSW/7vfxOv4ZET+LsHU0TpNpTrGmBxjs8Y5o0=";

        $event = Event::parse($content, $validSignature);

        if ($event->log->installment->id != "5529469787832320") {
            throw new Exception("failed");
        }
    }
}

echo "\n\MerchantInstallment:";

$test = new TestMerchantInstallment();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";

echo "\n\t- parse event";
$test->parseMerchantInstallmentEvent();
echo " - OK";
