<?php

namespace zin;

require_once 'toggle.func.php';

class toggle
{
    public static function __callStatic($name, $args)
    {
        return toggle($name, empty($args) ? NULL : $args[0]);
    }
}
