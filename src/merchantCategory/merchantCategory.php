<?php

namespace StarkBank;
use StarkCore\Utils\API;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class MerchantCategory extends SubResource
{

    public $code;
    public $type;
    public $name;
    public $number;

    /**
    # MerchantCategory object

    MerchantCategory's codes and types are used to define category filters in CorporateRules.
    A MerchantCategory filter must define exactly one parameter between code and type.
    A type, such as "food", "services", etc., defines an entire group of merchant codes,
    whereas a code only specifies a specific MCC.

    ## Parameters (conditionally required):
        - code [string, default null]: category's code. ex: "veterinaryServices", "fastFoodRestaurants"
        - type [string, default null]: category's type. ex: "pets", "food"

    ## Attributes (return-only):
        - name [string]: category's name. ex: "Veterinary services", "Fast food restaurants"
        - number [string]: category's number. ex: "742", "5814"
     */
    function __construct(array $params)
    {
        $this-> code = Checks::checkParam($params, "code");
        $this-> type = Checks::checkParam($params, "type");
        $this-> name = Checks::checkParam($params, "name");
        $this-> number = Checks::checkParam($params, "number");

        Checks::checkParams($params);
    }

    /**
    # Retrieve MerchantCategories

    Receive an enumerator of MerchantCategory objects available in the Stark Bank API

    ## Parameters (optional):
        - search [string, default null]: keyword to search for code, type, name or number. ex: "pets"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of MerchantCategory objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, MerchantCategory::resource(), $options);
    }

    public static function parseCategories($categories) {
        if (is_null($categories)){
            return [];
        }
        $parsedCategories = [];
        foreach($categories as $category) {
            if($category instanceof MerchantCategory) {
                array_push($parsedCategories, $category);
                continue;
            }
            $parsedCategory = function ($array) {
                $categoryMaker = function ($array) {
                    return new MerchantCategory($array);
                };
                return API::fromApiJson($categoryMaker, $array);
            };
            array_push($parsedCategories, $parsedCategory($category));
        }    
        return $parsedCategories;
    }

    private static function resource()
    {
        $category = function ($array) {
            return new MerchantCategory($array);
        };
        return [
            "name" => "MerchantCategory",
            "maker" => $category,
        ];
    }
}
