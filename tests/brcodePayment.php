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
        $payments = [self::example(), self::example()];

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

    public function cancel()
    {
        $payments = iterator_to_array(BrcodePayment::query(["limit" => 100, "status" => "created"]));
        if (count($payments) == 0)
            throw new Exception("failed");
        if (count($payments) > 100)
            throw new Exception("failed");
        $payment = $payments[array_rand($payments, 1)];

        $updateBrcodePayment = BrcodePayment::update($payment->id, ["status" => "canceled"]);

        if ($updateBrcodePayment->status != "canceled") {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = BrcodePayment::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $brcodePayment) {
                if (in_array($brcodePayment->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $brcodePayment->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function example()
    {
        return new BrcodePayment([
			"brcode" => "00020126390014br.gov.bcb.pix0117valid@sandbox.com52040000530398654041.005802BR5908Jon Snow6009Sao Paulo62110507sdktest63046109",
			"taxId" => "20.018.183/0001-80",
			"description" => "Tony Stark's Suit",
			"amount" => 100,
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

echo "\n\t- cancel";
$test->cancel();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
