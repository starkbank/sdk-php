<?php

namespace Test\UtilityPayment;
use \Exception;
use Test\TestUser;
use StarkBank\UtilityPayment;
use \DateTime;
use \DateInterval;


class Test
{
    public function createAndDelete()
    {
        $user = TestUser::project();

        $payments = [Test::example()];

        $payment = UtilityPayment::create($user, $payments)[0];

        $deleted = UtilityPayment::delete($user, $payment->id);

        if (is_null($payment->id) | $payment->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $user = TestUser::project();

        $payments = iterator_to_array(UtilityPayment::query($user, ["limit" => 10]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $payment = UtilityPayment::get($user, $payments[0]->id);

        if ($payments[0]->id != $payment->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGetPdf()
    {
        $user = TestUser::project();

        $payments = iterator_to_array(UtilityPayment::query($user, ["limit" => 10, "status" => "success"]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $pdf = UtilityPayment::pdf($user, $payments[0]->id);

        $fp = fopen('utilityPayment.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    private function example()
    {
        return new UtilityPayment([
            "barCode" => "83660000001084301380074119002551100010601813",
            "scheduled" => (new DateTime("now"))->add(new DateInterval("P1D")),
            "description" => "paying the bills",
        ]);
    }
}

echo "\n\nUtilityPayment:";

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
