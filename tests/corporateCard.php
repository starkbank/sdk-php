<?php

namespace Test\CorporateCard;
use \Exception;
use StarkBank\CorporateCard;
use StarkBank\CorporateHolder;


class TestCorporateCard
{

    public function postAndCancel()
    {
        $card = CorporateCard::create(self::example(), ["expand" => "securityCode"]);
        print_r($card);
        if ($card->securityCode == "***") {
            throw new Exception("failed");
        }
        $cardId = $card->id;
        $card = CorporateCard::update($cardId, ["displayName" => "Updated Name"]);
        print_r($card);
        if ($card->displayName != "Updated Name") {
            throw new Exception("failed");
        }
        $card = CorporateCard::cancel($cardId);
        print_r($card);
        if ($card->status != "canceled") {
            throw new Exception("failed");
        }
    }

    public function query()
    {
        $cards = CorporateCard::query(["limit" => 10, "expand" => ["rules"]]);
        foreach ($cards as $card) {
            if (is_null($card->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function page()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = CorporateCard::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $card) {
                if (is_null($card->id) or in_array($card->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $card->id);
            }
            if ($cursor == null) {
                break;
            }
        }
    }

    public function get()
    {
        $card = iterator_to_array(CorporateCard::query(["limit" => 1, "expand" => ["rules"]]))[0];
        $card = CorporateCard::get($card->id);

        if (!is_string($card->id)) {
            throw new Exception("failed");
        }
    }

    public function update()
    {
        $card = CorporateCard::create(self::example(), ["expand" => "securityCode"]);
        if (is_null($card->id)) {
            throw new Exception("failed");
        }
        if ($card->status != "active") {
            throw new Exception("failed");
        }    
        $updatedCard = CorporateCard::update($card->id, ["status" => "blocked"]);
        if ($updatedCard->status != "blocked") {
            throw new Exception("failed");
        }    
    }

    public static function example()
    {
        $holders = CorporateHolder::create([
            new CorporateHolder([
                "name" => "Holder Test" . substr(uniqid(), 0, 10),
                "tags" => ["Traveler Employee"],
                'permissions' => [
                    new CorporateHolder\Permission([
                        'ownerType' => 'project',
                        'ownerId' => $_SERVER["SANDBOX_BANK_PROJECT_ID"],
                    ])
                ]
            ])
        ]);
        $holderId = $holders[0]->id;

        $params = [
            "holderId" => $holderId
        ];
        return new CorporateCard($params);
    }
}

echo "\n\nCorporateCard:";

$test = new TestCorporateCard();

echo "\n\t- post and cancel";
$test->postAndCancel();
echo " - OK";

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- page";
$test->page();
echo " - OK";

echo "\n\t- get";
$test->get();
echo " - OK";

echo "\n\t- update";
$test->update();
echo " - OK";
