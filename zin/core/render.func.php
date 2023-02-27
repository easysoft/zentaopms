<?php
/**
 * The render function file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

class globalRender
{
    public static $list = array();

    public static $enabled = true;
}

function enableGlobalRender()
{
    globalRender::$enabled = true;
}

function disableGlobalRender()
{
    globalRender::$enabled = false;
}

function renderInGlobal()
{
    if(!globalRender::$enabled) return false;

    globalRender::$list = array_merge(globalRender::$list, func_get_args());
}

function render($wgName = '\\zin\\page')
{
    if(is_string($wgName) && strpos($wgName, '\\zin\\') === false) $wgName = "\\zin\\$wgName";

    $args = array();
    foreach(globalRender::$list as $item)
    {
        if(is_object($item) && isset($item->parent) && $item->parent) continue;
        $args[] = $item;
    }

    $wg = class_exists($wgName) ? (new $wgName($args)) : $wgName($args);

    if(!$wg->displayed) $wg->display();
}
