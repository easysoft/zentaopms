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
    public static function addClass(array $nodes, null|array|string|object ...$args)
    {
        foreach($nodes as $node)
        {
            $node->props->class->add(...$args);
            $node->removeBuildData();
        }
    }

    public static function removeClass(array $nodes, string|array ...$args)
    {
        foreach($nodes as $node)
        {
            $node->props->class->remove($args);
            $node->removeBuildData();
        }
    }

    public static function toggleClass(array $nodes, string $name, ?bool $toggle = null)
    {
        foreach($nodes as $node)
        {
            $node->props->class->toggle($name, $toggle);
            $node->removeBuildData();
        }
    }

    public static function prop(array $nodes, props|array|string $prop, mixed $value = null)
    {
        foreach($nodes as $node)
        {
            $node->setProp($prop, $value);
        }
    }

    public static function data(array $nodes, string|array|object $keyOrData, mixed $value = null)
    {
        foreach($nodes as $node)
        {
            $node->add(setData($keyOrData, $value));
        }
    }

    public static function html(array $nodes, mixed ...$codes)
    {
        foreach($nodes as $node)
        {
            $node->empty();
            $node->add(html(...$codes));
        }
    }

    public static function append(array $nodes, mixed ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args);
        }
    }

    public static function remove(array $nodes)
    {
        foreach($nodes as $node)
        {
            $node->remove();
        }
    }

    public static function text(array $nodes, mixed ...$args)
    {
        foreach($nodes as $node)
        {
            $node->empty();
            $node->add(text(...$args));
        }
    }

    public static function empty(array $nodes)
    {
        foreach($nodes as $node)
        {
            $node->empty();
        }
    }

    public static function prepend(array $nodes, mixed ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args, 'children', true);
        }
    }

    public static function before(array $nodes, mixed ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args, 'before');
        }
    }

    public static function after(array $nodes, mixed ...$args)
    {
        foreach($nodes as $node)
        {
            $node->add($args, 'after');
        }
    }

    public static function replaceWith(array $nodes, mixed ...$args)
    {
        foreach($nodes as $node)
        {
            $node->replaceWith(...$args);
        }
    }

    public static function on(array $nodes, string $event, null|string|jsCallback $selectorOrCallback = null, null|array|string|jsCallback $handlerOrOptions = null)
    {
        foreach($nodes as $node)
        {
            $node->add(on::bind($event, $selectorOrCallback, $handlerOrOptions), 'children');
        }
    }

    public static function off(array $nodes, string $event)
    {
        foreach($nodes as $node)
        {
            $node->off($event);
        }
    }

    public static function closest(array $nodes, string|array|object $selectors)
    {
        foreach($nodes as $node)
        {
            $result = $node->closest($selectors);
            if($result) return array($result);
        }
        return array();
    }

    public static function find(array $nodes, string|array|object $selectors)
    {
        $list = array();
        foreach($nodes as $node)
        {
            $result = $node->find($selectors);
            if($result) $list = array_merge($list, $result);
        }
        return $list;
    }

    public static function first(array $nodes, string|array|object $selectors)
    {
        foreach($nodes as $node)
        {
            $result = $node->findFirst($selectors);
            if($result) return array($result);
        }
        return array();
    }

    public static function last(array $nodes, string|array|object $selectors)
    {
        foreach($nodes as $node)
        {
            $result = $node->findLast($selectors);
            if($result) return array($result);
        }
        return array();
    }

    public static function each(array $nodes, callable|\Collator $callback)
    {
        foreach($nodes as $node)
        {
            if($callback instanceof \Closure) $callback($node);
            else call_user_func($callback, $node);
        }
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
