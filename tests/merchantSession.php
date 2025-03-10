<?php

namespace Test\MerchantSession;
use \Exception;
use StarkBank\MerchantSession;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestMerchantSession
{
    public function create()
    {
        $sessionExample = self::generateExampleMerchantSessionJson("disabled");
        $merchantSession = MerchantSession::create($sessionExample);
        if (is_null($merchantSession->id)) {
            throw new Exception("failed");
        }
    }

    public function query()
    {
        $sessions = iterator_to_array(MerchantSession::query(["limit" => 5, "before" => new DateTime("now")]));
        $index = 0;

        foreach ($sessions as $session) {
        $testSession = MerchantSession::get($session->id);
            print_r($testSession);
            
            if ($sessions[$index]->id != $session->id) {
                throw new Exception("failed");
            }
            $index = $index + 1;
        }
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = MerchantSession::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $session) {
                if (in_array($session->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $session->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public function purchaseChallengeModeDisabled()
    {
        $sessionExample = self::generateExampleMerchantSessionJson("disabled");
        $merchantSession = MerchantSession::create($sessionExample);

        $purchase = [
            "installmentCount" => 12,
            "amount" => 180,
            "cardExpiration" => "2035-01",
            "cardNumber" => "5277696455399733",
            "cardSecurityCode" => "123",
            "holderName" => "João da Silva",
            "fundingType"=> "credit"
        ];

        $MerchantSessionPurchase = MerchantSession::purchase($merchantSession->uuid, $purchase);

        if (is_null($MerchantSessionPurchase->id)) {
            throw new Exception("failed");
        }
    }

    public function purchaseChallengeModeEnabled()
    {
        $sessionExample = self::generateExampleMerchantSessionJson("enabled");
        $merchantSession = MerchantSession::create($sessionExample);

        $purchase = [
            "amount" => 180,
            "installmentCount" => 12,
            "cardExpiration" => "2035-01",
            "cardNumber" => "5277696455399733",
            "cardSecurityCode" => "123",
            "holderName" => "Holder Name",
            "holderEmail" => "holdeName@email.com",
            "holderPhone" => "11111111111",
            "fundingType" => "credit",
            "billingCountryCode" => "BRA",
            "billingCity" => "São Paulo",
            "billingStateCode" => "SP",
            "billingStreetLine1" => "Rua do Holder Name, 123",
            "billingStreetLine2" => "",
            "billingZipCode" => "11111-111",
            "metadata" => [
                "userAgent" => "Postman",
                "userIp" => "255.255.255.255",
                "language" => "pt-BR",
                "timezoneOffset" => 3,
                "extraData" => "extraData"
            ]
        ];

        $MerchantSessionPurchase = MerchantSession::purchase($merchantSession->uuid, $purchase);

        if (is_null($MerchantSessionPurchase->id)) {
            throw new Exception("failed");
        }
    }

    public static function generateExampleMerchantSessionJson($challengeMode) 
    {
        return new MerchantSession ([
            "allowedFundingTypes" => [
                "debit",
                "credit"
            ],
            "allowedInstallments" => [
                [
                    "totalAmount" => 0,
                    "count" => 1
                ],
                [
                    "totalAmount" => 120,
                    "count" => 2
                ],
                [
                    "totalAmount" => 180,
                    "count" => 12
                ]
            ],
            "expiration" => 3600,
            "challengeMode" => $challengeMode,
            "tags" => [
                "yourTags"
            ]
        ]);
    }
}

echo "\n\MerchantSession:";

$test = new TestMerchantSession();

// echo "\n\t- create";
// $test->create();
// echo " - OK";

echo "\n\t- query and get";
$test->query();
echo " - OK";

// echo "\n\t- get page";
// $test->getPage();
// echo " - OK";

// echo "\n\t- purchase challenge mode disabled";
// $test->purchaseChallengeModeDisabled();
// echo " - OK";

// echo "\n\t- purchase challenge mode enabled";
// $test->purchaseChallengeModeEnabled();
// echo " - OK";
