<?php

namespace Test\InvoicePullSubscriptionLog;
use \Exception;
use StarkBank\InvoicePullSubscription\Log;


class TestInvoicePullSubscriptionLog
{
    public function queryAndGet()
    {
        $subscriptionLogs = iterator_to_array(Log::query(["limit" => 2, "types" => ["created"]]));

        if (count($subscriptionLogs) != 2) {
            throw new Exception("failed");
        }

        foreach($subscriptionLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $subscriptionLog = Log::get($subscriptionLogs[0]->id);

        if ($subscriptionLogs[0]->id != $subscriptionLog->id) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Log::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $subscriptionLog) {
                if (in_array($subscriptionLog->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $subscriptionLog->id);
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

echo "\n\nInvoicePullRequest:";

$test = new TestInvoicePullSubscriptionLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
