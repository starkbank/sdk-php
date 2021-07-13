<?php

namespace Test\TaxPayment;
use \Exception;
use StarkBank\TaxPayment;
use \DateTime;
use \DateInterval;


class TestTaxPayment
{
    public function query()
    {
        $payments = iterator_to_array(TaxPayment::query(["limit" => 10]));
        if (count($payments) != 10) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = TaxPayment::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $taxPayment) {
                if (in_array($taxPayment->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $taxPayment->id);
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
        $payment = iterator_to_array(TaxPayment::query(["limit" => 1]))[0];
        $payment = TaxPayment::get($id = $payment->id);
        if ($payment == null) {
            throw new Exception("failed");
        }
    }

    public function pdf()
    {
        $payment = iterator_to_array(TaxPayment::query(["limit" => 1, "status" => "processing"]))[0];
        $pdf = TaxPayment::pdf($id = $payment->id);

        $fp = fopen('taxPayment.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    public function delete()
    {
        $payment = [self::example()];
        $payment = TaxPayment::create($payment)[0];
        $payment = TaxPayment::delete($payment->id);
        if ($payment == null) {
            throw new Exception("failed");
        }
    }
    
    public static function example()
    {
        return new TaxPayment([
            "barCode" => "85660000000935803280074119002551100010601813",
            "description" => "33ff6f90de30c7f60526dbe6a1bb3d0cd1f751c89a2fc9a8aad087d4efdc0bce",
            "tags" => ["test2"],
            "scheduled" => (new DateTime("now"))->add(new DateInterval("P1D"))->format("Y-m-d")
        ]);
    }
}

echo "\n\nTaxPayment:";

$test = new TestTaxPayment();

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- getPage";
$test->getPage();
echo " - OK";

echo "\n\t- get";
$test->get();
echo " - OK";

echo "\n\t- pdf";
$test->pdf();
echo " - OK";

echo "\n\t- delete";
$test->delete();
echo " - OK";
