<?php
declare(strict_types=1);
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
 * 将视图页面声明的所有内容通过一个部件进行渲染，并输出 HTML。
 * Render page content with a widget to HTML.
 *
 * @access public
 * @param  string       $wgName
 * @param  array $options
 * @return void
 */
function render(string $wgName = '', array $options = array())
{
    /* 获取全局渲染部件实例和指令。 Get global render widgets and directives. */
    $globalItems = zin::getGlobalRenderList();

    /* 决定部件名称，如果是 Ajax 请求则进行特殊处理。 Decide widget name, if is ajax request, then do special process. */
    if(empty($wgName))
    {
        $wgName = 'page';
        if(isAjaxRequest('modal')) $wgName = 'modalDialog';
        else if(isAjaxRequest() && !isAjaxRequest('zin')) $wgName = 'fragment';
    }

    /* 判断是否渲染为完整页面。 Check if render in full page. */
    $isFullPage = str_starts_with($wgName, 'page');
    if($isFullPage) $globalItems[] = set::display(false);

    /* 获取部件渲染选项。 Get widget display options. */
    if(empty($options) && isset($_SERVER['HTTP_X_ZIN_OPTIONS']) && !empty($_SERVER['HTTP_X_ZIN_OPTIONS']))
    {
        $setting = $_SERVER['HTTP_X_ZIN_OPTIONS'];
        $options = $setting[0] === '{' ? json_decode($setting, true) : array('selector' => $setting);
    }

    /* 创建部件实例。 Create widget instance. */
    $wg = createWg($wgName, $globalItems);

    /* 如果不是渲染一个完整页面，则使用 fragment 进行渲染。 If not render in full page, then render all items in a fragment. */
    if(!$isFullPage && $wgName !== 'fragment') $wg = fragment($wg);

    /* 渲染并输出 HTML。 Render and display html. */
    $wg->display($options);

    zin::$rendered = true;
}
