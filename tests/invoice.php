<?php

namespace Test\Invoice;
use \Exception;
use StarkBank\Invoice;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestInvoice
{
    public function createAndCancel()
    {
        $invoices = self::examples();

        $invoices = Invoice::create($invoices);

        foreach ($invoices as $invoice) {
            if (is_null($invoice->id)) {
                throw new Exception("failed");
            }
        }

        $invoice = $invoices[0];
        $updateInvoice = Invoice::update($invoice->id, ["status" => "canceled"]);
        if ($updateInvoice->status != "canceled") {
            throw new Exception("failed");
        }
    }

    public function queryGetGetPdfAndGetQrcode()
    {
        $invoices = iterator_to_array(Invoice::query(["limit" => 10, "before" => new DateTime("now")]));

        if (count($invoices) != 10) {
            throw new Exception("failed");
        }

        $invoice = Invoice::get($invoices[0]->id);

        if ($invoices[0]->id != $invoice->id) {
            throw new Exception("failed");
        }

        $pdf = Invoice::pdf($invoice->id);

        $fp = fopen('invoice.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);

        $qrcode = Invoice::qrcode($invoice->id);

        $fp = fopen('invoice-qrcode.png', 'w');
        fwrite($fp, $qrcode);
        fclose($fp);
    }

    public function queryAndGetPayment()
    {
        $invoice = iterator_to_array(Invoice::query(["limit" => 1, "status" => "paid"]))[0];
        $payment = Invoice::payment($invoice->id);

        if (is_null($payment->bankCode)) {
            throw new Exception("failed");
        }
    }

    public function updateAmount()
    {
        $invoices = iterator_to_array(Invoice::query(["limit" => 1, "status" => "created"]));

        if (count($invoices) != 1) {
            throw new Exception("failed");
        }

        foreach ($invoices as $invoice) {
            $updateInvoice = Invoice::update($invoice->id, ["amount" => 4321]);

            if ($updateInvoice->amount != 4321) {
                throw new Exception("failed");
            }
        }
    }

    public function updateDue()
    {
        $invoices = iterator_to_array(Invoice::query(["limit" => 1, "status" => "created"]));

        if (count($invoices) != 1) {
            throw new Exception("failed");
        }

        foreach ($invoices as $invoice) {
            $updateInvoice = Invoice::update($invoice->id, ["due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P5D")))]);

            if ($updateInvoice->due == $invoice->due) {
                throw new Exception("failed");
            }
        }
    }

    public function updateExpiration()
    {
        $invoices = iterator_to_array(Invoice::query(["limit" => 1, "status" => "created"]));

        if (count($invoices) != 1) {
            throw new Exception("failed");
        }

        foreach ($invoices as $invoice) {
            $updateInvoice = Invoice::update($invoice->id, ["expiration" => 123456789]);

            if ($updateInvoice->expiration->s != (new \DateInterval("PT0H0M123456789S"))->s) {
                throw new Exception("failed");
            }
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Invoice::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $invoice) {
                if (in_array($invoice->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $invoice->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function examples()
    {
        return [
            new Invoice([
                "amount" => 400000,
                "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P5D")))->setTime(0,0,0,0),
                "taxId" => "012.345.678-90",
                "name" => "João Rosá",
                "expiration" => 123456789,
                "fine" => 2.5,
                "interest" => 1.3,
                "discounts" => [
                    [
                        "percentage" => 5,
                        "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P1D")))->setTime(0,0,0,0)
                    ],
                    [
                        "percentage" => 3,
                        "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P2D")))->setTime(0,0,0,0)
                    ]
                ],
                "tags" => [
                    'War supply',
                    'Invoice #1234'
                ],
                "descriptions" => [
                    [
                        "key" => "product A",
                        "value" => "big"
                    ],
                    [
                        "key" => "product B",
                        "value" => "medium"
                    ],
                    [
                        "key" => "product C",
                        "value" => "small"
                    ]
                ],
            ]),
            new Invoice([
                "amount" => 400000,
                "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P5D")))->format("Y-m-d\TH:i:s.uP"),
                "taxId" => "012.345.678-90",
                "name" => "Mr Meeseks",
                "expiration" => new DateInterval("P0Y0M1DT1H0M10S"),
                "fine" => 2.5,
                "interest" => 1.3,
                "discounts" => [
                    [
                        "percentage" => 5,
                        "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P1D")))->format("Y-m-d\TH:i:s.uP")
                    ],
                ],
                "tags" => [
                    'War supply',
                    'Invoice #1234'
                ],
                "descriptions" => [
                    [
                        "key" => "product A",
                        "value" => "big"
                    ],
                ],
            ])
        ];
    }
}

echo "\n\nInvoice:";

$test = new TestInvoice();

echo "\n\t- create and cancel";
$test->createAndCancel();
echo " - OK";

echo "\n\t- query and get";
$test->queryGetGetPdfAndGetQrcode();
echo " - OK";

echo "\n\t- get payment";
$test->queryAndGetPayment();
echo " - OK";

echo "\n\t- update amount";
$test->updateAmount();
echo " - OK";

echo "\n\t- update due";
$test->updateDue();
echo " - OK";

echo "\n\t- update expiration";
$test->updateExpiration();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
