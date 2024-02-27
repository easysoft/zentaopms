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
    public static function addClass(node $node, array $args)
    {
        $node->props->class->add(...$args);
    }

    public static function removeClass(node $node, array $args)
    {
        $node->props->class->remove(...$args);
    }

    public static function prop(node $node, array $args)
    {
        $node->setProp(...$args);
    }

    public static function toggleClass(node $node, array $args)
    {
        $node->props->class->toggle(...$args);
    }

    public static function html(node $node, array $args)
    {
        $node->empty();
        $node->add(html(...$args));
    }

    public static function append(node $node, array $args)
    {
        $node->add($args);
    }

    public static function remove(node $node, array $args)
    {
        $node->remove();
    }

    public static function text(node $node, array $args)
    {
        $node->empty();
        $node->add(text(...$args));
    }

    public static function empty(node $node, array $args)
    {
        $node->empty();
    }

    public static function prepend(node $node, array $args)
    {
        $node->add($args, 'children', true);
    }

    public static function before(node $node, array $args)
    {
        $node->add($args, 'before');
    }

    public static function after(node $node, array $args)
    {
        $node->add($args, 'after');
    }

    public static function replaceWith(node $node, array $args)
    {
        $node->replaceWith(...$args);
    }

    public static function on(node $node, array $args)
    {
        $node->add(on(...$args), 'children');
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
