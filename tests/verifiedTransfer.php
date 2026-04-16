<?php

namespace Test\VerifiedTransfer;
use \Exception;
use StarkBank\VerifiedTransfer;
use StarkBank\VerifiedAccount;
use StarkBank\Transfer\Rule;
use Test\VerifiedAccount\TestVerifiedAccount as AccountTest;


class TestVerifiedTransfer
{
    public function create()
    {  
        $accountId = VerifiedAccount::create([AccountTest::example()])[0]->id;
        $transfers = VerifiedTransfer::create([TestVerifiedTransfer::example($accountId)]);

        foreach ($transfers as $transfer) {
            if (is_null($transfer->id)) {
                throw new Exception("failed");
            }
        }
    }

    public static function example($accountId)
    {
        return new VerifiedTransfer([
            "amount" => 1000,
            "accountId" => $accountId,
            "description" => "Test description",
            "displayDescription" => "Payment for service #1234",
            "tags" => ["verified-transfer-test"],
            "rules" => [
                new Rule([
                    "key" => "resendingLimit",
                    "value" => 5
                ])
            ]
        ]);
    }
}

echo "\n\nVerifiedTransfer:";

$test = new TestVerifiedTransfer();

echo "\n\t- create";
$test->create();
echo " - OK";
