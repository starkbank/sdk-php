<?php

namespace Test\CorporateInvoice;
use \Exception;
use StarkBank\CorporateInvoice;


class TestCorporateInvoice
{
    public function query()
    {
        $invoices = CorporateInvoice::query(["limit" => 10]);

        foreach ($invoices as $invoice) {
            if (is_null($invoice->id)) {
                throw new Exception("failed");
            }
        }
    }

    public function create()
    {
        $invoice = self::generateExampleInvoice();
        $invoice = CorporateInvoice::create($invoice);

        if (is_null($invoice->id)) {
            throw new Exception("failed");
        }
    }

    public static function generateExampleInvoice()
    {
        return new CorporateInvoice([
            "amount" => 400000,
        ]);
    }
}

echo "\n\nCorporateInvoice:";

$test = new TestCorporateInvoice();

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- create";
$test->create();
echo " - OK";
