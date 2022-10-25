<?php

namespace StarkBank;
use Exception;
use StarkCore\Utils\StarkHost;


class Settings
{
    private const apiVersion = "v2";
    private const sdkVersion = "v2.8.4";
    private const host = StarkHost::bank;
    private static $user;
    private static $language = "en-US";
    private static $timeout = 15;

    public static function getUser($user=null)
    {
        return is_null($user) ? self::$user : $user;
    }

    public static function setUser($user)
    {
        self::$user = $user;
    }

    public static function getLanguage()
    {
        return self::$language;
    }

    public static function setLanguage($language)
    {
        $acceptedLanguages = ["en-US", "pt-BR"];
        if (in_array($language, $acceptedLanguages)) {
            self::$language = $language;
            return;
        }
        throw new Exception("language must be one of " . join(", ", $acceptedLanguages));
    }

    public static function getTimeout(){
        return self::$timeout;
    }

    public static function setTimeout($timeout){
        self::$timeout = $timeout;
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
}
