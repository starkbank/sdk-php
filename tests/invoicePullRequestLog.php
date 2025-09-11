<?php

namespace Test\InvoicePullRequestLog;
use \Exception;
use StarkBank\InvoicePullRequest\Log;


class TestInvoicePullRequestLog
{
    public function queryAndGet()
    {
        $requestLogs = iterator_to_array(Log::query(["limit" => 2, "types" => ["created"]]));

        if (count($requestLogs) != 2) {
            throw new Exception("failed");
        }

        foreach($requestLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $requestLog = Log::get($requestLogs[0]->id);

        if ($requestLogs[0]->id != $requestLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $invoiceLog) {
                if (in_array($invoiceLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $invoiceLog->id);
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

echo "\n\nInvoicePullRequestLog:";

$test = new TestInvoicePullRequestLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
