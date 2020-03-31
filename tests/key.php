<?php

namespace StarkBank\Test;

require("src/key.php");

use StarkBank\Key;

class KeyTest
{
  public function test()
  {
    list($priv, $pub) = Key::create();
    list($priv2, $pub2) = Key::create("keys");
  }
}

$keyTest = new KeyTest();

$keyTest->test();

?>