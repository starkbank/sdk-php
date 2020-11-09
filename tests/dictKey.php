<?php

namespace Test\DictKey;
use \Exception;
use StarkBank\DictKey;

class TestDictKey
{
  public function get()
  {
    $pixKey = "tony@starkbank.com";
    $dictKey = DictKey::get($pixKey);
    
    if (is_null($dictKey->id) || $dictKey->id != $pixKey) {
      throw new Exception("failed");
    }    
  }
}

echo "\n\nDictKey";

$test = new TestDictKey();

echo "\n\t - get";
$test->get();
echo " - OK";