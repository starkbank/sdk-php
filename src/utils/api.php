<?php

namespace StarkBank\Utils;
use StarkBank\Utils\StringCase;
use \DateTime;
use \DateInterval;
use \DateTimeZone;


class API
{
    public static function apiJson($entity, $resourceName = null)
    {
        if (!is_array($entity)) {
            $entity = $entity->__toArray();
        }
        return API::castJsonToApiFormat($entity, $resourceName);
    }

    public static function castJsonToApiFormat($json, $resourceName = null)
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
                $value = utf8_decode($value);
                $clean[$key] = utf8_encode($value);
                continue;
            }
            if ($value instanceof DateTime) {
                $clean[$key] = API::convertDateTime($value);
                continue;
            }
            if ($value instanceof StarkBankDateTime || $value instanceof StarkBankDate) {
                $clean[$key] = $value->__toString();
                continue;
            }
            if ($value instanceof DateInterval) {
                $clean[$key] = API::convertDateInterval($value);
                continue;
            }
            if (is_array($value)) {
                $clean[$key] = API::castJsonToApiFormat($value, $resourceName);
                continue;
            }
            if($value instanceof Resource) {
                $clean[$key] = API::castJsonToApiFormat(get_object_vars($value), $resourceName);
                continue;
            }
            $clean[$key] = $value;
        }
        return $clean;
    }

    private static function convertDateTime($value) {
        if ($value->format("H:i:s.u") == "00:00:00.000000") {
            return $value->format("Y-m-d");
        }
        $value->setTimezone(new DateTimeZone("UTC"));
        return $value->format("Y-m-d\TH:i:s.u" . "+00:00");
    }

    private static function convertDateInterval($value)
    {
        $total = $value->y;
        $total = ($total * 12) + ($value->m);
        $total = ($total * 30) + ($value->d);
        $total = ($total * 24) + ($value->h);
        $total = ($total * 60) + ($value->i);
        $total = ($total * 60) + ($value->s);
        return $total;
    }

    public static function fromApiJson($resourceMaker, $json) {
        $json["checkParams"] = false;
        return $resourceMaker($json);
    }

    public static function endpoint($resourceName)
    {
        $targets = array("-log", "-attempt");
        $replacements = array("/log", "/attempt");
        return str_replace($targets, $replacements, StringCase::camelToKebab($resourceName));
    }

    public static function lastName($resourceName)
    {
        $parts = explode("-", StringCase::camelToKebab($resourceName));
        return end($parts);
    }

    public static function lastNamePlural($resourceName)
    {   
        if ($resourceName[strlen($resourceName)-1] == 's')
            return API::lastName($resourceName);
        if (substr($resourceName, strlen($resourceName)-2, 2) == 'ey')
            return API::lastName($resourceName) . "s";
        if ($resourceName[strlen($resourceName)-1] == 'y')
            return API::lastName(substr($resourceName, 0, -1)) . "ies";
        return API::lastName($resourceName) . "s";
    }
}

?>