<?php

namespace Test\Webhook;

use \Exception;
use StarkBank\Webhook;


class TestWebhook
{
    public function createAndDelete()
    {
        $webhook = self::example();

        $webhook = Webhook::create(["url" => $webhook->url, "subscriptions" => $webhook->subscriptions]);

        $deleted = Webhook::delete($webhook->id);

        if (is_null($webhook->id) | $webhook->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $webhooks = iterator_to_array(Webhook::query());

        if (count($webhooks) > 1) {
            $webhook = Webhook::get($webhooks[0]->id);

            if ($webhooks[0]->id != $webhook->id) {
                throw new Exception("failed");
            }
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Webhook::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $webhook) {
                if (in_array($webhook->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $webhook->id);
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
        $uuid = mt_rand(0, 0xffffffff);

        return new Webhook([
            "url" => "https://webhook.site/" . $uuid,
            "subscriptions" => ["transfer", "boleto", "boleto-payment", "boleto-holmes", "invoice"],
        ]);
    }
}

echo "\n\nWebhook:";

$test = new TestWebhook();

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
