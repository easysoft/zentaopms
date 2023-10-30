<?php
declare(strict_types=1);
/**
 * The header view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

h::css(file_get_contents('../css/header/lite.ui.css'));

$currentModule = $app->rawModule;
$currentMethod = $app->rawMethod;
$config->hasMainNavBar = array("{$currentModule}-{$currentMethod}");
$dropdownItems = array();
foreach($lang->execution->menu as $menuKey => $menu)
{
    $active = $menuKey == $currentMethod;
    $dropdownItems[] = array('icon' => $lang->execution->icons[$menuKey], 'text' => $menu['name'], 'url' => createLink($menu['module'], $menu['method'], $menu['vars']), 'active' => $active);
}

mainNavbar
(
    to::left
    (
        zui::dropmenu
        (
            setID("execution-menu"),
            set('_id', 'switcher'),
            set(array('text' => $execution->name, 'fetcher' => createLink('execution', 'ajaxGetExecutionSwitcherMenu', "projectID={$execution->project}&executionID={$execution->id}&method={$currentMethod}"), 'defaultValue' => $execution->id)),
        )
    ),
    to::right
    (
        dropdown
        (
            btn(
                setClass('ghost btn square btn-default absolute right-0'),
                set::icon($lang->execution->icons[$currentMethod]),
                span
                (
                    setClass('pl-1'),
                    $lang->execution->menu->{$currentMethod}['name']
                )
            ),
            set::placement('bottom-end'),
            set::items($dropdownItems)
        )
    )
);
