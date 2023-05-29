<?php
/**
 * The wg helper methods file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once __DIR__ . DS . 'props.class.php';
require_once __DIR__ . DS . 'directive.class.php';
require_once __DIR__ . DS . 'wg.class.php';
require_once __DIR__ . DS . 'context.func.php';

function set($name, $value = null)
{
    if($name === null) return null;

    $props = null;
    if($name instanceof props) $props = $name;
    else if(is_array($name)) $props = $name;
    else if(is_object($name))  $props = (array)$name;
    else if(is_string($name)) $props = array($name => $value);
    if($props) return directive('prop', $props);
}

function prop($name, $value = null)
{
    return set($name, $value);
}

function setClass()
{
    return directive('class', func_get_args());
}

function setStyle($name, $value = null)
{
    return directive('style', is_array($name) ? $name : array($name => $value));
}

function setCssVar($name, $value = null)
{
    return directive('cssVar', is_array($name) ? $name : array($name => $value));
}

function setID($id)
{
    return prop('id', $id);
}

function setTag($id)
{
    return prop('tagName', $id);
}

function on($name, $handler, $options = null)
{
    if(is_string($options) && is_string($handler))
    {
        $options  = array('selector' => $handler, 'handler' => $options);
    }
    elseif(is_bool($options))
    {
        $options = array('capture' => $options, 'handler' => $handler);
    }
    elseif(is_array($options))
    {
        $options['handler'] = $handler;
    }
    else
    {
        $options = array('handler' => $handler);
    }
    if(str_contains($name, '__'))
    {
        list($name, $flags) = explode('__', $name);
        if(str_contains($flags, 'capture')) $options['capture'] = true;
        if(str_contains($flags, 'stop'))    $options['stop']    = true;
        if(str_contains($flags, 'prevent')) $options['prevent'] = true;
        if(str_contains($flags, 'self'))    $options['self']    = true;
    }
    return set("@$name", (object)$options);
}

function html(/* string ...$lines */)
{
    return directive('html', implode("\n", \zin\utils\flat(func_get_args())));
}

function text(/* string ...$lines */)
{
    return directive('text', implode("\n", \zin\utils\flat(func_get_args())));
}

function block($name, $value = null)
{
    return directive('block', is_array($name) ? $name : array($name => $value));
}

function to($name, $value = null)
{
    return block($name, $value);
}

function before()
{
    return directive('block', array('before' => func_get_args()));
}

function after()
{
    return directive('block', array('after' => func_get_args()));
}

function inherit($item)
{
    if(!($item instanceof wg)) $item = new wg($item);
    return array(set($item->props), directive('block', $item->blocks), $item->children());
}

function divorce($item)
{
    if($item instanceof wg)
    {
        $item->parent = null;
    }
    else if(is_array($item))
    {
        foreach($item as $i) divorce($i);
    }
    return $item;
}

function hasWgInList($items, $type)
{
    if(!is_array($items)) $items = array($items);
    foreach($items as $item)
    {
        if($item instanceof wg && $item->type() == $type) return true;
    }
    return false;
}

function groupWgInList($items, $types)
{
    if(is_string($types)) $types = explode(',', $types);
    $typesMap = array();
    $restList = array();

    foreach($types as $type) $typesMap[$type] = array();

    foreach($items as $item)
    {
        if(!($item instanceof wg)) continue;

        $type = $item->shortType();
        if(isset($typesMap[$type])) $typesMap[$type][] = $item;
        else $restList[] = $item;
    }

    $groups = array();
    foreach($types as $index => $type) $groups[] = $typesMap[$type];
    $groups[] = $restList;
    return $groups;
}
