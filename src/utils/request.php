<?php

namespace StarkBank\Utils;

require __DIR__ . '/.../vendor/autoload.php';
use EllipticCurve\Ecdsa;
use \Exception;
use StarkBank\Exception\InputErrors;
use StarkBank\Exception\InternalServerError;
use StarkBank\Exception\UnknownException;


class Response
{
    function __construct($status, $content)
    {
        $this->status = $status;
        $this->content = $content;
    }

    function json()
    {
        return json_decode($this->content);
    }
}


class Request
{
    public static function fetch($method, $path, $payload=null, $query=null, $user=null, $version="v2")
    {
        $url = [
            "production" => "https://api.starkbank.com/",
            "sandbox" => "https://sandbox.api.starkbank.com/",
        ][$user->environment] . $version . "/";

        $url = $url . $path;

        if (!is_null($query)) {
            $url = $url . "?" . urlencode($query);
        }

        $accessTime = strval(time());
        $message = $user->accessId . $accessTime;
        $body = null;
        if (!is_null($payload)) {
            $body = json_encode($payload);
            $message = $message . $body;
        }
        $signature = Ecdsa::sign($message, $user->privateKey)->toBase64();

        $headers = [
            "Access-Time" => $accessTime,
            "Access-Id" => $user->accessId,
            "Access-Signature" => $signature,
            "User-Agent" => "PHP-" . phpversion() . "-SDK-2.0.0",
            "Content-Type" => "application/json"
        ];

        $opts = [
            'http' => [
                'method'  => $method,
                'header'  => $headers,
                'content' => $body
            ]
        ];

        $context = stream_context_create($opts);

        try {
            $result = file_get_contents($url, false, $context);
        } catch (Exception $e) {
            throw new UnknownException(strval($e));
        }

        $response = new Response($result->status, $result->content);

        if ($response->status == 500) {
            throw new InternalServerError();
        }
        if ($response->status == 400) {
            throw new InputErrors($response->json()["errors"]);
        }
        if ($response->status != 200) {
            throw new UnknownException($response->content);
        }

        return $response;
    }
}

?>