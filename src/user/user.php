<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use EllipticCurve\PrivateKey;


class User extends Resource
{
    function __construct($id = null, $privateKey = null, $environment = null)
    {
        parent::__construct($id);
        $this->pem = Checks::checkPrivateKey($privateKey);
        $this->environment = Checks::checkEnvironment($environment);
    }

    public function acessId()
    {
        return strtolower(get_called_class()) . "/" . strval($this->id);
    }

    public function privateKey()
    {
        return PrivateKey::fromPem($this->pem);
    }
}

?>