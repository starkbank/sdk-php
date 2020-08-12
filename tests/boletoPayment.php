<?php

namespace Test\BoletoPayment;
use \Exception;
use StarkBank\BoletoPayment;
use \DateTime;
use \DateInterval;


class Test
{
    public function createAndDelete()
    {
        $payments = [Test::example()];

        $payment = BoletoPayment::create($payments)[0];

        $deleted = BoletoPayment::delete($payment->id);

        if (is_null($payment->id) | $payment->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $payments = iterator_to_array(BoletoPayment::query(["limit" => 10]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $payment = BoletoPayment::get($payments[0]->id);

        if ($payments[0]->id != $payment->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGetPdf()
    {
        $payments = iterator_to_array(BoletoPayment::query(["limit" => 10, "status" => "success"]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $pdf = BoletoPayment::pdf($payments[0]->id);

        $fp = fopen('boletoPayment.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    private static function example()
    {
        return new BoletoPayment([
            "line" => join("", ["34191.09008 61713.957308 71444.640008 2 934300", sprintf("%08d", random_int(0, 100000000))]),
            "scheduled" => (new DateTime("now"))->add(new DateInterval("P1D")),
            "description" => "loading a random account",
            "taxId" => "20.018.183/0001-80",
        ]);
    }
}

echo "\n\nBoletoPayment:";

$test = new Test();

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";
