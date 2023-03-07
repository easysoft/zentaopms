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
    return \helper::createLink($moduleName, $methodName, $vars, $viewType);
}

function inLink($methodName = 'index', $vars = '', $viewType = '', $onlybody = false)
{
    return inlink($methodName, $vars, $viewType, $onlybody);
}

function zget($var, $key, $valueWhenNone = false, $valueWhenExists = false)
{
    return \zget($var, $key, $valueWhenNone, $valueWhenExists);
}

function getWebRoot($full = false)
{
    return \getWebRoot($full);
}
