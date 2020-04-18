<?php

namespace Test\Balance;
use \Exception;
use StarkBank\Balance;


class Test
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

$test = new Test();

echo "\n\t- get";
$test->get();
echo " - OK";
