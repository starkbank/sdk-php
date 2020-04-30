<?php

namespace StarkBank\Utils;

use \ReflectionClass;

class Enum
{
    public static function values()
    {
        $reflection = new ReflectionClass(get_called_class());
        return $reflection->getConstants();
    }

    public static function isValid($item)
    {
        return in_array($item, get_called_class()::values());
    }
}
