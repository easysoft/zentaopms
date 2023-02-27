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

$globalRenderList = array();

$isGlobalMode = true;

function enableGlobalRender()
{
    global $isGlobalMode;
    $isGlobalMode = true;
}

function disableGlobalRender()
{
    global $isGlobalMode;
    $isGlobalMode = false;
}

function render($wgName)
{
    global $globalRenderList;

    if(function_exists($wgName))
    {
        return call_user_func_array($wgName, $globalRenderList);
    }

    return class_exists($wgName) ? (new $wgName($globalRenderList)) : $wgName($globalRenderList);
}
