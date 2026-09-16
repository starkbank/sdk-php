<?php

namespace Test\VerifiedAccount;
use \Exception;
use StarkBank\VerifiedAccount;


class TestVerifiedAccount
{
    public function create()
    {
        $accounts = VerifiedAccount::create(TestVerifiedAccount::examples());

        foreach ($accounts as $account) {
            if (is_null($account->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function createAndCancel()
    {
        $account = VerifiedAccount::create([TestVerifiedAccount::examples()[1]])[0];
        $canceledAccount = VerifiedAccount::cancel($account->id);

        if ($account->id != $canceledAccount->id) {
            throw new Exception("failed");
        }
        if ($canceledAccount->status != "canceled") {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $accounts = iterator_to_array(VerifiedAccount::query(["limit" => 10]));

        if (count($accounts) != 10) {
            throw new Exception("failed");
        }

        $account = VerifiedAccount::get($accounts[0]->id);

        if ($accounts[0]->id != $account->id) {
            throw new Exception("failed");
        }
    }

    public function queryIds()
    {
        $accounts = iterator_to_array(VerifiedAccount::query(["limit" => 10]));
        $accountIdsExpected = array();
        for ($i=0; $i<sizeof($accounts); $i++){
            array_push($accountIdsExpected, $accounts[$i]->id);
        }

        $accountsResult = iterator_to_array(VerifiedAccount::query(["ids" => $accountIdsExpected]));
        $accountIdsResult = array();
        for ($i=0; $i<sizeof($accountsResult); $i++){
            array_push($accountIdsResult, $accountsResult[$i]->id);
        }

        sort($accountIdsExpected);
        sort($accountIdsResult);

        if ($accountIdsExpected != $accountIdsResult) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) {
            list($page, $cursor) = VerifiedAccount::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $account) {
                if (in_array($account->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $account->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function examples()
    {
        return [
            new VerifiedAccount([
                "taxId" => "911.544.440-66",
                "name" => "Daenerys Targaryen Stormborn",
                "bankCode" => "341",
                "branchCode" => "2201",
                "number" => "76543-8",
                "type" => "checking",
                "tags" => ["verified-account-test"],
            ]),
            new VerifiedAccount([
                "taxId" => "039.946.040-36",
                "keyId" => "arya.stark@starkbank.com",
                "tags" => ["verified-account-test"],
            ])
        ];
    }
}

echo "\n\nVerifiedAccount:";

$test = new TestVerifiedAccount();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- create and cancel";
$test->createAndCancel();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query ids";
$test->queryIds();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
