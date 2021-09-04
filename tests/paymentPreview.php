<?php

namespace Test\PaymentPreview;
use \Exception;
use \DateTime;
use \DateInterval;
use StarkBank\PaymentPreview;
use StarkBank\Boleto;


class TestPaymentPreview
{

    public function create()
    {
        $previews = [];
        $scheduled = (new DateTime("now"))->add(new DateInterval("P1D"))->setTime(0, 0, 0, 0);
        array_push($previews, new PaymentPreview(["scheduled" => $scheduled, "id" => "00020126390014br.gov.bcb.pix0117valid@sandbox.com52040000530398654041.005802BR5908Jon Snow6009Sao Paulo62110507sdktest63046109"]));
        array_push($previews, new PaymentPreview(["id" => iterator_to_array(Boleto::query(["limit" => 1]))[0]->line]));
        array_push($previews, new PaymentPreview(["id" => "8566000".sprintf("%08d", random_int(100, 100000000))."00640074119002551100010601813"]));
        array_push($previews, new PaymentPreview(["id" => "8364000".sprintf("%08d", random_int(100, 100000000))."01380076105302611108067159411"]));
        $previews = PaymentPreview::create($previews);
        foreach ($previews as $preview) {
            if ($preview == null) {
                throw new Exception("failed");
            }
        }
    }
}

echo "\n\nPayment Preview:";

$test = new TestPaymentPreview();

echo "\n\t- create";
$test->create();
echo " - OK";
