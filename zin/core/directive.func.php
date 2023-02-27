<?php
/**
 * The directive methods file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once 'render.func.php';

use stdClass;

/**
 * Create directive object
 *
 * @param  string $type
 * @param  mixed  $data
 * @access public
 * @return object
 */
function directive($type, $data, $options = NULL)
{
    if(!in_array($type, array('prop', 'class', 'style', 'cssVar', 'block', 'html', 'text')))
    {
        throw new \exception("zin: Unknown directive type \"$type\".");
    }

    $directive = new stdClass();
    $directive->directive = true;
    $directive->type      = $type;
    $directive->data      = $data;
    $directive->options   = $options;

    renderInGlobal($directive);

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
