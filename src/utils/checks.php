<?php

namespace StarkBank\Utils;
use EllipticCurve\PrivateKey;
use \Exception;
use \DateTime;


class Checks
{
    public static function checkParams($params) {
        $checkParams = true;
        if (array_key_exists("checkParams", $params)) {
            $checkParams = $params["checkParams"];
            unset($params["checkParams"]);
        }

        if ($checkParams & count($params) > 0) {
            throw new Exception("unknown parameters: " . join(", ", array_keys($params)));
        }
    }

    public static function checkId($id) {
        $id = strval($id);
        if (strlen($id) == 0) {
            throw new \Exception("invalid id: " . $id);
        }
        return $id;
    }

    public static function checkEnvironment($environment)
    {
        if (!Environment::isValid($environment)) {
            throw new Exception("Select a valid environment:  " . join(", ", (Environment::values())));
        }
        return $environment;
    }

    public static function checkPrivateKey($pem)
    {
        try {
            PrivateKey::fromPem($pem);
        } catch (Exception $e) {
            throw new Exception("Private-key must be valid secp256k1 ECDSA string in pem format");
        }
        return $pem;
    }

    public static function checkDateTime($data)
    {
        if (is_null($data)){
            return $data;
        }

        if ($data instanceof DateTime) {
            return $data;
        }

        return new DateTime(strval($data));
    }
}
