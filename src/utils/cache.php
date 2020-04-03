<?php

namespace StarkBank\Utils;


class Cache
{
    static public $starkBankPublicKey;

    function setStarkBankPublicKey($publicKey)
    {
        self::$starkBankPublicKey = $publicKey;
    }

    function getStarkBankPublicKey()
    {
        return self::$starkBankPublicKey;
    }
}
