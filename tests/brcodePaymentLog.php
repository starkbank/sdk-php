<?php

namespace Test\BrcodePaymentLog;
use \Exception;
use StarkBank\BrcodePayment\Log;


class TestBrcodePaymentLog
{
    public function queryAndGet()
    {
        $paymentLogs = iterator_to_array(Log::query(["limit" => 2, "types" => ["success"]]));
        
        if (count($paymentLogs) != 2) {
            throw new Exception("failed");
        }

        foreach($paymentLogs as $log) {
            if ($log->type != "success") {
                throw new Exception("failed");
            }
        }

        $paymentLog = Log::get($paymentLogs[0]->id);

        if ($paymentLogs[0]->id != $paymentLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $brcodePaymentLog) {
                if (in_array($brcodePaymentLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $brcodePaymentLog->id);
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

echo "\n\nBrcodePaymentLog:";

$test = new TestBrcodePaymentLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
