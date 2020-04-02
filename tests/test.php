<?php

namespace Test;
require_once("vendor/autoload.php");
require_once("src/starkbank.php");
require_once("tests/user.php");

echo "\n\nStarting tests\n";

include_once("key.php");
include_once("balance.php");
include_once("transaction.php");

echo "\n\nAll tests concluded\n\n";
