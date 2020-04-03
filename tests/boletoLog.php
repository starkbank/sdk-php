<?php

namespace Test\BoletoLog;
use \Exception;
use Test\TestUser;
use StarkBank\BoletoLog;
use \DateTime;
use \DateInterval;


class Test
{
    public function queryAndGet()
    {
        $user = TestUser::project();

        $boletoLogs = iterator_to_array(BoletoLog::query($user, ["limit" => 10, "types" => ["created"]]));

        if (count($boletoLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($boletoLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $boletoLog = BoletoLog::get($user, $boletoLogs[0]->id);

        if ($boletoLogs[0]->id != $boletoLog->id) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nBoletoLog:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
