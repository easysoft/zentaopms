<?php
declare(strict_types=1);
/**
 * The context function file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'context.class.php';

function context(): context
{
    return context::current();
}

function contextBegin(string $name)
{
    return context::create($name);
}

function contextEnd()
{
    return context::pop();
}

function enableGlobalRender()
{
    context::current()->enableGlobalRender();
}

function disableGlobalRender()
{
    context::current()->disableGlobalRender();
}

function renderInGlobal(node|iDirective $item): bool
{
    return context::current()->renderInGlobal($item);
}

function onBuildNode(callable $callback)
{
    return context::current()->onBuildNode($callback);
}

function onRenderNode(callable $callback)
{
    return context::current()->onRenderNode($callback);
}

function onBeforeBuildNode(callable $callback)
{
    return context::current()->onBeforeBuildNode($callback);
}

function onRender(callable $callback)
{
    return context::current()->onRender($callback);
}

function pageJS()
{
    call_user_func_array('\zin\context::js', func_get_args());
}

function jsCall()
{
    call_user_func_array('\zin\context::jsCall', func_get_args());
}

function jsVar()
{
    call_user_func_array('\zin\context::jsVar', func_get_args());
}

function css()
{
    call_user_func_array('\zin\context::css', func_get_args());
}

function import()
{
    call_user_func_array('\zin\context::import', func_get_args());
}

function setPageData($name, $value)
{
    $context = context::current();
    if(is_array($value) && empty($name))
    {
        foreach ($value as $key => $val) $context->setData($key, $val);
        return;
    }
    $context->setData($name, $value);
}

function getPageData($name)
{
    $context = context::current();
    if(is_array($name))
    {
        $values = array();
        foreach($name as $propName)
        {
            $values[] = $context->getData($propName);
        }
        return $values;
    }

    return $context->getData($name);
}

function data()
{
    $args = func_get_args();

    if(count($args) >= 2) return setPageData($args[0], $args[1]);
    return getPageData($args[0]);
}
