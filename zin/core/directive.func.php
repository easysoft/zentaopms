<?php
/**
 * The directive class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'utils' . DS . 'flat.func.php';
require_once 'props.class.php';

use stdClass;

/**
 * Create directive object
 *
 * @param  string $type
 * @param  mixed  $data
 * @access public
 * @return object
 */
function directive($type, $data)
{
    if(!in_array($type, array('prop', 'class', 'style', 'cssVar', 'block', 'html', 'text')))
    {
        throw new \exception("zin: Unknown directive type \"$type\".");
    }

    $directive = new stdClass();
    $directive->directive = true;
    $directive->type = $type;
    $directive->data = $data;
    return $directive;
}

/**
 * Check if an object is a directive
 *
 * @param  object $object
 * @access public
 * @return bool
 */
function isDirective($object)
{
    return is_object($object) && isset($object->directive) && $object->directive;
}

function prop($name, $value = NULL)
{
    return directive('prop', (is_array($name) || $name instanceof props) ? $name : array($name => $value));
}

function set($name, $value = NULL)
{
    return prop($name, $value);
}

function setClass()
{
    return directive('class', func_get_args());
}

function setStyle($name, $value = NULL)
{
    return directive('style', is_array($name) ? $name : array($name => $value));
}

function setCssVar($name, $value = NULL)
{
    return directive('cssVar', is_array($name) ? $name : array($name => $value));
}

function setId($id)
{
    return prop('id', $id);
}

function html(/* string ...$lines */)
{
    return directive('html', implode("\n", \zin\utils\flat(func_get_args())));
}

function text(/* string ...$lines */)
{
    return directive('text', implode("\n", \zin\utils\flat(func_get_args())));
}

function block($name, $value = NULL)
{
    return directive('block', is_array($name) ? $name : array($name => $value));
}

function before()
{
    return directive('block', array('before' => func_get_args()));
}

function after()
{
    return directive('block', array('after' => func_get_args()));
}
