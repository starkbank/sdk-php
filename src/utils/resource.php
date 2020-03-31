<?php

namespace StarkBank\Utils;


class Resource
{
    function __construct($id)
    {
        if (!is_null($id)) {
            $id = strval($id);
        }
        $this->id = $id;
    }
}

?>
