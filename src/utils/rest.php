<?php

namespace StarkBank\Utils;
use StarkBank\Utils\API;

class Rest
{
    public static function getList($user, $resource, $limit = null, array $query = [])
    {
        $query["limit"] = is_null($limit) ? null : min($limit, 100);

        while (true) {
            $json = Request::fetch($user, "GET", API::endpoint($resource), null, $query)->json();
            $entities = $json[API::lastNamePlural($resource)];

            foreach($entities as $entity) {
                yield API::fromApiJson($resource, $entity);
            }

            if (!is_null($limit)) {
                $limit -= 100;
                $query["limit"] = min($limit, 100);
            }

            $cursor = $json["cursor"];
            $query["cursor"] = $cursor;
            if (is_null($cursor) | (!is_null($limit) & $limit <= 0)) {
                break;
            }
        }
    }

    public static function getId($user, $resource, $id)
    {
        $json = Request::fetch($user, "GET", API::endpoint($resource) . "/" . $id)->json();
        $entity = $json[API::lastName($resource)];
        return API::fromApiJson($resource, $entity);
    }

    public static function getPdf($user, $resource, $id)
    {
        return Request::fetch($user, "GET", API::endpoint($resource) . "/" . $id . "/pdf");
    }

    public static function post($user, $resource, $entities)
    {
        $entitiesJson = [];
        foreach($entities as $entity){
            $entitiesJson[] = API::apiJson($entity);
        }
        $payload = [
            API::lastNamePlural($resource) => $entitiesJson
        ];

        $json = Request::fetch($user, "POST", API::endpoint($resource), $payload)->json();

        $retrievedEntities = [];
        foreach($json[API::lastNamePlural($resource)] as $entity){
            $retrievedEntities[] = API::fromApiJson($resource, $entity);
        }

        return $retrievedEntities;
    }

    public static function postSingle($user, $resource, $entity)
    {
        $payload = API::apiJson($entity);
        $json = Request::fetch($user, "POST", API::endpoint($resource), $payload)->json();
        $entityJson = $json[API::lastName($resource)];
        return API::fromApiJson($resource, $entityJson);
    }

    public static function deleteId($user, $resource, $id)
    {
        $json = Request::fetch($user, "DELETE", API::endpoint($resource) . "/" . $id)->json();
        $entity = $json[API::lastName($resource)];
        return API::fromApiJson($resource, $entity);
    }

    public static function patchId($user, $resource, $id)
    {
        $json = Request::fetch($user, "PATCH", API::endpoint($resource) . "/" . $id)->json();
        $entity = $json[API::lastName($resource)];
        return API::fromApiJson($resource, $entity);
    }
}

?>