<?php

namespace StarkBank;
use Exception;


class Settings
{
    private static $user;
    private static $language = "en-US";

    public static function getUser()
    {
        return self::$user;
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
}
