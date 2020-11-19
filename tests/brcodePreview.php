<?php

namespace Test\BrcodePreview;
use \Exception;
use StarkBank\BrcodePreview;
use \DateTime;


class TestBrcodePreview
{
    public function query()
    {
        $previews = iterator_to_array(BrcodePreview::query(["brcodes" => ["00020126580014br.gov.bcb.pix0136a629532e-7693-4846-852d-1bbff817b5a8520400005303986540510.005802BR5908T'Challa6009Sao Paulo62090505123456304B14A"]]));
        
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
