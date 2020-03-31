<?php

namespace StarkBank;

require __DIR__ . '/../vendor/autoload.php';
use EllipticCurve\PrivateKey;

class Key {
    public static function create ($path = null) {
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

        return array($privateKeyPem, $publicKeyPem);
    }

    private function addFileToPath($path, $file) {
        return join('/', array(trim($path, '/'), $file));
    }
}
