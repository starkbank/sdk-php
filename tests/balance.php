<?php

namespace Test\Balance;
use \Exception;
use StarkBank\Balance;


class TestBalance
{
  public function get()
  {
    $balance = Balance::get();
    if (!is_int($balance->amount)) {
      throw new Exception("failed");
    }
  }
}

echo "\n\nBalance:";

$test = new TestBalance();

echo "\n\t- get";
$test->get();
echo " - OK";
