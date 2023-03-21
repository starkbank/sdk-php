<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class Institution extends SubResource
{

    public $displayName;
    public $name;
    public $spiCode;
    public $strCode;

    /**
    # Institution object

    This resource is used to get information on the institutions that are recognized by the Brazilian Central Bank.
    Besides the display name and full name, they also include the STR code (used for TEDs) and the SPI Code
    (used for Pix) for the institutions. Either of these codes may be empty if the institution is not registered on
    that Central Bank service.

    ## Attributes (return-only):
        - displayName [string]: short version of the institution name that should be displayed to end users. ex: "Stark Bank"
        - name [string]: full version of the institution name. ex: "Stark Bank S.A."
        - spiCode [string]: SPI code used to identify the institution on Pix transactions. ex: "20018183"
        - strCode [string]: STR code used to identify the institution on TED transactions. ex: "123"
     */
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
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
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