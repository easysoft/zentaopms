<?php
declare(strict_types=1);
/**
 * The command class file of zin lib.
 *
 * @copyright   Copyright 2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'helper.func.php';

/**
 * The command class.
 */
class command
{
    public static function addClass(node $node, null|array|string|object ...$args)
    {
        $node->props->class->add(...$args);
        $node->removeBuildData();
    }

    public static function removeClass(node $node, string|array ...$args)
    {
        $node->props->class->remove($args);
        $node->removeBuildData();
    }

    public static function toggleClass(node $node, string $name, ?bool $toggle = null)
    {
        $node->props->class->toggle($name, $toggle);
        $node->removeBuildData();
    }

    public static function prop(node $node, props|array|string $prop, mixed $value = null)
    {
        $node->setProp($prop, $value);
    }

    public static function data(node $node, string|array|object $keyOrData, mixed $value = null)
    {
        $node->add(setData($keyOrData, $value));
    }

    public static function html(node $node, mixed ...$codes)
    {
        $node->empty();
        $node->add(html(...$codes));
    }

    public static function append(node $node, mixed ...$args)
    {
        $node->add($args);
    }

    public static function remove(node $node)
    {
        $node->remove();
    }

    public static function text(node $node, mixed ...$args)
    {
        $node->empty();
        $node->add(text(...$args));
    }

    public static function empty(node $node)
    {
        $node->empty();
    }

    public static function prepend(node $node, mixed ...$args)
    {
        $node->add($args, 'children', true);
    }

    public static function before(node $node, mixed ...$args)
    {
        $node->add($args, 'before');
    }

    public static function after(node $node, mixed ...$args)
    {
        $node->add($args, 'after');
    }

    public static function replaceWith(node $node, mixed ...$args)
    {
        $node->replaceWith(...$args);
    }

    public static function on(node $node, string $event, null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null)
    {
        $node->add(static::bind($event, $selectorOrCallback, $handlerOrOptions), 'children');
    }

    public static function off(node $node, string $event)
    {
        $node->off($event);
    }

    public static function closest(node $node, string|array|object $selectors)
    {
        $node = $node->closest($selectors);
        return $node ? $node : array();
    }

    public static function find(node $node, string|array|object $selectors)
    {
        return $node->find($selectors);
    }

    public static function first(node $node, string|array|object $selectors)
    {
        $node = $node->findFirst($selectors);
        return $node ? $node : array();
    }

    public static function last(node $node, string|array|object $selectors)
    {
        $node = $node->findLast($selectors);
        return $node ? $node : array();
    }

    public static function each(node $node, callable|\Collator $callback)
    {
        if($callback instanceof \Closure) $callback($node);
        else call_user_func($callback, $node);
    }

    /**
     * Magic static method for setting property value.
     *
     * @access public
     * @param  string $name  - Property name.
     * @param  array  $args  - Property values.
     * @return setting
     */
    public static function __callStatic($name, $args)
    {
        if(isDebug())
        {
            triggerError('Command not found: ' . $name);
        }
    }
}
