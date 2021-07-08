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

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $boletoHolmesLog) {
                if (in_array($boletoHolmesLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $boletoHolmesLog->id);
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

echo "\n\nBoletoHolmesLog:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
