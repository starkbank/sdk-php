<?php

namespace StarkBank\Utils;
use StarkBank\Settings;


class Rest
{
    public static function getPage($user, $resource, array $query = [])
    {
        return \StarkCore\Utils\Rest::getPage(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            Settings::getLanguage(),
            Settings::getTimeout(),
            $query
        );
    }

    public static function getList($user, $resource, array $query = [])
    {
        return \StarkCore\Utils\Rest::getList(
            Settings::getSdkVersion(), 
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            Settings::getLanguage(),
            Settings::getTimeout(),
            $query
        );
    }

    public static function getId($user, $resource, $id)
    {
        return \StarkCore\Utils\Rest::getId(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $id,
            Settings::getLanguage(),
            Settings::getTimeout(),
            []
        );
    }

    public static function getContent($user, $resource, $id, $subresourceName, $options = null)
    {
        return \StarkCore\Utils\Rest::getContent(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $id,
            $subresourceName,
            Settings::getLanguage(),
            Settings::getTimeout(),
            $options
        );
    }

    public static function getSubresource($user, $resource, $id, $subresource, $options = null)
    {
        return \StarkCore\Utils\Rest::getSubresource(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $id,
            $subresource,
            Settings::getLanguage(),
            Settings::getTimeout(),
            $options
        );
    }

    public static function post($user, $resource, $entities)
    {
        return \StarkCore\Utils\Rest::post(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $entities,
            Settings::getLanguage(),
            Settings::getTimeout(),
            []
        );
    }

    public static function postSingle($user, $resource, $entity)
    {
        return \StarkCore\Utils\Rest::postSingle(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $entity,
            Settings::getLanguage(),
            Settings::getTimeout()
        );
    }

    public static function deleteId($user, $resource, $id)
    {
        return \StarkCore\Utils\Rest::deleteId(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $id,
            Settings::getLanguage(),
            Settings::getTimeout()
        );
    }

    public static function patchId($user, $resource, $id, $payload = [])
    {
        return \StarkCore\Utils\Rest::patchId(
            Settings::getSdkVersion(),
            Settings::getHost(),
            Settings::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $id,
            Settings::getLanguage(),
            Settings::getTimeout(),
            $payload
        );
    }
}
