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
    if($config->vision == 'lite' and !in_array($menuKey, $config->admin->liteMenuList)) continue;

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

    $settingItems[] = div
    (
        setClass('setting-box p-2 w-1/5 h-32'),
        set(array('data-id' => $menuKey)),
        button
        (
            setClass('flex col border rounded-md px-2.5 py-1 w-full h-full'),
            set(array
            (
                'data-on' => $url ? 'click' : '',
                'data-call' => $url ? "window.location.href='{$url}'" : '',
                'title' => $menu['disabled'] ? $lang->admin->noPriv : '',
            )),
            h4
            (
                setClass('flex align-center my-2.5 w-full'),
                div
                (
                    setClass('flex align-center gap-1 font-bold text-md'),
                    img(set::src("static/svg/admin-{$menuKey}.svg")),
                    $menu['name'],
                    a
                    (
                        setClass('text-gray'),
                        set::href($config->admin->helpURL[$menuKey]),
                        set::title($lang->help),
                        set::target('_blank'),
                        icon('help'),
                    )
                ),
            ),
            p
            (
                setClass('text-left text-gray pb-4 h-12 leading-6'),
                set::title($menu['desc']),
                $menu['desc']
            )
        )
    );
}

div
(
    set::style(array('width' => '70%')),
    panel
    (
        setID('settings'),
        setClass('mb-4 px-2'),
        set::title($lang->admin->setting),
        set::bodyClass('flex flex-wrap'),
        $settingItems
    ),
    panel
    (
        setID('plugin'),
        setClass('mb-4 px-2'),
        set::title($lang->admin->pluginRecommendation),
    ),
    div
    (
        setClass('flex'),
        panel
        (
            setClass('mr-4 '),
            set::style(array('width' => '30%')),
            set::title($lang->admin->officialAccount),
        ),
        panel
        (
            set::style(array('width' => '70%')),
            set::title($lang->admin->publicClass),
        )
    )
);

panel
(
    setClass('ml-4'),
    set::style(array('width' => '30%')),
    set::title($lang->admin->zentaoInfo),
    div
    (
        setClass('h-56px'),
    )
);


render();
