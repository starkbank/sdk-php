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

        if (count($webhooks) == 0) {
            throw new Exception("failed");
        }

        $webhook = Webhook::get($webhooks[0]->id);

        if ($webhooks[0]->id != $webhook->id) {
            throw new Exception("failed");
        }
    }

    private static function example()
    {
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

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
