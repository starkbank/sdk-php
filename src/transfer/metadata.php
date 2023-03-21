<?php

namespace StarkBank\Transfer;
use StarkCore\Utils\Checks;
use StarkCore\Utils\API;
use StarkCore\Utils\SubResource;


class Metadata extends SubResource
{

    public $authentication;

    /**
    # Transfer\Metadata object
    
    The Transfer\Metadata object contains additional information about the Transfer object.
    
    ## Parameters (required):
        - authentication [string]: Central Bank's unique ID for Pix transactions (EndToEndID). ex: "E200181832023031715008Scr7tD63TS"
    */
    function __construct(array $params)
    {
        $this->authentication = Checks::checkParam($params, "authentication");

        Checks::checkParams($params);
    }

    public static function parseRule($rule) {
        $ruleMaker = function ($array) {
            return new Rule($array);
        };
        return API::fromApiJson($ruleMaker, $rule);
    }
}
