<?php

namespace Test\MerchantSessionLog;
use \Exception;
use StarkBank\MerchantSession\Log;


class TestMerchantSessionLog
{
    public function queryAndGet()
    {
        $logs = iterator_to_array(Log::query(["limit" => 10]));
        
        foreach ($logs as $log) {
            if (is_null($log->id)) {
                throw new Exception("failed");
            }

            $getLog = Log::get($log->id);
            if ($log->id != $getLog->id) {
                throw new Exception("failed");
            }
        }
    }

    public function page()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page(["limit" => 5, "cursor" => $cursor]);
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
}

echo "\n\MerchantSessionLog:";

$test = new TestMerchantSessionLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- page";
$test->page();
echo " - OK";
