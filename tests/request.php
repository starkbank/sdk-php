<?php

namespace Test\Request;
use \Exception;
use StarkBank\Request;
use DateTime;


class TestRequest
{
  public function get()
  {
    $path = "invoice";
    $query = ["limit" => 3];
    $request = Request::get($path, $query);
    $testAssertion = json_decode($request, true);
    if (!is_int($testAssertion["invoices"][0]["amount"])) {
      throw new Exception("failed");
    }
    $testId = $testAssertion["invoices"][0]["id"];
    $path = "invoice/" . $testId;
    $request = Request::get($path);
    $testAssertion = json_decode($request, true);
    if (!($testAssertion["invoice"]["id"] === $testId)) {
        throw new Exception("failed");
      }
  }

  public function getPdfQrcode()
  {
    $path = "invoice";
    $query = ["limit" => 3];
    $request = Request::get($path, $query);
    $testAssertion = json_decode($request, true);
    if (!is_int($testAssertion["invoices"][0]["amount"])) {
      throw new Exception("failed");
    }
    $testId = $testAssertion["invoices"][0]["id"];
    $path = "invoice/" . $testId . "/pdf";
    $request = Request::get($path);
    $fp = fopen('invoice.pdf', 'w');
    fwrite($fp, $request);
    fclose($fp);

    $path = "invoice/" . $testId . "/qrcode";
    $request = Request::get($path);

    $fp = fopen('invoice-qrcode.png', 'w');
    fwrite($fp, $request);
    fclose($fp);
  }

  public function post()
  {
    $path = "invoice";
    $body = [
        "invoices" => [
            [
                "amount" => 400000,
                "taxId" => "012.345.678-90",
                "name" => "Arya Stark",    
            ]
        ]
    ];
    $request = Request::Post($path, $body);
    $testAssertion = json_decode($request, true);
    if (!is_int($testAssertion["invoices"][0]["amount"])) {
      throw new Exception("failed");
    }
  }

  public function patch()
  {
    $path = "invoice";
    $query = [
        "limit" => 1,
        "status" => "paid"
    ];
    $request = Request::get($path, $query);

    $patchId = json_decode($request, true)["invoices"][0]["id"];
    $path = "invoice/" . $patchId;

    $body = ["amount" => 0];

    $request = Request::patch($path, $body);
    $testAssertion = json_decode($request, true);
    if (!$testAssertion["invoice"]["amount"] === 0) {
        throw new Exception("failed");
      }
  }

  public function put()
  {
    $path = "split-profile";
    $data = [
        "profiles" =>[
            [
                "interval" => "day",
                "delay" => 0
            ]
        ]
    ];

    $request = Request::put($path, $data);
    $testAssertion = json_decode($request, true);

    if (!$testAssertion["profiles"][0]["delay"] === 0) {
        throw new Exception("failed");
    }
  }
  public function delete()
  {
    $timestamp = time();
    $date = new DateTime();
    $date->modify('+10 days');

    $path = "transfer";
    $body = [
        "transfers" => [
            [
                "amount" => 10000,
                "name" => "Steve Rogers",
                "taxId" => "330.731.970-10",
                "bankCode" => "001",
                "branchCode" => "1234",
                "accountNumber" => "123456-0",
                "accountType" => "checking",
                "scheduled" => $date->format('Y-m-d'),
                "externalId" => (string) $timestamp,
            ]
        ]
    ];
    $request = Request::Post($path, $body);
    $transferId = json_decode($request, true)["transfers"][0]["id"];

    $path = "transfer/" . $transferId;
    $request = Request::delete($path);

    $testAssertion = json_decode($request, true);
    if (!$testAssertion["transfer"]["status"] === "canceled") {
      throw new Exception("failed");
    }
  }

}

echo "\n\Request:";

$test = new TestRequest();

echo "\n\t- get";
$test->get();
echo " - OK";

echo "\n\t- getPdfQrcode";
$test->getPdfQrcode();
echo " - OK";

echo "\n\t- post";
$test->post();
echo " - OK";

echo "\n\t- patch";
$test->patch();
echo " - OK";

echo "\n\t- put";
$test->put();
echo " - OK";

echo "\n\t- delete";
$test->delete();
echo " - OK";
