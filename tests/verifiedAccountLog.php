<?php

namespace Test\VerifiedAccount;
use \Exception;
use StarkBank\VerifiedAccount\Log;


class TestVerifiedAccountLog
{
    public function testVerifiedAccountLogQuery()
    {
        $logs = iterator_to_array(Log::query(["limit" => 10]));

        if (count($logs) != 10) {
            throw new Exception("failed");
        }
    }

    public function testVerifiedAccountLogInfoGet()
    {
        $logs = iterator_to_array(Log::query(["limit" => 10]));

        if (count($logs) != 10) {
            throw new Exception("failed");
        }

        $log = Log::get($logs[0]->id);

        if ($logs[0]->id != $log->id) {
            throw new Exception("failed");
        }
    }

    public function testVerifiedAccountLogGetPage()
    {
        $ids = [];
        $cursor = null;
        for ($i = 0; $i < 2; $i++) {
            list($page, $cursor) = Log::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $log) {
                if (in_array($log->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $log->id);
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

echo "\n\nVerifiedAccount\Log:";

$test = new TestVerifiedAccountLog();

echo "\n\t- TestVerifiedAccountLogQuery";
$test->testVerifiedAccountLogQuery();
echo " - OK";

echo "\n\t- TestVerifiedAccountLogInfoGet";
$test->testVerifiedAccountLogInfoGet();
echo " - OK";

echo "\n\t- TestVerifiedAccountLogGetPage";
$test->testVerifiedAccountLogGetPage();
echo " - OK";
