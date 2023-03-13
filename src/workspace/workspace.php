<?php

namespace StarkBank;

use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;


class Workspace extends Resource
{

    public $username;
    public $name;
    public $allowedTaxIds;
    public $status;
    public $organizationId;
    public $pictureUrl;
    public $created;

    /**
    # Workspace object
    
    Workspaces are bank accounts. They have independent balances, statements, operations and permissions.
    The only property that is shared between your workspaces is that they are linked to your organization,
    which carries your basic informations, such as tax ID, name, etc..
    
    ## Parameters (required):
        - username [string]: Simplified name to define the workspace URL. This name must be unique across all Stark Bank Workspaces. Ex: "starkbankworkspace"
        - name [string]: Full name that identifies the Workspace. This name will appear when people access the Workspace on our platform, for example. Ex: "Stark Bank Workspace"
    
    ## Parameters (optional):
        - allowedTaxIds [list of strings]: list of tax IDs that will be allowed to send Deposits to this Workspace. ex: ["012.345.678-90", "20.018.183/0001-80"]
    
    ## Attributes (return-only):
        - id [string]: unique id returned when the workspace is created. ex: "5656565656565656"
        - status [string]: current Workspace status. Options: "active", "closed", "frozen" or "blocked"
        - organizationId [string]: unique organization id returned when the organization is created. ex: "5656565656565656"
        - pictureUrl [string]: public workspace image (png) URL. ex: "https://storage.googleapis.com/api-ms-workspace-sbx.appspot.com/pictures/workspace/6284441752174592.png?20230208220551"
        - created [DateTime]: creation datetime for the payment.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->username = Checks::checkParam($params, "username");
        $this->name = Checks::checkParam($params, "name");
        $this->allowedTaxIds = Checks::checkParam($params, "allowedTaxIds");
        $this->status = Checks::checkParam($params, "status");
        $this->organizationId = Checks::checkParam($params, "organizationId");
        $this->pictureUrl = Checks::checkParam($params, "pictureUrl");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));

        Checks::checkParams($params);
    }

    /**
    # Create Workspace
    
    Send a Workspace for creation in the Stark Bank API
    
    ## Parameters (required):
        - username [string]: Simplified name to define the workspace URL. This name must be unique across all Stark Bank Workspaces. Ex: "starkbankworkspace"
        - name [string]: Full name that identifies the Workspace. This name will appear when people access the Workspace on our platform, for example. Ex: "Stark Bank Workspace"
    
    ## Parameters (optional):
        - user [Organization object]: Organization object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - Workspace object with updated attributes
     */
    public static function create(array $params, $user = null)
    {
        return Rest::postSingle($user, Workspace::resource(), new Workspace($params));
    }

    /** 
    # Retrieve a specific Workspace
    
    Receive a single Workspace object previously created in the Stark Bank API by passing its id
    
    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - Workspace object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Workspace::resource(), $id);
    }

    /**
    # Retrieve Workspaces

    Receive a enumerator of Workspace objects previously created in the Stark Bank API.
    If no filters are passed and the user is an Organization, all of the Organization Workspaces
    will be retrieved.

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - username [string, default null]: query by the simplified name that defines the workspace URL. This name is always unique across all Stark Bank Workspaces. Ex: "starkbankworkspace"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - enumerator of Workspace objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        return Rest::getList($user, Workspace::resource(), $options);
    }

    /**
    # Retrieve paged Workspace

    Receive a list of up to 100 Workspace objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - username [string, default null]: query by the simplified name that defines the workspace URL. This name is always unique across all Stark Bank Workspaces. Ex: "starkbankworkspace"
        - ids [list of strings, default null]: list of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - list of Workspace objects with updated attributes
        - cursor to retrieve the next page of Workspace objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, Workspace::resource(), $options);
    }

    /**
    # Update Workspace entity

    Update a Workspace by passing its ID.

    ## Parameters (required):
        - id [string]: Workspace ID. ex: '5656565656565656'

    ## Parameters (conditionally required):
        - pictureType [string]: picture MIME type. This parameter will be required if the picture parameter is informed ex: "image/png" or "image/jpeg"

    ## Parameters (optional):
        - username [string]: Simplified name to define the workspace URL. This name must be unique across all Stark Bank Workspaces. Ex: "starkbank-workspace"
        - name [string]: Full name that identifies the Workspace. This name will appear when people access the Workspace on our platform, for example. Ex: "Stark Bank Workspace"
        - allowedTaxIds [list of strings, default []]: list of tax IDs that will be allowed to send Deposits to this Workspace. If empty, all are allowed. ex: ["012.345.678-90", "20.018.183/0001-80"]
        - picture [list of bytes, default null]: Binary buffer of the picture. ex: file_get_contents('myprofilepic.jpg');
        - status [string, default null]: current Workspace status. Options: "active" or "blocked"
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call
    
    ## Return:
        - target Workspace with updated attributes
     */
    public static function update($id, $options = [], $user = null)
    {
        if ($options["picture"] != null){
            $options["picture"] = "data:" . $options["pictureType"] . ";base64," . base64_encode($options["picture"]);
            unset($options["pictureType"]);
        }

        return Rest::patchId($user, Workspace::resource(), $id, $options);
    }

    private static function resource() 
    {
        $workspace = function ($array){
            return new Workspace($array);
        };
        return [
            "name" => "Workspace",
            "maker" => $workspace,
        ];
    }
}
