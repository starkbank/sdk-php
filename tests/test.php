<?php

namespace Test;
require_once("vendor/autoload.php");
require_once("src/starkbank.php");

$project = new \StarkBank\Project(
    "sandbox",
    "5690398416568320",
    "
    -----BEGIN EC PRIVATE KEY-----
    MHQCAQEEIIoYWZ2OGwqX6n1EVvj1C1YvWHSGqqhZJzfsZZnk0SVgoAcGBSuBBAAK
    oUQDQgAEGS1jWJXoK9RUk+qoNNFquO7X4JzRf5ZA5UDJUfPCbbKe5KwtrBKTJC1/
    vRGIpAM5gNsxdfKgmoXNriiuY4LEPQ==
    -----END EC PRIVATE KEY-----
    "
);
\StarkBank\User::setDefault($project);

echo "\n\nStarting tests\n";

include_once("key.php");
include_once("balance.php");
include_once("transaction.php");
include_once("boleto.php");
include_once("boletoLog.php");
include_once("transfer.php");
include_once("transferLog.php");
include_once("boletoPayment.php");
include_once("boletoPaymentLog.php");
include_once("utilityPayment.php");
include_once("utilityPaymentLog.php");
include_once("webhook.php");
include_once("event.php");

echo "\n\nAll tests concluded\n\n";
