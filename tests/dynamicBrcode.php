<?php

namespace Test\DynamicBrcode;
use \Exception;
use StarkBank\DynamicBrcode;


class TestDynamicBrcode
{
    public function create()
    {
        $brcodes = self::examples();

        $brcodes = DynamicBrcode::create($brcodes);

        foreach ($brcodes as $brcode) {
            if (is_null($brcode->uuid)) {
                throw new Exception("failed");
            }
        }
    }

    public function queryGet()
    {
        $brcodes = iterator_to_array(DynamicBrcode::query(["limit" => 10, "before" => "2023-03-10"]));

        if (count($brcodes) != 10) {
            throw new Exception("failed");
        }

        $brcode = DynamicBrcode::get($brcodes[0]->uuid);

        if ($brcodes[0]->uuid != $brcode->uuid) {
            throw new Exception("failed");
        }
    }

    public function getPage()
    {
        $uuids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = DynamicBrcode::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $brcode) {
                if (in_array($brcode->uuid, $uuids)) {
                    throw new Exception("failed");
                }
                array_push($uuids, $brcode->uuid);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($uuids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function examples()
    {
        return [
            new DynamicBrcode([
                "amount" => 400000,
                "expiration" => 123456789,
                "tags" => [
                    'War supply',
                    'DynamicBrcode #1234'
                ]
            ]),
            new DynamicBrcode([
                "amount" => 40000,
                "expiration" => 12346789,
                "tags" => [
                    'War supply',
                    'DynamicBrcode #1234'
                ]
            ])
        ];
    }
}

echo "\n\nDynamicBrcode:";

$test = new TestDynamicBrcode();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->queryGet();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
