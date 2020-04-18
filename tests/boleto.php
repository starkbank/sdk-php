<?php

namespace Test\Boleto;
use \Exception;
use StarkBank\Boleto;
use \DateTime;
use \DateInterval;


class Test
{
    public function createAndDelete()
    {
        $boletos = [Test::example()];

        $boleto = Boleto::create($boletos)[0];

        $deleted = Boleto::delete($boleto->id);

        if (is_null($boleto->id) | $boleto->id != $deleted->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $boletos = iterator_to_array(Boleto::query(["limit" => 10, "before" => new DateTime("now")]));

        if (count($boletos) != 10) {
            throw new Exception("failed");
        }

        $boleto = Boleto::get($boletos[0]->id);

        if ($boletos[0]->id != $boleto->id) {
            throw new Exception("failed");
        }
    }

    public function queryAndGetPdf()
    {
        $boletos = iterator_to_array(Boleto::query(["limit" => 10]));

        if (count($boletos) != 10) {
            throw new Exception("failed");
        }

        $pdf = Boleto::pdf($boletos[0]->id);

        $fp = fopen('boleto.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    private function example()
    {
        return new Boleto([
            "amount" => 100000,
            "name" => "Mr Meeseks",
            "taxId" => "012.345.678-90",
            "streetLine1" => "Rua do Teste, 200",
            "streetLine2" => "apto 418",
            "district" => "Jardim Paulista",
            "city" => "São Paulo",
            "stateCode" => "SP",
            "zipCode" => "55555-555",
            "due" => (new DateTime("now"))->add(new DateInterval("P1D")),
            "tags" => ["going", "to", "delete"],
        ]);
    }
}

echo "\n\nBoleto:";

$test = new Test();

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";
