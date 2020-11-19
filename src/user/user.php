<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use EllipticCurve\PrivateKey;


class User extends Resource
{
    function __construct(&$params)
    {
        parent::__construct($params);

        $this->pem = Checks::checkPrivateKey(Checks::checkParam($params, "privateKey"));
        $this->environment = Checks::checkEnvironment(Checks::checkParam($params, "environment"));
    }

    public function accessId()
    {
        $parts = explode("\\", strtolower(get_called_class()));
        return end($parts) . "/" . strval($this->id);
    }

    public function privateKey()
    {
        return PrivateKey::fromPem($this->pem);
    }
}

?>