<?php

namespace Test\InvoiceLog;
use \Exception;
use StarkBank\Invoice\Log;


class TestInvoiceLog
{
    public function queryAndGet()
    {
        $invoiceLogs = iterator_to_array(Log::query(["limit" => 10, "types" => ["created"]]));

        if (count($invoiceLogs) != 10) {
            throw new Exception("failed");
        }

        foreach($invoiceLogs as $log) {
            if ($log->type != "created") {
                throw new Exception("failed");
            }
        }

        $invoiceLog = Log::get($invoiceLogs[0]->id);

        if ($invoiceLogs[0]->id != $invoiceLog->id) {
            throw new Exception("failed");
        }
    }

    public function getLogPdf()
    {
        $invoiceLogs = iterator_to_array(Log::query(["limit" => 1, "types" => "reversed"]));        
        $pdf = Log::pdf($invoiceLogs[0]->id);

        $fp = fopen('invoice-log.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }
}

echo "\n\nInvoiceLog:";

$test = new TestInvoiceLog();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get log pdf";
$test->getLogPdf();
echo " - OK";
