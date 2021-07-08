<?php

namespace StarkBank\Utils;

use StarkBank\Utils\Checks;


class Rest
{
    public static function getPage($user, $resource, array $query = [])
    {
        $json = Request::fetch($user, "GET", API::endpoint($resource["name"]), null, $query)->json();
        $entities = []; 
        foreach($json[API::lastNamePlural($resource["name"])] as $entity) {
            array_push($entities, API::fromApiJson($resource["maker"], $entity));
        }
        return [$entities, $json["cursor"]];
    }

    public static function getList($user, $resource, array $query = [])
    {
        $limit = Checks::CheckParam($query, "limit");
        $query["limit"] = is_null($limit) ? null : min($limit, 100);

        while (true) {
            $json = Request::fetch($user, "GET", API::endpoint($resource["name"]), null, $query)->json();
            $entities = $json[API::lastNamePlural($resource["name"])];
            
            foreach($entities as $entity) {
                yield API::fromApiJson($resource["maker"], $entity);
            }

            if (!is_null($limit)) {
                $limit -= 100;
                $query["limit"] = min($limit, 100);
            }

            $cursor = $json["cursor"];
            $query["cursor"] = $cursor;
            if (empty($cursor) | is_null($cursor) | (!is_null($limit) & $limit <= 0)) {
                break;
            }
        }
    }

    public static function getId($user, $resource, $id)
    {
        $id = Checks::checkId($id);
        $json = Request::fetch($user, "GET", API::endpoint($resource["name"]) . "/" . $id)->json();
        $entity = $json[API::lastName($resource["name"])];
        return API::fromApiJson($resource["maker"], $entity);
    }

    public static function getContent($user, $resource, $id, $subresourceName, $options = null)
    {
        $id = Checks::checkId($id);
        $options = API::castJsonToApiFormat($options);
        $path = API::endpoint($resource["name"]) . "/" . $id . "/" . $subresourceName;
        return Request::fetch($user, "GET", $path, null, $options)->content;
    }

    public static function getSubresource($user, $resource, $id, $subresource, $options = null)
    {
        $id = Checks::checkId($id);
        $options = API::castJsonToApiFormat($options);
        $path = API::endpoint($resource["name"]) . "/" . $id . "/" . API::endpoint($subresource["name"]);
        $json = Request::fetch($user, "GET", $path)->json();
        $entity = $json[API::lastName($subresource["name"])];
        return API::fromApiJson($subresource["maker"], $entity);
    }

    public static function post($user, $resource, $entities)
    {
        $entitiesJson = [];
        foreach($entities as $entity){
            $entitiesJson[] = API::apiJson($entity, $resource["name"]);
        }
        $payload = [
            API::lastNamePlural($resource["name"]) => $entitiesJson
        ];

        $json = Request::fetch($user, "POST", API::endpoint($resource["name"]), $payload)->json();

        $retrievedEntities = [];
        foreach($json[API::lastNamePlural($resource["name"])] as $entity){
            $retrievedEntities[] = API::fromApiJson($resource["maker"], $entity);
        }

        return $retrievedEntities;
    }

    public static function postSingle($user, $resource, $entity)
    {
        $payload = API::apiJson($entity);
        $json = Request::fetch($user, "POST", API::endpoint($resource["name"]), $payload)->json();
        $entityJson = $json[API::lastName($resource["name"])];
        return API::fromApiJson($resource["maker"], $entityJson);
    }

    public static function deleteId($user, $resource, $id)
    {
        $id = Checks::checkId($id);
        $json = Request::fetch($user, "DELETE", API::endpoint($resource["name"]) . "/" . $id)->json();
        $entity = $json[API::lastName($resource["name"])];
        return API::fromApiJson($resource["maker"], $entity);
    }

    public static function patchId($user, $resource, $id, $payload = [])
    {
        $id = Checks::checkId($id);
        $json = Request::fetch($user, "PATCH", API::endpoint($resource["name"]) . "/" . $id, API::castJsonToApiFormat($payload, $resource["name"]))->json();
        $entity = $json[API::lastName($resource["name"])];
        return API::fromApiJson($resource["maker"], $entity);
    }
}

?>