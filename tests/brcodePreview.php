<?php

namespace Test\BrcodePreview;
use \Exception;
use StarkBank\BrcodePreview;
use \DateTime;


class TestBrcodePreview
{
    public function query()
    {
        $previews = iterator_to_array(BrcodePreview::query(["brcodes" => ["00020126390014br.gov.bcb.pix0117valid@sandbox.com52040000530398654041.005802BR5908Jon Snow6009Sao Paulo62110507sdktest63046109"]]));
        
        if (count($previews) != 1) {
            throw new Exception("failed");
        }
    }
}

echo "\n\nBrcodePreview:";

$test = new TestBrcodePreview();

echo "\n\t- query";
$test->query();
echo " - OK";
