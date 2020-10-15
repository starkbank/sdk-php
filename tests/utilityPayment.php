<?php

namespace Test\UtilityPayment;
use \Exception;
use StarkBank\UtilityPayment;
use \DateTime;
use \DateInterval;


class TestUtilityPayment
{
    public function createAndDelete()
    {
        $payments = [TestUtilityPayment::example()];

        $payment = UtilityPayment::create($payments)[0];

        $deleted = UtilityPayment::delete($payment->id);

        if (is_null($payment->id) | $payment->id != $deleted->id)
            throw new Exception("failed");
    }

    public function queryAndGet()
    {
        $payments = iterator_to_array(UtilityPayment::query(["limit" => 10]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $payment = UtilityPayment::get($payments[0]->id);

        if ($payments[0]->id != $payment->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGetPdf()
    {
        $payments = iterator_to_array(UtilityPayment::query(["limit" => 10, "status" => "success"]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $pdf = UtilityPayment::pdf($payments[0]->id);

        $fp = fopen('utilityPayment.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    public static function example($schedule = true)
    {
        $params = [
            "barCode" => "8366".sprintf("%011d", random_int(100, 100000000))."01380074119002551100010601813",
            "description" => "paying the bills",
        ];

        if($schedule)
            $params["scheduled"] = (new DateTime("now"))->add(new DateInterval("P1D"));

        return new UtilityPayment($params);
    }
}

echo "\n\nUtilityPayment:";

$test = new TestUtilityPayment();

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";
