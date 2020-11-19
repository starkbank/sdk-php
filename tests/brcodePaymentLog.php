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
}

echo "\n\nBrcodePaymentLog:";

$test = new TestBrcodePaymentLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
