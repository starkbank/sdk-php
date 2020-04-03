<?php

namespace Test\BoletoPaymentLog;
use \Exception;
use StarkBank\BoletoPaymentLog;


class Test
{
    public function queryAndGet()
    {
        $paymentLogs = iterator_to_array(BoletoPaymentLog::query(["limit" => 10, "types" => ["created"]]));

        if (count($paymentLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($paymentLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $paymentLog = BoletoPaymentLog::get($paymentLogs[0]->id);

        if ($paymentLogs[0]->id != $paymentLog->id) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nBoletoPaymentLog:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
