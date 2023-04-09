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

function render($wgName = 'page', $options = NULL)
{
    $args = [];
    foreach(zin::$globalRenderList as $item)
    {
        if(is_object($item) && isset($item->parent) && $item->parent) continue;
        $args[] = $item;
    }

    if(is_string($wgName) && isset(zin::$globalRenderMap[$wgName])) $wgName = zin::$globalRenderMap[$wgName];

    if($wgName === 'page' || $wgName === 'pagebase') $args[] = set::display(false);

    if($options === NULL)
    {
        $headers = getallheaders();
        if(isset($headers['X-ZIN-Options']) && !empty($headers['X-ZIN-Options']))
        {
            $setting = $headers['X-ZIN-Options'];
            $options = $setting[0] === '{' ? json_decode($headers['X-ZIN-Options'], true) : ['selector' => $setting];
        }
    }

    $wg = createWg($wgName, $args);
    $wg->display($options);
}
