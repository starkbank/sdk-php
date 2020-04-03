<?php

namespace StarkBank\Utils;
use StarkBank\Utils\StringCase;
use \DateTime;


class API
{
    public static function apiJson($entity)
    {
        return API::castJsonToApiFormat(get_object_vars($entity));
    }

    public static function castJsonToApiFormat($json)
    {
        $clean = [];
        foreach ($json as $key => $value) {
            if (!is_null($value)) {
                $clean[$key] = API::dateToString($value);
            }
        }
        return $clean;
    }

    private static function dateToString($data)
    {
        if ($data instanceof DateTime){
            return $data->format("Y-m-d");
        }
        return $data;
    }

    public static function fromApiJson($resourceMaker, $json) {
        $json["checkParams"] = false;
        return $resourceMaker($json);
    }

    public static function endpoint($resourceName)
    {
        return str_replace("-log", "/log", StringCase::camelToKebab($resourceName));
    }

    public static function lastName($resourceName)
    {
        return end(explode("-", StringCase::camelToKebab($resourceName)));
    }

    public static function lastNamePlural($resourceName)
    {
        return API::lastName($resourceName) . "s";
    }
}

?>