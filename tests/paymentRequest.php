<?php

namespace Test\PaymentRequest;
use \Exception;
use StarkBank\PaymentRequest;
use StarkBank\Transaction;
use Test\BoletoPayment\TestBoletoPayment;
use Test\Transaction\TestTransaction;
use Test\Transfer\TestTransfer;
use Test\UtilityPayment\TestUtilityPayment;
use \DateTime;
use \DateInterval;


class TestPaymentRequest
{

    public function create()
    {
        $requests = [];
        for($i = 0; $i < 10; $i++)
            array_push($requests, self::example());
        $received = PaymentRequest::create($requests);
        foreach ($received as $item) {
            if(is_null($item->id))
                throw new Exception("Failed");
        }
    }

    public function query()
    {
        $requests = iterator_to_array(PaymentRequest::query([
            "centerId" => $_SERVER["SANDBOX_CENTER_ID"],
            "limit" => 10,
            "before" => new DateTime("now")
        ]));

        if (count($requests) != 10)
            throw new Exception("failed");
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = PaymentRequest::page($options = ["centerId" => $_SERVER["SANDBOX_CENTER_ID"], "limit" => 2, "cursor" => $cursor]);
            foreach ($page as $paymentRequest) {
                if (in_array($paymentRequest->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $paymentRequest->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) == 0) {
            throw new Exception("failed");
        }
    }

    private static function example()
    {
        $payment = self::createPayment();
        $params = [
            "centerId" => $_SERVER["SANDBOX_CENTER_ID"],
            "payment" => $payment,
        ];
        if(!($payment instanceof Transaction))
        {
            $days = rand(1, 7);
            $params["due"] = (new DateTime("now"))->add(new DateInterval("P" . $days . "D"));
        }
        return new PaymentRequest($params);
    }

    private static function createPayment()
    {
        $random = rand(0, 3);
        switch ($random)
        {
            case 0:
                return TestTransfer::example(false);
            case 1:
                return TestTransaction::example(uniqid());
            case 2:
                return TestBoletoPayment::example(false);
            case 3:
                return TestUtilityPayment::example(false);
            default:
                throw new Exception("Bad random number.");
        }
    }
}

echo "\n\nPayment Request:";

$test = new TestPaymentRequest();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query";
$test->query();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
