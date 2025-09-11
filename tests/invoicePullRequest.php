<?php

namespace Test\InvoicePullRequest;
use \Exception;
use StarkBank\InvoicePullRequest;
use \DateTime;
use \DateTimeZone;
use \DateInterval;
use StarkBank\InvoicePullSubscription;
use StarkBank\Invoice;



class TestInvoicePullRequest
{
    public function create()
    {

        $invoices = self::exampleInvoices();
        $invoices = Invoice::create($invoices);

        foreach ($invoices as $invoice) {
            if (is_null($invoice->id)) {
                throw new Exception("failed");
            }
        }

        $subscriptions = self::examplePullSubscriptions();

        $subscriptions = InvoicePullSubscription::create($subscriptions);

        foreach ($subscriptions as $subscription) {
            $requests = self::examplePullRequests($subscription->id, $invoices[0]->id);
            $requests = InvoicePullRequest::create($requests);
            if (is_null($subscription->id)) {
                throw new Exception("failed");
            }
        }
    }

    public static function examplePullRequests($subscriptionId = null, $invoiceId = null)
    {
        return [
            new InvoicePullRequest([
                "attemptType" => "default",
                "due" => ((new DateTime("now"))->add(new DateInterval("P5D")))->format("Y-m-d\TH:i:s.uP"),
                "invoiceId" => $invoiceId,
                "subscriptionId" => $subscriptionId,
                "tags" => []
            ])
        ];
    }

    public static function examplePullSubscriptions()
    {
        return [
            new InvoicePullSubscription([
                    "amount" => 0,
                    "amountMinLimit" => 5000,
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
                    "type" => "qrcode",
            ])
        ];
    }

    public static function exampleInvoices() 
    {
        return [
            new Invoice([
                "amount" => 12,
                "name" => "John Snow",
                "taxId" => "012.345.678-90"
            ])
        ];
    }

    public function queryAndGetRequest()
    {
        $requests = iterator_to_array(InvoicePullRequest::query(["limit" => 1, "status" => "pending"]))[0];
    }

        public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = InvoicePullRequest::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $requests) {
                if (in_array($requests->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $requests->id);
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

echo "\n\nInvoicePullRequest:";

$test = new TestInvoicePullRequest();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- get payment";
$test->queryAndGetRequest();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
