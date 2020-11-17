<?php

namespace Test\BrcodePayment;
use \Exception;
use StarkBank\BrcodePayment;
use \DateTime;
use \DateInterval;


class TestBrcodePayment
{
    public function create()
    {
        $payments = [self::example()];

        $payment = BrcodePayment::create($payments)[0];

        if (is_null($payment->id)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $payments = iterator_to_array(BrcodePayment::query(["limit" => 10, "before" => new DateTime("now")]));

        if (count($payments) != 10) {
            throw new Exception("failed");
        }

        $payment = BrcodePayment::get($payments[0]->id);

        if ($payments[0]->id != $payment->id) {
            throw new Exception("failed");
        }
    }

    public static function example()
    {
        return new BrcodePayment([
			"brcode" => "00020126580014br.gov.bcb.pix0136a629532e-7693-4846-852d-1bbff817b5a8520400005303986540510.005802BR5908T'Challa6009Sao Paulo62090505123456304B14A",
			"taxId" => "20.018.183/0001-80",
			"description" => "Tony Stark's Suit",
			"amount" => 7654321,
			"scheduled" => (new DateTime("now"))->add(new DateInterval("P5D")),
			"tags" => ["Stark", "Suit"]
        ]);
    }
}

echo "\n\nBrcodePayment:";

$test = new TestBrcodePayment();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
