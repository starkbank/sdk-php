<?php

namespace StarkBank\DynamicBrcode;
use StarkCore\Utils\API;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class Rule extends SubResource
{

    public $key;
    public $value;

    /**
    # DynamicBrcode\Rule object
    
    The DynamicBrcode\Rule object modifies the behavior of DynamicBrcode objects when passed as an argument upon their creation.
    
    ## Parameters (required):
        - key [string]: Rule to be customized, describes what DynamicBrcode behavior will be altered. ex: "allowedTaxIds"
        - value [list of string]: Value of the rule. ex: ["012.345.678-90", "45.059.493/0001-73"]
    */
    function __construct(array $params)
    {
        $this->key = Checks::checkParam($params, "key");
        $this->value = Checks::checkParam($params, "value");

        Checks::checkParams($params);
    }

    public static function parseRules($rules) {
        if (is_null($rules)){
            return null;
        }
        $parsedRules = [];
        foreach($rules as $rule) {
            if($rule instanceof Rule) {
                array_push($parsedRules, $rule);
                continue;
            }
            $parsedRule = function ($array) {
                $ruleMaker = function ($array) {
                    return new Rule($array);
                };
                return API::fromApiJson($ruleMaker, $array);
            };
            array_push($parsedRules, $parsedRule($rule));
        }    
        return $parsedRules;
    }
}
