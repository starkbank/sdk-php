<?php

namespace StarkBank\Utils;


class URL
{
    public static function encode($query)
    {
        $queryArray = [];
        foreach ($query as $key => $value) {
            if (!is_null($value)) {
                if (is_iterable($value)) {
                    $stringValue = [];
                    foreach($value as $v) {
                        $stringValue[] = strval($v);
                    }
                    $value = join(",", $value);
                }
                $queryArray[] = strval($key) . "=" . strval($value);
            }
        }

        if (count($queryArray) > 0) {
            return "?" . join("&", $queryArray);
        }
        return "";
    }
}
