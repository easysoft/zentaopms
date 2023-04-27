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

function render(string $wgName = 'page', string|array $options = NULL)
{
    $args = array();
    foreach(zin::$globalRenderList as $item)
    {
        if(is_object($item) && isset($item->parent) && $item->parent) continue;
        $args[] = $item;
    }
    zin::$globalRenderList = array();

    if(is_string($wgName) && isset(zin::$globalRenderMap[$wgName])) $wgName = zin::$globalRenderMap[$wgName];

    $isFullPage = str_starts_with($wgName, 'page');
    if($isFullPage) $args[] = set::display(false);

    if($options === NULL)
    {
        if(isset($_SERVER['HTTP_X_ZIN_OPTIONS']) && !empty($_SERVER['HTTP_X_ZIN_OPTIONS']))
        {
            $setting = $_SERVER['HTTP_X_ZIN_OPTIONS'];
            $options = $setting[0] === '{' ? json_decode($setting, true) : array('selector' => $setting);
        }
    }

    global $app;
    data('zinErrors', isset($app->zinErrors) ? $app->zinErrors : array());

    $wg = createWg($wgName, $args);
    if(!$isFullPage) $wg = fragment($wg);
    $wg->display($options);
}
