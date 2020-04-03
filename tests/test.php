<?php

namespace Test;
require_once("vendor/autoload.php");
require_once("src/starkbank.php");
require_once("tests/user.php");

echo "\n\nStarting tests\n";

include_once("key.php");
include_once("balance.php");
include_once("transaction.php");
include_once("boleto.php");
include_once("boletoLog.php");
include_once("transfer.php");
include_once("transferLog.php");

echo "\n\nAll tests concluded\n\n";
