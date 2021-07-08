<?php

namespace Test\BoletoPayment;
use \Exception;
use StarkBank\BoletoPayment;
use \DateTime;
use \DateInterval;


class TestBoletoPayment
{
    public function createAndDelete()
    {
        $payments = [self::example()];

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

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = BoletoPayment::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $boletoPayment) {
                if (in_array($boletoPayment->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $boletoPayment->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function example($schedule = true)
    {
        $params = [
            "line" => join("", ["34191.09008 61713.957308 71444.640008 2 934300", sprintf("%08d", random_int(0, 100000000))]),
            "description" => "loading a random account",
            "taxId" => "20.018.183/0001-80",
        ];
        if($schedule)
            $params["scheduled"] = (new DateTime("now"))->add(new DateInterval("P1D"));

        return new BoletoPayment($params);
    }
}

echo "\n\nBoletoPayment:";

$test = new TestBoletoPayment();

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
