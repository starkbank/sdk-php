<?php

namespace Test\Event;

use \Exception;
use StarkBank\Event;
use StarkBank\Event\Attempt;
use StarkBank\Error\InvalidSignatureError;


class TestEvent
{

    const CONTENT = '{"event": {"log": {"transfer": {"status": "processing", "updated": "2020-04-03T13:20:33.485644+00:00", "fee": 160, "name": "Lawrence James", "accountNumber": "10000-0", "id": "5107489032896512", "tags": [], "taxId": "91.642.017/0001-06", "created": "2020-04-03T13:20:32.530367+00:00", "amount": 2, "transactionIds": ["6547649079541760"], "bankCode": "01", "branchCode": "0001"}, "errors": [], "type": "sending", "id": "5648419829841920", "created": "2020-04-03T13:20:33.164373+00:00"}, "subscription": "transfer", "id": "6234355449987072", "created": "2020-04-03T13:20:40.784479+00:00"}}';
    const VALID_SIGNATURE = "MEYCIQCmFCAn2Z+6qEHmf8paI08Ee5ZJ9+KvLWSS3ddp8+RF3AIhALlK7ltfRvMCXhjS7cy8SPlcSlpQtjBxmhN6ClFC0Tv6";
    const INVALID_SIGNATURE = "MEUCIQDOpo1j+V40DNZK2URL2786UQK/8mDXon9ayEd8U0/l7AIgYXtIZJBTs8zCRR3vmted6Ehz/qfw1GRut/eYyvf1yOk=";

    public function queryAndDelete()
    {
        $events = iterator_to_array(Event::query(["limit" => 100, "isDelivered" => true]));
        if (count($events) == 0)
            throw new Exception("failed");
        if (count($events) > 100)
            throw new Exception("failed");
        $event = $events[array_rand($events, 1)];

        if ($event->isDelivered != true)
            throw new Exception("failed");

        $deleted = Event::delete($event->id);

        if (is_null($event->id) | $event->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAttempts()
    {
        $events = iterator_to_array(Event::query(["limit" => 5, "isDelivered" => false]));

        foreach ($events as $event) {
            $attempts = iterator_to_array(Attempt::query(["eventIds" => $event->id, "limit" => 1]));
            if (count($attempts) == 0)
                throw new Exception("failed");
        }
    }

    public function getAttemptsPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Attempt::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $attempt) {
                if (in_array($attempt->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $attempt->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public function queryGetAndUpdate()
    {
        $events = iterator_to_array(Event::query(["limit" => 1, "isDelivered" => false, "before" => "2030-01-01"]));

        if (count($events) != 1) {
            throw new Exception("failed");
        }

        $event = Event::get($events[0]->id);

        if ($events[0]->id != $event->id) {
            throw new Exception("failed");
        }

        $event = Event::update($event->id, ["isDelivered" => true]);
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Event::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $event) {
                if (in_array($event->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $event->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public function parseRight()
    {
        $event_1 = Event::parse(self::CONTENT, self::VALID_SIGNATURE);
        $event_2 = Event::parse(self::CONTENT, self::VALID_SIGNATURE); // using cache

        if ($event_1 != $event_2) {
            throw new Exception("failed");
        }
    }

    public function parseWrong()
    {
        $error = false;
        try {
            $event = Event::parse(self::CONTENT, self::INVALID_SIGNATURE);
        } catch (InvalidSignatureError $e) {
            $error = true;
        }

        if (!$error) {
            throw new Exception("failed");
        }
    }

    public function parseMalformed()
    {
        $error = false;
        try {
            $event = Event::parse(self::CONTENT, "something is definitely wrong");
        } catch (InvalidSignatureError $e) {
            $error = true;
        }

        if (!$error) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nEvent:";

$test = new TestEvent();

echo "\n\t- query and delete";
$test->queryAndDelete();
echo " - OK";

echo "\n\t- query attempts";
$test->queryAttempts();
echo " - OK";

echo "\n\t- get attempts page";
$test->getAttemptsPage();
echo " - OK";


echo "\n\t- query, get and update";
$test->queryGetAndUpdate();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";

echo "\n\t- parse right";
$test->parseRight();
echo " - OK";

echo "\n\t- parse wrong";
$test->parseWrong();
echo " - OK";

echo "\n\t- parse malformed";
$test->parseMalformed();
echo " - OK";