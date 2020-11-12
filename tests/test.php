<?php

namespace Test;
require_once("vendor/autoload.php");
require_once("src/init.php");

$projectId = $_SERVER["SANDBOX_ID"]; # "9999999999999999",
$privateKey = $_SERVER["SANDBOX_PRIVATE_KEY"]; # "-----BEGIN EC PRIVATE KEY-----\nMHQCAQEEIBEcEJZLk/DyuXVsEjz0w4vrE7plPXhQxODvcG1Jc0WToAcGBSuBBAAK\noUQDQgAE6t4OGx1XYktOzH/7HV6FBukxq0Xs2As6oeN6re1Ttso2fwrh5BJXDq75\nmSYHeclthCRgU8zl6H1lFQ4BKZ5RCQ==\n-----END EC PRIVATE KEY-----"

if (is_null($projectId) || is_null($privateKey)) {
    throw new \Exception("missing test credentials");
}

$project = new \StarkBank\Project([
    "environment" => "sandbox",
    "id" => $projectId,
    "privateKey" => $privateKey
]);
\StarkBank\Settings::setUser($project);

echo "\n\nStarting tests\n";

include_once("key.php");
include_once("balance.php");
include_once("transaction.php");
include_once("boleto.php");
include_once("boletoLog.php");
include_once("boletoHolmes.php");
include_once("boletoHolmesLog.php");
include_once("invoice.php");
include_once("invoiceLog.php");
include_once("dictKey.php");
include_once("deposit.php");
include_once("depositLog.php");
include_once("transfer.php");
include_once("transferLog.php");
include_once("boletoPayment.php");
include_once("boletoPaymentLog.php");
include_once("utilityPayment.php");
include_once("utilityPaymentLog.php");
include_once("webhook.php");
include_once("event.php");
include_once("paymentRequest.php");

echo "\n\nAll tests concluded\n\n";
