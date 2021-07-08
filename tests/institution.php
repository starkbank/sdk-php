<?php

namespace Test\Institution;
use \Exception;
use StarkBank\Institution;
use \DateTime;
use \DateTimeZone;
use \DateInterval;


class TestInstitution
{
    public function query()
    {
        $institution = Institution::query(["search" => "stark"]);
        if (count($institution) != 1) {
            throw new Exception("failed");
        }

        $institution = Institution::query(["spiCodes" => "20018183"]);
        if (count($institution) != 1) {
            throw new Exception("failed");
        }

        $institution = Institution::query(["strCodes" => "341"]);
        if (count($institution) != 1) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nInstitution:";

$test = new TestInstitution();

echo "\n\t- query";
$test->query();
echo " - OK";
