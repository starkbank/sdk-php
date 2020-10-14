<?php

namespace Test\Workspace;

use \Exception;
use StarkBank\Workspace;

class Test
{
    public function queryAndGet()
    {
        $workspaces = iterator_to_array(Workspace::query(["limit" => 30]));

        if (count($workspaces) == 0) {
            throw new Exception("failed");
        }

        foreach($workspaces as $workspace) {
            $prevWorkspace = $workspace;
            $workspace = Workspace::get($workspace->id);

            if ($workspace->id != $prevWorkspace->id){
                throw new Exception("failed");
            }
        }
    }
}

echo "\nWorkspace:";

$test = new Test();

echo "\n\t- query and get";
$test->queryAndGet();
echo " - OK";