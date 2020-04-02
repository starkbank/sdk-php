<?php

namespace StarkBank\Utils;
use EllipticCurve\PrivateKey;
use \Exception;
use \DateTime;


class Checks
{
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
