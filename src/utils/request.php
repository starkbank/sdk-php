<?php

namespace StarkBank\Utils;

use \Exception;
use EllipticCurve\Ecdsa;
use StarkBank\Settings;
use StarkBank\Utils\URL;
use StarkBank\Error\InputErrors;
use StarkBank\Error\InternalServerError;
use StarkBank\Error\UnknownError;


class Response
{
    function __construct($status, $content)
    {
        $this->status = $status;
        $this->content = $content;
    }

    function json()
    {
        return json_decode($this->content, true);
    }
}


class Request
{
    public static function fetch($user, $method, $path, $payload = null, $query = null, $version = "v2")
    {
        if (is_null($user)) {
            $user = Settings::getUser();
        }
        if (is_null($user)) {
            throw new Exception("A user is required to access our API. Check our README: https://github.com/starkbank/sdk-php/");
        }

        $url = [
            Environment::production => "https://api.starkbank.com/",
            Environment::sandbox => "https://sandbox.api.starkbank.com/",
        ][$user->environment] . $version . "/";
        $url .= $path;
        if (!is_null($query)) {
            $url .= URL::encode($query);
        }

        $accessTime = strval(time());
        $message = $user->accessId() . ":" . $accessTime . ":";
        $body = null;
        if (!is_null($payload)) {
            $body = json_encode($payload);
            $message .= $body;
        }
        $signature = Ecdsa::sign($message, $user->privateKey())->toBase64();

        $headers = [
            "Access-Time" => $accessTime,
            "Access-Id" => $user->accessId(),
            "Access-Signature" => $signature,
            "User-Agent" => "PHP-" . phpversion() . "-SDK-2.8.0",
            "Content-Type" => "application/json",
            "Accept-Language" => Settings::getLanguage()
        ];

        $response = Request::makeRequest($method, $headers, $url, $body);

        if ($response->status == 500) {
            throw new InternalServerError();
        }
        if ($response->status == 400) {
            throw new InputErrors($response->json()["errors"]);
        }
        if ($response->status != 200) {
            throw new UnknownError(strval($response->content));
        }

        return $response;
    }

    private static function makeRequest($method, $headers, $url, $body)
    {
        $stringHeader = "";
        foreach($headers as $key => $value) {
            $stringHeader .= $key . ": " . $value . "\r\n";
        }

        $opts = [
            'http' => [
                'method'  => $method,
                'header'  => $stringHeader,
                'ignore_errors' => true
            ]
        ];
        if (!is_null($body)) {
            $opts = [
                'http' => [
                    'method'  => $method,
                    'header'  => $stringHeader,
                    'content' => $body,
                    'ignore_errors' => true
                ]
            ];
        }
        
        try {
            $content = file_get_contents($url, false, stream_context_create($opts));
        } catch (Exception $e) {
            throw new UnknownError(strval($e));
        }

        $status = null;
        if (is_array($http_response_header)) {
            $parts = explode(' ', $http_response_header[0]);
            if (count($parts) > 1) //HTTP/1.0 <code> <text>
                $status = intval($parts[1]);
        }
        
        return new Response($status, $content);
    }
}

?>