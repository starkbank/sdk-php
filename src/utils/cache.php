<?php

namespace StarkBank\Utils;


class Cache
{
    static private $starkBankPublicKey;

    static function getStarkBankPublicKey()
    {
        return self::$starkBankPublicKey;
    }
    
    static function setStarkBankPublicKey($publicKey)
    {
        self::$starkBankPublicKey = $publicKey;
    }
}
