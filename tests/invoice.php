<?php

namespace Test\Invoice;
use \Exception;
use StarkBank\Invoice;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestInvoice
{
    public function create()
    {
        $invoices = [self::example()];

        $invoice = Invoice::create($invoices)[0];

        if (is_null($invoice->id)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $invoices = iterator_to_array(Invoice::query(["limit" => 10, "before" => new DateTime("now")]));

        if (count($invoices) != 10) {
            throw new Exception("failed");
        }

        $invoice = Invoice::get($invoices[0]->id);

        if ($invoices[0]->id != $invoice->id) {
            throw new Exception("failed");
        }
    }

    public function updateStatus()
    {
        $invoices = iterator_to_array(Invoice::query(["limit" => 1, "status" => "created"]));

        if (count($invoices) != 1) {
            throw new Exception("failed");
        }

        foreach ($invoices as $invoice) {
            $updateInvoice = Invoice::update($invoice->id, ["status" => "canceled"]);

            if ($updateInvoice->status != "canceled") {
                throw new Exception("failed");
            }
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

            if ($updateInvoice->expiration != 123456789) {
                throw new Exception("failed");
            }
        }
    }

    public static function example()
    {
        return new Invoice([
            "amount" => 400000,
            "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P5D"))),
            "taxId" => "012.345.678-90",
            "name" => "Mr Meeseks",

            "expiration" => 123456789,
            "fine" => 2.5,
            "interest" => 1.3,
            "discounts" => [
                [
                    "percentage" => 5,
                    "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P1D")))
                ],
                [
                    "percentage" => 3,
                    "due" => ((new DateTime("now", new DateTimeZone('Europe/London')))->add(new DateInterval("P2D")))
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
        ]);
    }
}

echo "\n\nInvoice:";

$test = new TestInvoice();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- update status";
$test->updateStatus();
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