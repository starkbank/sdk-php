<?php

namespace StarkBank\Utils;

use StarkBank\Utils\SubResource;


class Resource extends SubResource
{
    function __construct(&$params)
    {
        $id = Checks::checkParam($params, "id");
        if (!is_null($id)) {
            $id = strval($id);
        }
        $this->id = $id;
    }
}

?>
