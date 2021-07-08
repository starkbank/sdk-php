<?php

namespace Test\BoletoLog;
use \Exception;
use StarkBank\Boleto\Log;


class TestBoletoLog
{
    public function queryAndGet()
    {
        $boletoLogs = iterator_to_array(Log::query(["limit" => 10, "types" => ["created"]]));

        if (count($boletoLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($boletoLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $boletoLog = Log::get($boletoLogs[0]->id);

        if ($boletoLogs[0]->id != $boletoLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $boletoLog) {
                if (in_array($boletoLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $boletoLog->id);
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

echo "\n\nBoletoLog:";

$test = new TestBoletoLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
