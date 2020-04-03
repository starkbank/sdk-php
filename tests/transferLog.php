<?php

namespace Test\TransferLog;
use \Exception;
use StarkBank\TransferLog;


class Test
{
    public function queryAndGet()
    {
        $transferLogs = iterator_to_array(TransferLog::query(["limit" => 10, "types" => ["created"]]));

        if (count($transferLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($transferLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $transferLog = TransferLog::get($transferLogs[0]->id);

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
