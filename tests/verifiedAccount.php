<?php

namespace Test\VerifiedAccount;
use \Exception;
use StarkBank\VerifiedAccount;


class TestVerifiedAccount
{
    public function create()
    {
        $verifiedAccount = VerifiedAccount::create([TestVerifiedAccount::example()])[0];

        if (is_null($verifiedAccount->id)) {
            throw new Exception("failed");
        }
    }

    public function createAndCancel()
    {
        $verifiedAccount = VerifiedAccount::create([TestVerifiedAccount::example()])[0];
        $canceledAccount = VerifiedAccount::cancel($verifiedAccount->id);

        if ($verifiedAccount->id != $canceledAccount->id) {
            throw new Exception("failed");
        }
        if ($canceledAccount->status != "canceled") {
            throw new Exception("failed");
        }
    }

    public function createAndGet()
    {
        $verifiedAccount = VerifiedAccount::create([TestVerifiedAccount::example()])[0];
        $fetchedAccount = VerifiedAccount::get($verifiedAccount->id);

        if ($verifiedAccount->id != $fetchedAccount->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $verifiedAccounts = iterator_to_array(VerifiedAccount::query(["limit" => 10]));

        if (count($verifiedAccounts) != 10) {
            throw new Exception("failed");
        }

        $verifiedAccount = VerifiedAccount::get($verifiedAccounts[0]->id);

        if ($verifiedAccounts[0]->id != $verifiedAccount->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i = 0; $i < 2; $i++) {
            list($page, $cursor) = VerifiedAccount::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $verifiedAccount) {
                if (in_array($verifiedAccount->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $verifiedAccount->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function example()
    {
        return new VerifiedAccount([
            "taxId" => "012.345.678-90",
            "bankCode" => "20018183",
            "branchCode" => "0001",
            "name" => "Anthony Edward Stark",
            "number" => "876543-2",
            "type" => "checking",
            "tags" => ["test", "php"]
        ]);
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

echo "\n\t- create and get";
$test->createAndGet();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
