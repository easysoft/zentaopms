<?php
declare(strict_types=1);
/**
 * The deny view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;

$denyContent = '';
if($denyType == 'nopriv')
{
    $this->app->loadLang('group');
    $groupPriv  = isset($lang->resource->$module->$method) ? $lang->resource->$module->$method : $method;
    $moduleName = isset($lang->$module->common)  ? $lang->$module->common  : $module;
    $methodName = isset($lang->$module->$groupPriv) && is_string($lang->$module->$groupPriv) ? $lang->$module->$groupPriv : $method;

    if($module == 'execution' && $method == 'gantt') $methodName = $methodName->common;

    /* find method name if method is lowercase letter. */
    if(!isset($lang->$module->$method))
    {
        $tmpLang = array();
        foreach($lang->$module as $key => $value) $tmpLang[strtolower($key)] = $value;
        $methodName = isset($tmpLang[$method]) ? $tmpLang[$method] : $method;
    }

    $denyContent = sprintf($lang->user->errorDeny, $moduleName, $methodName);
}

if($denyType == 'noview')
{
    $menuName = isset($lang->$menu->common) ? $lang->$module->common : $menu;
    if(isset($lang->menu->$menu)) list($menuName) = explode('|', $lang->menu->$menu);
    $denyContent = sprintf($lang->user->errorView, $menuName);
}

h::css("#header{display:none;}");
panel
(
    setID('denyBox'),
    set::title($app->user->account . ' ' . $lang->user->deny),
    set::footerActions
    (array(
        $referer ? array('url' => helper::safe64Decode($referer), 'text' => $lang->user->goback) : array('back' => 'APP', 'text' => $lang->user->goback),
        array('data-url' => createLink('user', 'logout', "referer=" . helper::safe64Encode($denyPage)), 'class' => 'primary', 'text' => $lang->user->relogin, 'onclick' => 'locateLogin(this)')
    )),
    set::footerClass('justify-center'),
    div
    (
        setClass('alert'),
        icon(setClass('icon-3x alert-icon'), set::style(array('opacity' => '0.6')), 'exclamation-sign'),
        div(html($denyContent))
    )
);

render();
