<?php

namespace Test\MerchantCard;
use \Exception;
use StarkBank\MerchantCard;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestMerchantCard
{

    public function query()
    {
        $cards = iterator_to_array(MerchantCard::query(["limit" => 5, "before" => new DateTime("now")]));
        $index = 0;

        foreach ($cards as $card) {
            $testSession = MerchantCard::get($card->id);
            if ($cards[$index]->id != $card->id) {
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
            list($page, $cursor) = MerchantCard::page($options = ["limit" => 5, "cursor" => $cursor]);
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
}

echo "\n\MerchantCard:";

$test = new TestMerchantCard();

echo "\n\t- query and get";
$test->query();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
