<?php

namespace StarkBank\Utils;


class Rest
{
    public static function getList($user, $resource, $limit = null, array $query = [])
    {
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
            if (is_null($cursor) | (!is_null($limit) & $limit <= 0)) {
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

    public static function getPdf($user, $resource, $id)
    {
        $id = Checks::checkId($id);
        return Request::fetch($user, "GET", API::endpoint($resource["name"]) . "/" . $id . "/pdf")->content;
    }

    public static function post($user, $resource, $entities)
    {
        $entitiesJson = [];
        foreach($entities as $entity){
            $entitiesJson[] = API::apiJson($entity);
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
        $json = Request::fetch($user, "PATCH", API::endpoint($resource["name"]) . "/" . $id, API::castJsonToApiFormat($payload))->json();
        $entity = $json[API::lastName($resource["name"])];
        return API::fromApiJson($resource["maker"], $entity);
    }
}

?>