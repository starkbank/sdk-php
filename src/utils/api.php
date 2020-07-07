<?php

namespace StarkBank\Utils;
use StarkBank\Utils\StringCase;
use \DateTime;


class API
{
    public static function apiJson($entity)
    {
        if (!is_array($entity)) {
            $entity = get_object_vars($entity);
        }
        return API::castJsonToApiFormat($entity);
    }

    public static function castJsonToApiFormat($json)
    {
        $clean = [];
        if (is_null($json)) {
            return $clean;
        }
        foreach ($json as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            if (is_string($value)) {
                $clean[$key] = utf8_encode($value);
                continue;
            }
            if ($value instanceof DateTime) {
                $clean[$key] = $value->format("Y-m-d");
                continue;
            }
            if (is_array($value)) {
                $clean[$key] = API::castJsonToApiFormat($value);
                continue;
            }
            $clean[$key] = $value;
        }
        return $clean;
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
        $parts = explode("-", StringCase::camelToKebab($resourceName));
        return end($parts);
    }

    public static function lastNamePlural($resourceName)
    {
        return API::lastName($resourceName) . "s";
    }
}

?>