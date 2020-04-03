<?php

namespace Test\TransferLog;
use \Exception;
use Test\TestUser;
use StarkBank\TransferLog;


class Test
{
    public function queryAndGet()
    {
        $user = TestUser::project();

        $transferLogs = iterator_to_array(TransferLog::query($user, ["limit" => 10, "types" => ["created"]]));

        if (count($transferLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($transferLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $transferLog = TransferLog::get($user, $transferLogs[0]->id);

        if ($transferLogs[0]->id != $transferLog->id) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nTransferLog:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
