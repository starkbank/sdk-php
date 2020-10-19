<?php

namespace Test\BoletoHolmesLog;
use \Exception;
use StarkBank\BoletoHolmes\Log;


class Test
{
    public function queryAndGet()
    {
        $holmesLog = iterator_to_array(Log::query(["limit" => 10, "types" => ["solving"]]));

        if (count($holmesLog) != 10) {
            throw new Exception("failed");
        }

        foreach($holmesLog as $log) {
            if ($log->type != "solving") {
                throw new Exception("failed");
            }
        }

        $sherlockLog = Log::get($holmesLog[0]->id);

        if ($holmesLog[0]->id != $sherlockLog->id) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nBoletoHolmesLog:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
