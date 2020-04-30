<?php

namespace StarkBank\Utils;


class Resource
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
