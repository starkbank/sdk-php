<?php

namespace StarkBank\Utils;
use StarkBank\Settings;


class Parse
{
    public static function parseAndVerify($content, $signature, $resource, $user)
    {
        return \StarkCore\Utils\Parse::parseAndVerify(
            $content,
            $signature,
            Settings::getSdkVersion(),
            Settings::getApiVersion(),
            Settings::getHost(),
            $resource,
            Settings::getUser($user),
            Settings::getLanguage(),
            Settings::getTimeout()
        );
    }
}
