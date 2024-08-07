<?php

namespace Test;
require_once("vendor/autoload.php");

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
include_once("cardMethod.php");
include_once("corporateBalance.php");
include_once("corporateCard.php");
include_once("corporateCardLog.php");
include_once("corporateHolder.php");
include_once("corporateHolderLog.php");
include_once("corporateInvoice.php");
include_once("corporatePurchase.php");
include_once("corporatePurchaseLog.php");
include_once("corporateTransaction.php");
include_once("corporateWithdrawal.php");
include_once("invoice.php");
include_once("invoiceLog.php");
include_once("dictKey.php");
include_once("dynamicBrcode.php");
include_once("deposit.php");
include_once("depositLog.php");
include_once("brcodePayment.php");
include_once("brcodePaymentLog.php");
include_once("transfer.php");
include_once("transferLog.php");
include_once("boletoPayment.php");
include_once("boletoPaymentLog.php");
include_once("utilityPayment.php");
include_once("utilityPaymentLog.php");
include_once("taxPayment.php");
include_once("taxPaymentLog.php");
include_once("darfPayment.php");
include_once("darfPaymentLog.php");
include_once("webhook.php");
include_once("workspace.php");
include_once("event.php");
include_once("merchantCategory.php");
include_once("merchantCountry.php");
include_once("paymentRequest.php");
include_once("paymentPreview.php");
include_once("institution.php");
include_once("split.php");
include_once("splitLog.php");
include_once("splitReceiver.php");
include_once("splitReceiverLog.php");
include_once("splitProfile.php");
include_once("splitProfileLog.php");
include_once("request.php");

echo "\n\nAll tests concluded\n\n";
