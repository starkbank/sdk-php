<?php

namespace Test\BoletoPaymentLog;
use \Exception;
use StarkBank\BoletoPayment\Log;


class TestBoletoPaymentLog
{
    public function queryAndGet()
    {
        $paymentLogs = iterator_to_array(Log::query(["limit" => 10, "types" => ["created"]]));

        if (count($paymentLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($paymentLogs as $log) {
            if ($log->type != "created") {
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
            foreach ($page as $boletoPaymentLog) {
                if (in_array($boletoPaymentLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $boletoPaymentLog->id);
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

echo "\n\nBoletoPaymentLog:";

$test = new TestBoletoPaymentLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
