<?php

namespace Test\Event;

use \Exception;
use Test\TestUser;
use StarkBank\Event;
use StarkBank\Exception\InvalidSignatureError;


class Test
{

    const CONTENT = '{"event": {"log": {"transfer": {"status": "processing", "updated": "2020-04-03T13:20:33.485644+00:00", "fee": 160, "name": "Lawrence James", "accountNumber": "10000-0", "id": "5107489032896512", "tags": [], "taxId": "91.642.017/0001-06", "created": "2020-04-03T13:20:32.530367+00:00", "amount": 2, "transactionIds": ["6547649079541760"], "bankCode": "01", "branchCode": "0001"}, "errors": [], "type": "sending", "id": "5648419829841920", "created": "2020-04-03T13:20:33.164373+00:00"}, "subscription": "transfer", "id": "6234355449987072", "created": "2020-04-03T13:20:40.784479+00:00"}}';
    const VALID_SIGNATURE = "MEYCIQCmFCAn2Z+6qEHmf8paI08Ee5ZJ9+KvLWSS3ddp8+RF3AIhALlK7ltfRvMCXhjS7cy8SPlcSlpQtjBxmhN6ClFC0Tv6";
    const INVALID_SIGNATURE = "MEUCIQDOpo1j+V40DNZK2URL2786UQK/8mDXon9ayEd8U0/l7AIgYXtIZJBTs8zCRR3vmted6Ehz/qfw1GRut/eYyvf1yOk=";

    public function queryAndDelete()
    {
        $user = TestUser::project();

        $events = iterator_to_array(Event::query($user, ["limit" => 10, "isDelivered" => true]));

        if (count($events) != 10) {
            throw new Exception("failed");
        }

        foreach($events as $event) {
            if ($event->isDelivered != true) {
                throw new Exception("failed");
            }
        }

        $deleted = Event::delete($user, $events[0]->id);

        if (is_null($events[0]->id) | $events[0]->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryGetAndUpdate()
    {
        $user = TestUser::project();

        $events = iterator_to_array(Event::query($user, ["limit" => 1, "isDelivered" => false]));

        if (count($events) != 1) {
            throw new Exception("failed");
        }

        $event = Event::get($user, $events[0]->id);

        if ($events[0]->id != $event->id) {
            throw new Exception("failed");
        }

        $event = Event::update($user, $event->id, ["isDelivered" => true]);

        print_r($event);
    }

    public function parseRight()
    {
        $user = TestUser::project();

        $event_1 = Event::parse($user, self::CONTENT, self::VALID_SIGNATURE);
        $event_2 = Event::parse($user, self::CONTENT, self::VALID_SIGNATURE); // using cache

        if ($event_1 != $event_2) {
            throw new Exception("failed");
        }
    }

    public function parseWrong()
    {
        $user = TestUser::project();

        $error = false;
        try {
            $event = Event::parse($user, self::CONTENT, self::INVALID_SIGNATURE);
        } catch (InvalidSignatureError $e) {
            $error = true;
        }

        if (!$error) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nEvent:";

$test = new Test();

echo "\n\t- query and delete";
$test->queryAndDelete();
echo " - OK";

echo "\n\t- query, get and update";
$test->queryGetAndUpdate();
echo " - OK";

echo "\n\t- parse right";
$test->parseRight();
echo " - OK";

echo "\n\t- parse wrong";
$test->parseWrong();
echo " - OK";
