<?php
declare(strict_types=1);
/**
 * The widget function file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once __DIR__ . DS . 'props.class.php';
require_once __DIR__ . DS . 'directive.class.php';
require_once __DIR__ . DS . 'setting.class.php';
require_once __DIR__ . DS . 'rawcontent.class.php';
require_once __DIR__ . DS . 'wg.class.php';
require_once __DIR__ . DS . 'context.func.php';

/**
 * Create an new widget.
 *
 * @return wg
 */
function wg(): wg
{
    return new wg(func_get_args());
}

/**
 * Set widget properties.
 *
 * @param  string|array|props|null $name
 * @param  mixed                   $value
 * @return directive|null
 */
function set(string|array|props|null $name, mixed $value = null): ?directive
{
    if($name === null) return null;

    $props = null;
    if($name instanceof props) $props = $name;
    else if(is_array($name))   $props = $name;
    else if(is_object($name))  $props = (array)$name;
    else if(is_string($name))  $props = array($name => $value);
    return $props ? directive('prop', $props) : null;
}

/**
 * Set widget CSS class attribute.
 *
 * @param  array|string|null ...$classList
 * @return directive
 */
function setClass(/* array|string|null ...$classList */): directive
{
    return directive('class', func_get_args());
}

/**
 * Set widget style attribute.
 *
 * @return directive
 */
function setStyle(array|string $name, ?string $value = null): directive
{
    return directive('style', is_array($name) ? $name : array($name => $value));
}

/**
 * Set widget CSS variable.
 *
 * @return directive
 */
function setCssVar(array|string $name, ?string $value = null): directive
{
    return directive('cssVar', is_array($name) ? $name : array($name => $value));
}

/**
 * Set widget ID attribute.
 *
 * @return ?directive
 */
function setID(?string $id = null): directive
{
    return set('id', $id);
}

/**
 * Set widget element tag name.
 *
 * @return directive
 */
function setTag(string $id): directive
{
    return set('tagName', $id);
}

/**
 * Set widget data-* attribute.
 *
 * @param  string|array $name
 * @param  mixed        $value
 * @return directive
 */
function setData(string|array $name, mixed $value = null): directive
{
    $map   = is_array($name) ? $name : array($name => $value);
    $attrs = array();
    foreach($map as $key => $value)
    {
        if(is_numeric($key)) $key = (string)$key;
        $name = 'data-' . strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $key));
        if(is_bool($value))       $attrs[$name] = $value ? 'true' : 'false';
        else if(is_array($value)) $attrs[$name] = json_encode($value);
        else                      $attrs[$name] = $value;
    }
    return set($attrs);
}

/**
 * Add event listener to widget element.
 *
 * @param  string            $name
 * @param  bool|string|array $handler
 * @param  array             $options
 */
function on(string $name, bool|string|array $handler, array|string|bool $options = null): directive
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

/**
 * Create html content.
 *
 * @param  string ...$lines
 * @return directive
 */
function html(/* string ...$lines */): directive
{
    return directive('html', implode("\n", \zin\utils\flat(func_get_args())));
}

/**
 * Create text content.
 *
 * @param  string ...$lines
 * @return directive
 */
function text(/* string ...$lines */): directive
{
    return directive('text', implode("\n", \zin\utils\flat(func_get_args())));
}

/**
 * Create block content.
 *
 * @param  string       $name
 * @param  mixed        ...$wgs
 * @return directive
 */
function to(/* string $name, mixed ...$wgs */): directive
{
    $args  = func_get_args();
    $name  = array_shift($args);
    $wg    = new wg(count($args) > 1 ? $args : $args[0]);
    return directive('block', array($name => $wg));
}

/**
 * Create content for block "before".
 *
 * @param  string       $wgs
 * @return directive
 */
function before(/* mixed ...$wgs */): directive
{
    return to('before', func_get_args());
}

/**
 * Create content for block "after".
 *
 * @param  string       $wgs
 * @return directive
 */
function after(): directive
{
    return to('after', func_get_args());
}

/**
 * Create widget contents inherited from the given widget.
 *
 * @param  wg|array $item
 * @return array
 */
function inherit(wg|array $item): array
{
    if(!($item instanceof wg)) $item = new wg($item);
    return array(set($item->props), directive('block', $item->blocks), $item->children());
}

/**
 * Divorce widget from parent.
 *
 * @param  wg|array $item
 * @return array
 */
function divorce(wg|array $item): wg|array
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

/**
 * Check if the given widget list has the given widget type.
 *
 * @param  wg|array $items
 * @param  string   $type
 * @return bool
 */
function hasWgInList(wg|array $items, string $type): bool
{
    if(!is_array($items)) $items = array($items);
    foreach($items as $item)
    {
        if($item instanceof wg && $item->type() == $type) return true;
    }
    return false;
}

/**
 * Group widgets by type.
 *
 * @param  wg|array $items
 * @param  string   $types
 * @return array
 */
function groupWgInList(wg|array $items, string|array $types): array
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

/**
 * Create raw content placeholder.
 *
 * @return rawContent
 */
function rawContent(): rawContent
{
    zin::$rawContentCalled = true;

    return new rawContent();
}

/**
 * Include hooks files.
 */
function includeHooks()
{
    $hookFiles = context::current()->getHookFiles();
    ob_start();
    foreach($hookFiles as $hookFile)
    {
        if(!empty($hookFile) && file_exists($hookFile)) include $hookFile;
    }
    $hookCode = ob_get_clean();
    return html($hookCode);
}
