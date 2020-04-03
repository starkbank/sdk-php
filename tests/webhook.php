<?php

namespace Test\Webhook;

use \Exception;
use Test\TestUser;
use StarkBank\Webhook;


class Test
{
    public function createAndDelete()
    {
        $user = TestUser::project();

        $webhook = Test::example();

        $webhook = Webhook::create($user, $webhook->url, $webhook->subscriptions);

        $deleted = Webhook::delete($user, $webhook->id);

        if (is_null($webhook->id) | $webhook->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $user = TestUser::project();

        $webhooks = iterator_to_array(Webhook::query($user));

        if (count($webhooks) == 0) {
            throw new Exception("failed");
        }

        $webhook = Webhook::get($user, $webhooks[0]->id);

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
