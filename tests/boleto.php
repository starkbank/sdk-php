<?php

namespace Test\Boleto;
use \Exception;
use StarkBank\Boleto;
use \DateTime;
use \DateInterval;


class TestBoleto
{
    public function createAndDelete()
    {
        $boletos = [self::example()];

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

        $pdf = Boleto::pdf($boletos[0]->id, ["layout" => "default"]);

        $fp = fopen('boleto.pdf', 'w');
        fwrite($fp, $pdf);
        fclose($fp);
    }

    public function getPage()
    {
        $ids = [];
        $cursor = null;
        for ($i=0; $i < 2; $i++) { 
            list($page, $cursor) = Boleto::page($options = ["limit" => 5, "cursor" => $cursor]);
            foreach ($page as $boleto) {
                if (in_array($boleto->id, $ids)) {
                    throw new Exception("failed");
                }
                array_push($ids, $boleto->id);
            }
            if ($cursor == null) {
                break;
            }
        }
        if (count($ids) != 10) {
            throw new Exception("failed");
        }
    }

    public static function example()
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
            "receiverName" => "Mr Meeseks Receiver",
            "receiverTaxId" => "123.456.789-09",
            "due" => (new DateTime("now"))->add(new DateInterval("P5D")),
            "tags" => ["going", "to", "delete"],
            "descriptions" => [
                [
                    "text" => "product A",
                    "amount" => 123
                ],
                [
                    "text" => "product B",
                    "amount" => 456
                ],
                [
                    "text" => "product C",
                    "amount" => 789
                ]
            ],
            "discounts" => [
                [
                    "percentage" => 5,
                    "date" => (new DateTime("now"))->add(new DateInterval("P1D"))
                ],
                [
                    "percentage" => 3,
                    "date" => (new DateTime("now"))->add(new DateInterval("P2D"))
                ]
            ]
        ]);
    }
}

echo "\n\nBoleto:";

$test = new TestBoleto();

echo "\n\t- create and delete";
$test->createAndDelete();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";

echo "\n\t- query and get PDF";
$test->queryAndGetPdf();
echo " - OK";

echo "\n\t- get page";
$test->getPage();
echo " - OK";
