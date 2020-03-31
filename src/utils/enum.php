<?php

namespace StarkBank\Utils;

use \ReflectionClass;

class Enum
{
    public function values()
    {
        $reflection = new ReflectionClass(get_called_class());
        return $reflection->getConstants();
    }

    public function isValid($item)
    {
        return in_array($item, Enum::values());
    }
}
