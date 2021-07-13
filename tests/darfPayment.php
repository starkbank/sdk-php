<?php

namespace Test\DarfPayment;
use \Exception;
use StarkBank\DarfPayment;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestDarfPayment
{
    public function query()
    {
        $payments = iterator_to_array(DarfPayment::query(["limit" => 10]));
        if (count($payments) != 10) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = DarfPayment::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $darfPayment) {
                if (in_array($darfPayment->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $darfPayment->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public function get()
    {
        $payment = iterator_to_array(DarfPayment::query(["limit" => 1]))[0];
        $payment = DarfPayment::get($id = $payment->id);
        if ($payment == null) {
            throw new Exception("failed");
        }
    }

    public function pdf()
    {
        $payment = iterator_to_array(DarfPayment::query(["limit" => 1, "status" => "success"]))[0];
        $pdf = DarfPayment::pdf($id = $payment->id);

        $fp = fopen('darf-payment.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    public function createAndDelete()
    {
        $payment = [self::example()];
        $payment = DarfPayment::create($payment)[0];
        $payment = DarfPayment::delete($id = $payment->id);
        if ($payment == null) {
            throw new Exception("failed");
        }
    }

    public static function example()
    {
        return new DarfPayment([
            "description" => "Darf Payment Example",
            "tags" => ["Darf"],
            "due" => (new DateTime("now"))->add(new DateInterval("P1D"))->format("Y-m-d"),
            "competence" => "2020-04-03",
            "fineAmount" => 100,
            "interestAmount" => 100,
            "nominalAmount" => 1000,
            "revenueCode" => "0201",
            "taxId" => "45678350005",
            "scheduled" => (new DateTime("now"))->add(new DateInterval("P1D"))->format("Y-m-d")
        ]);
    }
}

echo "\n\nDarfPayment:";

$test = new TestDarfPayment();

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- get Page";
$test->getPage();
echo " - OK";

echo "\n\t- get";
$test->get();
echo " - OK";

echo "\n\t- pdf";
$test->pdf();
echo " - OK";

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";
