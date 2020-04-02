<?php

namespace Test\Balance;
use \Exception;
use Test\TestUser;
use StarkBank\Balance;


class Test
{
  public function get()
  {
    $balance = Balance::get(TestUser::project());
    if (!is_int($balance->amount)) {
      throw new Exception("failed");
    }
  }
}

echo "\nBalance:";

$test = new Test();

echo "\n\t- get";
$test->get();
echo " - OK";
