<?php

namespace StarkBank\Utils;


class SubResource
{
    function __toArray() {
        return get_object_vars($this);
    }
}

?>
