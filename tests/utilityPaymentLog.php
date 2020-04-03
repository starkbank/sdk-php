<?php

namespace Test\UtilityPaymentLog;
use \Exception;
use Test\TestUser;
use StarkBank\UtilityPaymentLog;


class Test
{
    public function queryAndGet()
    {
        $user = TestUser::project();

        $paymentLogs = iterator_to_array(UtilityPaymentLog::query($user, ["limit" => 10, "types" => ["created"]]));

        if (count($paymentLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($paymentLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $paymentLog = UtilityPaymentLog::get($user, $paymentLogs[0]->id);

        if ($paymentLogs[0]->id != $paymentLog->id) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nUtilityPaymentLog:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
