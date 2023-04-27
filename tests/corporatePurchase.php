<?php

namespace Test\CorporatePurchase;
use \Exception;
use StarkBank\CorporatePurchase;
use StarkCore\Error\InvalidSignatureError;


class TestCorporatePurchase
{
    const CONTENT = '{"acquirerId": "236090", "amount": 100, "cardId": "5671893688385536", "cardTags": [], "endToEndId": "2fa7ef9f-b889-4bae-ac02-16749c04a3b6", "holderId": "5917814565109760", "holderTags": [], "isPartialAllowed": false, "issuerAmount": 100, "issuerCurrencyCode": "BRL", "merchantAmount": 100, "merchantCategoryCode": "bookStores", "merchantCountryCode": "BRA", "merchantCurrencyCode": "BRL", "merchantFee": 0, "merchantId": "204933612653639", "merchantName": "COMPANY 123", "methodCode": "token", "purpose": "purchase", "score": null, "tax": 0, "walletId": ""}';
    const VALID_SIGNATURE = "MEUCIBxymWEpit50lDqFKFHYOgyyqvE5kiHERi0ZM6cJpcvmAiEA2wwIkxcsuexh9BjcyAbZxprpRUyjcZJ2vBAjdd7o28Q=";
    const INVALID_SIGNATURE = "MEUCIQDOpo1j+V40DNZK2URL2786UQK/8mDXon9ayEd8U0/l7AIgYXtIZJBTs8zCRR3vmted6Ehz/qfw1GRut/eYyvf1yOk=";

    public function queryAndGet()
    {
        $purchases = CorporatePurchase::query(["limit" => 10]);

        foreach ($purchases as $purchase) {
            if (is_null($purchase->id)) {
                throw new Exception("failed");
            }

            $purchase = iterator_to_array(CorporatePurchase::query(["limit" => 1]))[0];
            $purchase = CorporatePurchase::get($purchase->id);

            if (!is_string($purchase->id)) {
                throw new Exception("failed");
            }
        }        
    }

    public function page()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = CorporatePurchase::page(["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $purchase) {
                print_r($purchase);
                if (is_null($purchase->id) or in_array($purchase->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $purchase->id);
            }
            if ($cursor == null) {
                break;
            }
        }
    }

    public function parseRight()
    {
        $authorization_1 = CorporatePurchase::parse(self::CONTENT, self::VALID_SIGNATURE);
        $authorization_2 = CorporatePurchase::parse(self::CONTENT, self::VALID_SIGNATURE); // using cache

        if ($authorization_1 != $authorization_2) {
            throw new Exception("failed");
        }
    }

    public function parseWrong()
    {
        $error = false;
        try {
            $authorization = CorporatePurchase::parse(self::CONTENT, self::INVALID_SIGNATURE);
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
            $authorization = CorporatePurchase::parse(self::CONTENT, "something is definitely wrong");
        } catch (InvalidSignatureError $e) {
            $error = true;
        }

        if (!$error) {
            throw new Exception("failed");
        }
    }

    public function createResponse()
    {
        $response = CorporatePurchase::response(["status"=>"accepted", "amount"=>1000]);
        if (gettype($response) != "string") {
            throw new Exception("failed");
        }
        if (strlen($response) == 0) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nCorporatePurchase:";

$test = new TestCorporatePurchase();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- page";
$test->page();
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

echo "\n\t- create response";
$test->createResponse();
echo " - OK";
