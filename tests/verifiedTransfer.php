<?php

namespace Test\VerifiedTransfer;
use \Exception;
use StarkBank\Transfer;
use StarkBank\VerifiedAccount;
use StarkBank\VerifiedTransfer;
use StarkBank\Transfer\Rule;
use Test\VerifiedAccount\TestVerifiedAccount;


class TestVerifiedTransfer
{
    public function create()
    {
        $account = VerifiedAccount::create([TestVerifiedAccount::examples()[0]])[0];

        $transfer = VerifiedTransfer::create([TestVerifiedTransfer::example($account->id)])[0];

        if (is_null($transfer->id)) {
            throw new Exception("failed");
        }

        $getTransfer = Transfer::get($transfer->id);

        if ($getTransfer->id != $transfer->id) {
            throw new Exception("failed");
        }
    }

    public static function example($accountId)
    {
        return new VerifiedTransfer([
            "amount" => 10,
            "accountId" => $accountId,
            "externalId" => "php-" . $uuid = mt_rand(0, 0xffffffff),
            "tags" => ["iron", "suit"],
            "description" => "Test description",
            "displayDescription" => "Test displayDescription",
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
