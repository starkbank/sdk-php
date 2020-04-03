<?php

namespace StarkBank\Utils;


class Cache
{
    static private $starkBankPublicKey;

    function getStarkBankPublicKey()
    {
        return self::$starkBankPublicKey;
    }
    
    function setStarkBankPublicKey($publicKey)
    {
        self::$starkBankPublicKey = $publicKey;
    }
}
