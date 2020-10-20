<?php

namespace Test\BoletoHolmes;
use \Exception;
use StarkBank\BoletoHolmes;
use StarkBank\Boleto;
use \DateTime;
use Test\Boleto\TestBoleto as BoletoTest;

class Test
{
    public function create()
    {   
        $boletos = [BoletoTest::example()];

        $boleto = Boleto::create($boletos)[0];

        $holmes = [new BoletoHolmes([
            "boletoId" => $boleto->id
        ])];

        $sherlock = BoletoHolmes::create($holmes)[0];

        if (is_null($sherlock->id)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $holmes = iterator_to_array(Boleto::query(["limit" => 10, "before" => new DateTime("now")]));

        if (count($holmes) != 10) {
            throw new Exception("failed");
        }

        $sherlock = Boleto::get($holmes[0]->id);

        if ($holmes[0]->id != $sherlock->id) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nBoletoHolmes:";

$test = new Test();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

