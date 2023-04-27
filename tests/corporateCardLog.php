<?php

namespace Test\CorporateCardLog;
use \Exception;
use StarkBank\CorporateCard\Log;


class TestCorporateCardLog
{
    public function query()
    {
        $logs = Log::query(["limit" => 10]);

        foreach ($logs as $log) {
            if (is_null($log->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function get()
    {
        $log = iterator_to_array(Log::query(["limit" => 1]))[0];
        $log = Log::get($log->id);

        if (!is_string($log->id)) {
            throw new Exception("failed");
        }
    }

    public function page()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $card) {
                print_r($card);
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

echo "\n\nCorporateCardLog:";

$test = new TestCorporateCardLog();

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- get";
$test->get();
echo " - OK";

echo "\n\t- page";
$test->page();
echo " - OK";
