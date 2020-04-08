<?php

namespace Test;
require_once("vendor/autoload.php");
require_once("src/starkbank.php");

$project = new \StarkBank\Project(
    "sandbox",
    "9999999999999999",
    "
    -----BEGIN EC PRIVATE KEY-----
    MHQCAQEEIBEcEJZLk/DyuXVsEjz0w4vrE7plPXhQxODvcG1Jc0WToAcGBSuBBAAK
    oUQDQgAE6t4OGx1XYktOzH/7HV6FBukxq0Xs2As6oeN6re1Ttso2fwrh5BJXDq75
    mSYHeclthCRgU8zl6H1lFQ4BKZ5RCQ==
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
