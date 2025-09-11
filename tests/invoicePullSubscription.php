<?php

namespace Test\InvoicePullSubscription;
use \Exception;
use StarkBank\InvoicePullSubscription;
use \DateTime;
use \DateTimeZone;
use \DateInterval;

class TestInvoicePullSubscription
{
    public function createAndCancel()
    {
        $subscription = self::examples();

        $subscriptions = InvoicePullSubscription::create($subscription);

        foreach ($subscriptions as $subscription) {
            if (is_null($subscription->id)) {
                throw new Exception("failed");
            }
        }
    }

    public static function examples()
    {
        return [
            new InvoicePullSubscription([
                    "amount" => 0,
                    "amountMinLimit" => 5000,
                    "data" => [
                        "amount" => 400000,
                        "due" => ((new DateTime("now"))->add(new DateInterval("P30D")))->format("Y-m-d\TH:i:s.uP"),
                        "fine" => 2.5,
                        "interest" => 1.3
                    ],
                    "displayDescription" => "Dragon Travel Fare",
                    "due" => ((new DateTime("now"))->add(new DateInterval("P35D"))->format("Y-m-d")),
                    "end" => ((new DateTime("now"))->add(new DateInterval("P35D"))->format("Y-m-d")),
                    "externalId" => "php-" . $uuid = mt_rand(0, 0xffffffff),
                    "interval" => "month",
                    "name" => "John Snow",
                    "pullMode" => "manual",
                    "pullRetryLimit" => 3,
                    "referenceCode" => "contract-12345",
                    "start" => ((new DateTime("now"))->add(new DateInterval("P5D"))->format("Y-m-d")),
                    "tags" => [],
                    "taxId" => "012.345.678-90",
                    "type" => "paymentAndOrQrcode",
            ])
        ];
    }

    public function queryAndGetPayment()
    {
        $requests = iterator_to_array(InvoicePullSubscription::query(["limit" => 1, "status" => "active"]))[0];
    }

        public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = InvoicePullSubscription::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $subscriptions) {
                if (in_array($subscriptions->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $subscriptions->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nInvoicePullSubscription:";

$test = new TestInvoicePullSubscription();

echo "\n\t- create and cancel";
$test->createAndCancel();
echo " - OK";

echo "\n\t- get payment";
$test->queryAndGetPayment();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
