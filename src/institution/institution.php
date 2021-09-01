<?php

namespace StarkBank;
use StarkBank\Utils\SubResource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Institution extends SubResource
{
    function __construct(array $params)
    {
        $this->displayName = Checks::checkParam($params, "displayName");
        $this->name = Checks::checkParam($params, "name");
        $this->spiCode = Checks::checkParam($params, "spiCode");
        $this->strCode = Checks::checkParam($params, "strCode");

        Checks::checkParams($params);
    }

    /**
    # Retrieve Bacen Institutions

    Receive a list of Institution objects that are recognized by the Brazilian Central bank for Pix and TED transactions
    
    ## Parameters (optional):
    - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
    - search [string, default null]: part of the institution name to be searched. ex: "stark"
    - spiCodes [list of strings, default null]: list of SPI (Pix) codes to be searched. ex: ["20018183"]
    - strCodes [list of strings, default null]: list of STR (TED) codes to be searched. ex: ["260"]
    - user [Organization/Project object, default null, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
    - list of Institution objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getPage($user, Institution::resource(), $options)[0];
    }

    private static function resource()
    {
        $institution = function ($array) {
            return new Institution($array);
        };
        return [
            "name" => "Institution",
            "maker" => $institution,
        ];
    }

}