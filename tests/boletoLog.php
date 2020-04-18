<?php

namespace Test\BoletoLog;
use \Exception;
use StarkBank\Boleto\Log;


class Test
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
}

echo "\n\nBoletoLog:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
