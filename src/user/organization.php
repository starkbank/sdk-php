<?php

namespace StarkBank;

use StarkBank\Utils\Checks;

class Organization extends User
{
    /**
    # Organization object

    The Organization object is an authentication entity for the SDK that
    represents your entire Organization, being able to access any Workspace
    underneath it and even create new Workspaces. Only a legal representative
    of your organization can register or change the Organization credentials.
    All requests to the Stark Bank API must be authenticated via an SDK user,
    which must have been previously created at the Stark Bank website
    [https://web.sandbox.starkbank.com] or [https://web.starkbank.com]
    before you can use it in this SDK. Organizations may be passed as the user parameter on
    each request or may be defined as the default user at the start (See README).
    If you are accessing a specific Workspace using Organization credentials, you should
    specify the workspace ID when building the Organization object or by request, using
    the Organization::replace($organization, $workspaceId) method, which creates a copy of the organization
    object with the altered workspace ID. If you are listing or creating new Workspaces, the
    $workspaceId should be null.
    
    ## Parameters (required):
        - id [string]: unique id required to identify organization. ex: "5656565656565656"
        - privateKey [string]: PEM string of the private key linked to the organization. ex: "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEyTIHK6jYuik6ktM9FIF3yCEYzpLjO5X/\ntqDioGM+R2RyW0QEo+1DG8BrUf4UXHSvCjtQ0yLppygz23z0yPZYfw==\n-----END PUBLIC KEY-----"
        - environment [string]: environment where the organization is being used. ex: "sandbox" or "production"
        - workspaceId [string]: unique id of the accessed Workspace, if any. ex: null or "4848484848484848"

    ## Attributes (return-only):
        - pem [string]: private key in pem format. ex: "-----BEGIN PUBLIC KEY-----\nMFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEyTIHK6jYuik6ktM9FIF3yCEYzpLjO5X/\ntqDioGM+R2RyW0QEo+1DG8BrUf4UXHSvCjtQ0yLppygz23z0yPZYfw==\n-----END PUBLIC KEY-----"
    */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->workspaceId = Checks::checkParam($params, "workspaceId");

        Checks::checkParams($params);
    }

    public function accessId()
    {
        if ($this->workspaceId)
            return "organization/" . $this->id . "/workspace/" . $this->workspaceId;
        return "organization/" . $this->id;
    }

    public static function replace($organization, $workspaceId)
    {
        return new Organization([
            "environment" => $organization->environment,
            "id" => $organization->id,
            "privateKey" => $organization->pem,
            "workspaceId" => $workspaceId
        ]);
    }
}
