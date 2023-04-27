<?php

namespace StarkBank\CorporateHolder;
use StarkCore\Utils\API;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class Permission extends SubResource
{

    public $ownerId;
    public $ownerType;
    public $ownerEmail;
    public $ownerName;
    public $ownerPictureUrl;
    public $ownerStatus;
    public $created;

    /**
    # CorporateHolder\Permission object
    
    The CorporateHolder\Permission object modifies the behavior of CorporateHolder objects when passed as an argument upon their creation.
    
    ## Parameters (optional):
        - ownerId [string, default null]: owner unique id. ex: "5656565656565656"
        - ownerType [string, default null]: owner type. ex: "project"
        
    ## Parameters (optional):
        - ownerEmail [string]: email address of the owner. ex: "tony@starkbank.com
        - ownerName [string]: name of the owner. ex: "Tony Stark"
        - ownerPictureUrl [string]: Profile picture Url of the owner. ex: ""
        - ownerStatus [string]: current owner status. ex: "active", "blocked", "canceled"
        - created [DateTime]: creation datetime for the CorporateHolder.Permission. 
    */
    function __construct(array $params)
    {
        $this->ownerId = Checks::checkParam($params, "ownerId");
        $this->ownerType = Checks::checkParam($params, "ownerType");
        $this->ownerEmail = Checks::checkParam($params, "ownerEmail");
        $this->ownerName = Checks::checkParam($params, "ownerName");
        $this->ownerPictureUrl = Checks::checkParam($params, "ownerPictureUrl");
        $this->ownerStatus = Checks::checkParam($params, "ownerStatus");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    public static function parsePermissions($permissions) {
        if (is_null($permissions)){
            return null;
        }
        $parsedPermissions = [];
        foreach($permissions as $permission) {
            if($permission instanceof Permission) {
                array_push($parsedPermissions, $permission);
                continue;
            }
            $parsedPermission = function ($array) {
                $permissionMaker = function ($array) {
                    return new Permission($array);
                };
                return API::fromApiJson($permissionMaker, $array);
            };
            array_push($parsedPermissions, $parsedPermission($permission));
        }    
        return $parsedPermissions;
    }
}
