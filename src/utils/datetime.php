<?php

namespace StarkBank\Utils;
use \DateTimeZone;


class StarkBankDate
{
    function __construct($value)
    {
        $this->value = $value;
    }

    function __toString() {
        $value = $this->value;
        if (is_null($value))
            return "";
        if (is_string($value))
            return $value;
        return $value->format("Y-m-d");
    }
}

class StarkBankDateTime
{
    function __construct($value)
    {
        $this->value = $value;
    }

    function __toString()
    {
        $value = $this->value;
        if (is_null($value))
            return null;
        if ($value instanceof string)
            return $value;
        $value->setTimezone(new DateTimeZone("UTC"));
        return $value->format("Y-m-d\TH:i:s.u" . "+00:00");
    }
}
