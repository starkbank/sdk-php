<?php

namespace StarkBank;

use EllipticCurve\PrivateKey;


class Key
{
    /**
    # Generate a new key pair

    Generates a secp256k1 ECDSA private/public key pair to be used in the API authentications

    ## Parameters (optional):
        - path [string]: path to save the keys .pem files. No files will be saved if this parameter isn't provided
    
    ## Return:
        - array with private and public key pems
     */
    public static function create ($path = null)
    {
        $privateKey = new PrivateKey();
        $publicKey = $privateKey->publicKey();

        $privateKeyPem = $privateKey->toPem();
        $publicKeyPem = $publicKey->toPem();

        if (!is_null($path)) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $privateKeyFile = fopen(Key::addFileToPath($path, "privateKey.pem"), "w");
            fwrite($privateKeyFile, $privateKeyPem);
            fclose($privateKeyFile);

            $publicKeyFile = fopen(Key::addFileToPath($path, "publicKey.pem"), "w");
            fwrite($publicKeyFile, $publicKeyPem);
            fclose($publicKeyFile);
        }

        return [$privateKeyPem, $publicKeyPem];
    }

    private static function addFileToPath($path, $file)
    {
        return join('/', array(trim($path, '/'), $file));
    }
}
