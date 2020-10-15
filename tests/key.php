<?php

namespace Test\Key;
use StarkBank\Key;


class TestKey
{
  public function create()
  {
    list($priv, $pub) = Key::create();
    list($priv2, $pub2) = Key::create("keys");
  }
}

echo "\n\nKey:";

$test = new TestKey();

echo "\n\t- create";
$test->create();
echo " - OK";
