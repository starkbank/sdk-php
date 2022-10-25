<?php

namespace StarkBank\Utils;
use StarkBank\Settings;
use StarkBank\Utils\Rest;


class Parse
{
    public static function parseAndVerify($content, $signature, $resource, $user)
    {
        return \StarkCore\Utils\Parse::parseAndVerify(
            $content,
            $signature,
            Rest::getSdkVersion(),
            Rest::getApiVersion(),
            Rest::getHost(),
            $resource,
            Settings::getUser($user),
            Settings::getLanguage(),
            Settings::getTimeout()
        );
    }
}
