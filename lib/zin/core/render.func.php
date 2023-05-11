<?php
/**
 * The render function file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'zin.class.php';

/**
 * Render page content with a widget to HTML.
 * 将视图页面声明的所有内容通过一个部件进行渲染，并输出 HTML。
 *
 * @access public
 * @param string       $wgName
 * @param string|array $options
 * @return void
 */
function render(string $wgName = 'page', string|array $options = null)
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

    if($options === null && isset($_SERVER['HTTP_X_ZIN_OPTIONS']) && !empty($_SERVER['HTTP_X_ZIN_OPTIONS']))
    {
        $setting = $_SERVER['HTTP_X_ZIN_OPTIONS'];
        $options = $setting[0] === '{' ? json_decode($setting, true) : array('selector' => $setting);
    }

    $wg = createWg($wgName, $args);
    if(!$isFullPage) $wg = fragment($wg);
    $wg->display($options);
}
