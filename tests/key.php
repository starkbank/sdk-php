<?php

namespace Test\Key;
use StarkBank\Key;


class Test
{
  public function create()
  {
    list($priv, $pub) = Key::create();
    list($priv2, $pub2) = Key::create("keys");
  }
}

echo "\nKey:";

$test = new Test();

echo "\n\t- create";
$test->create();
echo " - OK";
