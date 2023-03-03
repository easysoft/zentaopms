<?php
namespace zin;

class helper
{
    public static function __callStatic($prop, $args)
    {
        return call_user_func_array(array('\helper', $prop), $args);
    }
}

class commonModel
{
    public static function __callStatic($prop, $args)
    {
        return call_user_func_array(array('\commonModel', $prop), $args);
    }
}

function createLink($moduleName, $methodName = 'index', $vars = '', $viewType = 'json')
{
    \helper::createLink($moduleName, $methodName, $vars, $viewType);
}
