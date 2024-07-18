<?php

namespace StarkBank\Utils;
use StarkBank\Settings;
use StarkCore\Utils\StarkHost;


class Rest
{
    private const apiVersion = "v2";
    private const sdkVersion = "2.17.0";
    private const host = StarkHost::bank;

    public static function getPage($user, $resource, array $query = [])
    {
        return \StarkCore\Utils\Rest::getPage(
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(), 
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
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
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $id,
            Settings::getLanguage(),
            Settings::getTimeout(),
            $payload
        );
    }

    public static function putMulti($user, $resource, $entities)
    {
        return \StarkCore\Utils\Rest::putMulti(
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
            Settings::getUser($user),
            $resource,
            $entities,
            Settings::getLanguage(),
            Settings::getTimeout(),
            []
        );
    }

    public static function getSdkVersion()
    {
        return self::sdkVersion;
    }

    public static function getApiVersion()
    {
        return self::apiVersion;
    }

    public static function getHost()
    {
        return self::host;
    }

    public static function postRaw($user, $path, $payload, $prefix = null,  $throwError = true, $query = null)
    {
        return \StarkCore\Utils\Rest::postRaw(
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
            Settings::getUser($user),
            Settings::getLanguage(),
            Settings::getTimeout(),
            $path,
            $payload,
            $query,
            $prefix,
            $throwError
        );
    }

    public static function getRaw($user, $path, $query = null, $prefix = null,  $throwError = true, $payload = null)
    {
        return \StarkCore\Utils\Rest::getRaw(
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
            Settings::getUser($user),
            Settings::getLanguage(),
            Settings::getTimeout(),
            $path,
            $payload,
            $query,
            $prefix,
            $throwError
        );
    }

    public static function patchRaw($user, $path, $payload, $prefix = null,  $throwError = true, $query = null)
    {
        return \StarkCore\Utils\Rest::patchRaw(
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
            Settings::getUser($user),
            Settings::getLanguage(),
            Settings::getTimeout(),
            $path,
            $payload,
            $query,
            $prefix,
            $throwError
        );
    }

    public static function putRaw($user, $path, $payload, $prefix = null,  $throwError = true, $query = null)
    {
        return \StarkCore\Utils\Rest::putRaw(
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
            Settings::getUser($user),
            Settings::getLanguage(),
            Settings::getTimeout(),
            $path,
            $payload,
            $query,
            $prefix,
            $throwError
        );
    }

    public static function deleteRaw($user, $path, $payload, $prefix = null,  $throwError = true, $query = null)
    {
        return \StarkCore\Utils\Rest::deleteRaw(
            self::getSdkVersion(),
            self::getHost(),
            self::getApiVersion(),
            Settings::getUser($user),
            Settings::getLanguage(),
            Settings::getTimeout(),
            $path,
            $payload,
            $query,
            $prefix,
            $throwError
        );
    }
}
