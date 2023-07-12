<?php
declare(strict_types=1);
/**
 * The index view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('hasInternet', $zentaoData->hasData);

$settingItems = array();
foreach($lang->admin->menuList as $menuKey => $menu)
{
    $link = '';
    if(!empty($menu['link'])) $link = $menu['link'];
    if(!empty($menu['subMenu']))
    {
        foreach($menu['subMenu'] as $subMenu)
        {
            if(!empty($subMenu['link']))
            {
                $link = $subMenu['link'];
                break;
            }
        }
    }

    $params = explode('|', $link);
    if(count($params) == 2) list($module, $method) = $params;
    if(count($params) > 2)  list($label, $module, $method) = $params;

    $url = $module && $method ? createLink($module, $method) : '';

    if($config->vision == 'lite' and !in_array($menuKey, $config->admin->liteMenuList)) continue;
    $settingItems[] = button
    (
        setClass('flex col justify-between basis-20% shrink-1 border m-2 px-2 py-2 w-1/5 h-32'),
        set(array
        (
            'data-on' => $url ? 'click' : '',
            'data-call' => $url ? "window.location.href='{$url}'" : '',
            'title' => $menu['disabled'] ? $lang->admin->noPriv : '',
        )),
        h4
        (
            setClass('flex'),
            img(set::src("static/svg/admin-{$menuKey}.svg")),
            $menu['name'],
            a
            (
                setClass('text-gray'),
                set::href($config->admin->helpURL[$menuKey]),
                set::title($lang->help),
                set::target('_blank'),
                icon('help'),
            ),
        ),
        p
        (
            setClass('text-left'),
            set::title($menu['desc']),
            $menu['desc']
        )
    );
}

col
(
    setClass('w-2/3'),
    panel
    (
        setID('settings'),
        set::title($lang->admin->setting),
        set::bodyClass('flex flex-wrap pt-0 pb-4 px-1'),
        $settingItems
    )
);

render();
