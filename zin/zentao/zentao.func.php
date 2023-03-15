<?php
namespace zin;

class helper extends \helper
{
}

class commonModel extends \commonModel
{
}

class common extends \commonModel
{
}

/**
 * Created by typecasting to object.
 *
 * @link https://php.net/manual/en/reserved.classes.php
 */
class stdClass extends \stdClass
{
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

function hasPriv($module, $method, $object = null, $vars = '')
{
    return common::hasPriv($module, $method, $object, $vars);
}
