<?php

namespace Test\BoletoPayment;
use \Exception;
use Test\TestUser;
use StarkBank\BoletoPayment;
use \DateTime;
use \DateInterval;


class Test
{
    public function createAndDelete()
    {
        $user = TestUser::project();

        $payments = [Test::example()];

        $payment = BoletoPayment::create($user, $payments)[0];

        $deleted = BoletoPayment::delete($user, $payment->id);

        if (is_null($payment->id) | $payment->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $user = TestUser::project();

        $payments = iterator_to_array(BoletoPayment::query($user, ["limit" => 10]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $payment = BoletoPayment::get($user, $payments[0]->id);

        if ($payments[0]->id != $payment->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGetPdf()
    {
        $user = TestUser::project();

        $payments = iterator_to_array(BoletoPayment::query($user, ["limit" => 10]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $pdf = BoletoPayment::pdf($user, $payments[0]->id);

        $fp = fopen('boletoPayment.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    private function example()
    {
        return new BoletoPayment([
            "line" => "34191.09008 61713.957308 71444.640008 2 83430000984732",
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
