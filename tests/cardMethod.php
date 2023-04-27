<?php

namespace Test\CardMethod;
use \Exception;
use StarkBank\CardMethod;


class TestCardMethod
{
    public function get()
    {
        $methods = CardMethod::query([
            "search" => "token"
        ]);

        foreach ($methods as $method) {
            if (is_null($method->code)) {
                throw new Exception("failed");
            }
        }
    }
}

echo "\n\nCardMethod:";

$test = new TestCardMethod();

echo "\n\t- query";
$test->get();
echo " - OK";
