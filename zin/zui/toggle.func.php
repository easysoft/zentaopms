<?php
/**
 * The zui toggle function file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

function toggle($name, $options = NULL)
{
    $props = array('data-toggle' => $name);
    if (is_array($options))
    {
        foreach ($options as $key => $value)
        {
            $props["data-$key"] = $value;
        }
    }
    return set($props);
}
