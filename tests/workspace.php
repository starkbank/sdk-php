<?php

namespace Test\Workspace;

use \Exception;
use StarkBank\Workspace;
use StarkBank\Organization;

class Test
{
    public function create()
    {
        $workspace = self::example();

        $workspace = Workspace::create(
            ["username" => $workspace->username, "name" => $workspace->name],
            self::exampleOrganization(),
        );

        if (is_null($workspace->id) | is_null($workspace->username) | is_null($workspace->name)) {
            throw new Exception("failed");
        }
    }

    public function queryAndGet()
    {
        $organization = self::exampleOrganization();

        $workspaces = iterator_to_array(Workspace::query(["limit" => 2], $organization));

        if (count($workspaces) == 0) {
            throw new Exception("failed");
        }

        foreach($workspaces as $workspace) {
            $prevWorkspace = $workspace;
            $workspace = Workspace::get($workspace->id, Organization::replace($organization, $workspace->id));

            if ($workspace->id != $prevWorkspace->id){
                throw new Exception("failed");
            }
        }
    }

    private static function example()
    {
        $uuid = mt_rand(0, 0xffffffff);

        return new Workspace([
            "username" => "starkv2-" . $uuid,
            "name" => "Stark V2: " . $uuid,
        ]);
    }

    private static function exampleOrganization()
    {
        $organizationId = $_SERVER["SANDBOX_ORGANIZATION_ID"];
        $privateKey = $_SERVER["SANDBOX_ORGANIZATION_PRIVATE_KEY"];

        return new Organization([
            "environment" => "sandbox",
            "id" => $organizationId,
            "privateKey" => $privateKey
        ]);
    }
}

echo "\nWorkspace:";

$test = new Test();

echo "\n\t- create";
$test->create();
echo " - OK";

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";
