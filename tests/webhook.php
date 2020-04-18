<?php

namespace Test\Webhook;

use \Exception;
use StarkBank\Webhook;


class Test
{
    public function createAndDelete()
    {
        $webhook = Test::example();

        $webhook = Webhook::create(["url" => $webhook->url, "subscriptions" => $webhook->subscriptions]);

        $deleted = Webhook::delete($webhook->id);

        if (is_null($webhook->id) | $webhook->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $webhooks = iterator_to_array(Webhook::query());

        if (count($webhooks) == 0) {
            throw new Exception("failed");
        }

        $webhook = Webhook::get($webhooks[0]->id);

        if ($webhooks[0]->id != $webhook->id) {
            throw new Exception("failed");
        }
    }

    private function example()
    {
        return new Webhook([
            "url" => "https://webhook.site/60e9c18e-4b5c-4369-bda1-ab5fcd8e1b29",
            "subscriptions" => ["transfer", "boleto", "boleto-payment"],
        ]);
    }
}

echo "\n\nWebhook:";

$test = new Test();

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
