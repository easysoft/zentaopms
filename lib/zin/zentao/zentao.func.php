<?php
declare(strict_types=1);
/**
 * The helper functions and classes for ZentaoPHP file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'pager.func.php';

class helper extends \helper
{
}

class html extends \html
{
}

if(class_exists('\commonModel'))
{
    class commonModel extends \commonModel
    {
    }

    class common extends \commonModel
    {
    }
}

/**
 * Created by typecasting to object.
 *
 * @link https://php.net/manual/en/reserved.classes.php
 */
class stdClass extends \stdClass
{
}

function createLink(string $moduleName, string $methodName = 'index', string|array $vars = array(), string $viewType = '', bool $onlybody = false): string
{
    if(empty($moduleName)) return '';
    return \helper::createLink($moduleName, $methodName, $vars, $viewType, $onlybody);
}

function inLink(string $methodName = 'index', string|array $vars = '', string $viewType = '', bool $onlybody = false): string
{
    return \inlink($methodName, $vars, $viewType, $onlybody);
}

function zget(array|object $var, string|int|bool $key, mixed $valueWhenNone = false, mixed $valueWhenExists = false): mixed
{
    return \zget($var, $key, $valueWhenNone, $valueWhenExists);
}

function getWebRoot(bool $full = false): string
{
    return \getWebRoot($full);
}

function hasPriv(string $module, string $method, ?object $object = null, string $vars = ''): bool
{
    return \common::hasPriv($module, $method, $object, $vars);
}

function isFieldRequired(?string $name, ?string $requiredFields = null): bool
{
    if(empty($name)) return false;

    if(is_null($requiredFields))
    {
        global $config, $app;
        $moduleName = $app->moduleName;
        $methodName = $app->methodName;
        $requiredFields = $config->$moduleName->$methodName->requiredFields;
    }
    if(isset($requiredFields)) return in_array($name, explode(',', $requiredFields));

    return false;
}

/**
 * Determine whether the request is ajax.
 *
 * @param ?string $type 'zin'|'modal'|'fetch'|null
 */
function isAjaxRequest(?string $type = null): bool
{
    return \helper::isAjaxRequest($type);
}

/**
 * Bind global event listener to widget element.
 *
 * @param  string            $name
 * @param  bool|string|array $callback
 * @param  array             $options
 */
function bind(string $name, bool|string|array $callback, array|string $options = null): directive
{
    if(is_string($options) && is_string($callback))
    {
        $options  = array('selector' => $callback, 'call' => $options);
    }
    elseif(is_array($options))
    {
        $options['call'] = $callback;
    }
    else
    {
        $options = array('callback' => $callback);
    }
    if(str_contains($name, '__'))
    {
        list($name, $flags) = explode('__', $name);
        if(str_contains($flags, 'stop'))    $options['stop']    = true;
        if(str_contains($flags, 'prevent')) $options['prevent'] = true;
        if(str_contains($flags, 'self'))    $options['self']    = true;
        if(str_contains($flags, 'once'))    $options['once']    = true;
    }
    $options['on'] = $name;
    return setData($options);
}

/**
 * Render data to json.
 *
 * @param mixed $data   data.
 * @param int   $flags  json encode flags.
 * @return void
 */
function renderJson(mixed $data, int $flags = 0)
{
    ob_end_flush();
    zin::$rendered = true;
    echo json_encode($data, $flags);
}
