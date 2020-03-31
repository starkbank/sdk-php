<?php

namespace StarkBank\Utils;
use StarkBank\Utils\StringCase;
use \DateTime;

class API
{
    public static function apiJson($entity)
    {
        $cleanVars = [];
        foreach(get_object_vars($entity) as $key => $value) {
            if (!is_null($value)) {
                $cleanVars[$key] = API::dateToString($value);
            }
        }
        return $cleanVars;
    }

    private static function dateToString($data)
    {
        if ($data instanceof DateTime){
            return $data->format("Y-m-d");
        }
        return $data;
    }

    public static function fromApiJson($resource, $json)
    {
        $params = func_get_args($resource::__construct);

        $cleanJson = [];
        foreach ($params as $key) {
            $cleanJson[$key] = null;
            if (array_key_exists($key, $json)) {
                $cleanJson[$key] = $json[$key];
            }
        }

        return call_user_func_array($resource::__construct, $cleanJson);
    }

    public static function endpoint($resource)
    {
        return str_replace("-log", "/log", StringCase::camelToKebab($resource));
    }

    public static function lastName($resource)
    {
        return end(explode("-", StringCase::camelToKebab($resource)));
    }

    public static function lastNamePlural($resource)
    {
        return API::lastName($resource) . "s";
    }
}

?>