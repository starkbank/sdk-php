<?php

namespace Test\CorporateHolder;
use \Exception;
use StarkBank\CorporateHolder;
use StarkBank\MerchantCategory;
use StarkBank\MerchantCountry;
use StarkBank\CardMethod;
use StarkBank\CorporateRule;

class TestCorporateHolder
{
    public function query()
    {
        $holders = CorporateHolder::query(["limit" => 10, "expand" => ["rules"]]);

        foreach ($holders as $holder) {
            if (is_null($holder->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function page()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = CorporateHolder::page(["limit" => 2, "expand" => ["rules"], "cursor" => $cursor]);
            foreach ($page as $holder) {
                if (is_null($holder->id) or in_array($holder->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $holder->id);
            }
            if ($cursor == null) {
                break;
            }
        }
    }

    public function get()
    {
        $holder = iterator_to_array(CorporateHolder::query(["limit" => 1]))[0];
        $holder = CorporateHolder::get($holder->id, ["expand" => "rules"]);

        if (!is_string($holder->id)) {
            throw new Exception("failed");
        }
    }

    public function postPatchAndCancel()
    {
        $holders = CorporateHolder::create(TestCorporateHolder::generateExampleHoldersJson(1), ["expand" => "rules"]);
        $holderId = $holders[0]->id;
        $holder = CorporateHolder::update($holderId, ["name" => "Updated Name"]);
        if ($holder->name != "Updated Name") {
            throw new Exception("failed");
        }
        $holder = CorporateHolder::cancel($holderId);
        if ($holder->status != "canceled") {
            throw new Exception("failed");
        }
    }

    public static function generateExampleHoldersJson($n=1)
    {
        $holders = [];
        foreach (range(1, $n) as $index) {
            $holder = new CorporateHolder([
                "name" => "Holder Test" . substr(uniqid(), 0, 10),
                "tags" => ["Traveler Employee"],
                'permissions' => [
                    new CorporateHolder\Permission([
                        'ownerType' => 'project',
                        'ownerId' => $_SERVER["SANDBOX_BANK_PROJECT_ID"],
                    ])
                ]
            ]);
            array_push($holders, $holder);
        }
        return $holders;
    }
}

echo "\n\nCorporateHolder:";

$test = new TestCorporateHolder();

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- page";
$test->page();
echo " - OK";

echo "\n\t- get";
$test->get();
echo " - OK";

echo "\n\t- post, patch and cancel";
$test->postPatchAndCancel();
echo " - OK";
