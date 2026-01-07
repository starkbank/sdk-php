<?php

namespace Test\MerchantCard;
use \Exception;
use StarkBank\MerchantCard;
use StarkBank\Event;
use \DateTime;


class TestMerchantCard
{

    public function queryAndGet()
    {
        $cards = iterator_to_array(MerchantCard::query(["limit" => 5, "before" => new DateTime("now")]));

        foreach ($cards as $card) {
            $getCard = MerchantCard::get($card->id);
            if ($getCard->id != $card->id) {
                throw new Exception("failed");
            }
        }

        if (count($cards) != 5) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = MerchantCard::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $card) {
                if (in_array($card->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $card->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public function parseMerchantCardEvent() {
        $content = "{\"event\": {\"created\": \"2025-10-16T19:33:33.957137+00:00\", \"id\": \"4824539051589632\", \"log\": {\"card\": {\"created\": \"2025-10-16T19:33:33.667314+00:00\", \"ending\": \"9733\", \"expiration\": \"2035-02-01T02:59:59.999999+00:00\", \"fundingType\": \"debit\", \"holderName\": \"Kaladin Stormblessed\", \"id\": \"5596404638547968\", \"network\": \"mastercard\", \"status\": \"active\", \"tags\": [], \"updated\": \"2025-10-16T19:33:33.672774+00:00\"}, \"created\": \"2025-10-16T19:33:33.672780+00:00\", \"errors\": [], \"id\": \"6722304545390592\", \"type\": \"active\"}, \"subscription\": \"merchant-card\", \"workspaceId\": \"6314371953197056\"}}";
        $validSignature = "MEQCIFj9Vg+QkC+oXYXirS0j2ZoLFChRw7khSWrfpOud7/q7AiBPD7aPWYbpT6t3qSfyj2ol8b0cFQwtUHXu0iBkp4zGTQ==";

        $event = Event::parse($content, $validSignature);

        if ($event->log->card->id != "5596404638547968") {
            throw new Exception("failed");
        }
    }
}

echo "\n\MerchantCard:";

$test = new TestMerchantCard();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";

echo "\n\t- parse event";
$test->parseMerchantCardEvent();
echo " - OK";
