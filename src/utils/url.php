<?php

namespace StarkBank\Utils;


class URL
{
    public static function encode($query)
    {
        $query = API::castJsonToApiFormat($query);
        $queryArray = [];
        foreach ($query as $key => $value) {
            if (is_iterable($value)) {
                $stringValue = [];
                foreach($value as $v) {
                    $stringValue[] = strval($v);
                }
                $value = join(",", $value);
            }
            if (is_bool($value)) {
                $value = $value ? "true" : "false";
            }
            $queryArray[] = strval($key) . "=" . strval($value);
        }

        if (count($queryArray) > 0) {
            return "?" . join("&", $queryArray);
        }
        return "";
    }
}
