<?php
declare(strict_types=1);
/**
 * The properties setter class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'setting.class.php';
require_once __DIR__ . DS . 'directive.class.php';
require_once __DIR__ . DS . 'context.func.php';

class set extends setting implements iDirective
{
    /**
     * Create an instance, the initialed data can be passed.
     *
     * @access public
     * @param array|object|string $data  Properties list array.
     * @param mixed               $value Property value.
     */
    public function __construct(array|string $data = null, mixed $value = null)
    {
        parent::__construct($data, $value);

        renderInGlobal($this);
    }

    public function apply(node $node, string $blockName): void
    {
        $node->setProp($this->toArray());
    }

    public static function __callStatic($prop, $args): set
    {
        $set = new set();
        if(empty($args)) return $set->set($prop, true);

        if($prop === 'class' || strtolower($prop) === 'classname')
        {
            global $config;
            if($prop === 'class' && isset($config->debug) && $config->debug)
            {
                trigger_error("[ZIN] Use set::className() instead of set::class() to compatible with php 5.4.", E_USER_WARNING);
            }
            return $set->setClass('class', $args);
        }

        /* Compatible with zui prop className. */
        if($prop === '_className')
        {
            return $set->setClass('className', $args);
        }

        /* Support to set url with createLink params. */
        if(($prop === 'url' || $prop === 'href' || $prop === 'link') && count($args) > 1)
        {
            $value = call_user_func_array('\helper::createLink', $args);
        }
        else
        {
            $value = count($args) > 1 ? $args : array_shift($args);
        }

        return $set->set($prop, $value);
    }
}

/**
 * Set widget properties.
 *
 * @param  string|array|props|null $name
 * @param  mixed                   $value
 * @return set
 */
function set(string|array|props|null $name = null, mixed $value = null): set
{
    $set = new set();
    if($name === null) return $set;

    $props = null;
    if($name instanceof props) $props = $name->toArray();
    else if(is_array($name))   $props = $name;
    else if(is_object($name))  $props = (array)$name;
    else if(is_string($name))  $props = array($name => $value);
    $set->set($props);
    return $set;
}


/**
 * Set widget CSS class attribute.
 *
 * @param  mixed ...$class
 * @return set
 */
function setClass(mixed ...$class): set
{
    return set()->setClass('class', ...$class);
}

/**
 * Set widget style attribute.
 *
 * @return set
 */
function setStyle(array|string $name, ?string $value = null): set
{
    return set()->addToMap('style', is_array($name) ? $name : array($name => $value));
}

/**
 * Set widget CSS variable.
 *
 * @return set
 */
function setCssVar(array|string $name, ?string $value = null): set
{
    return set()->addToMap('--', is_array($name) ? $name : array($name => $value));
}

/**
 * Set widget ID attribute.
 *
 * @return ?set
 */
function setID(?string $id = null): set
{
    return set('id', $id);
}

/**
 * Set widget element tag name.
 *
 * @return set
 */
function setTag(string $id): set
{
    return set('tagName', $id);
}

/**
 * Set widget data-* attribute.
 *
 * @param  string|array $name
 * @param  mixed        $value
 * @return set
 */
function setData(null|string|array $name, mixed $value = null): set
{
    if($name === null) return set();
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
