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

require_once 'zin.class.php';

function render($wgName = '\\zin\\page')
{
    $args = array();
    foreach(zin::$globalRenderList as $item)
    {
        if(is_object($item) && isset($item->parent) && $item->parent) continue;
        $args[] = $item;
    }

    if(is_string($wgName))
    {
        if(strpos($wgName, '\\zin\\') !== false)  $wgName = str_replace('\\zin\\', '', $wgName);
        if(isset(zin::$globalRenderMap[$wgName])) $wgName = zin::$globalRenderMap[$wgName];
    }

    $className = "\\zin\\$wgName";
    $wg        = class_exists($className) ? (new $className($args)) : $className($args);

    if(!$wg->displayed) $wg->display();
}
