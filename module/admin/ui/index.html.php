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

/* Get latest data from zentao.net if ZenTaoPMS has internet and the user is admin. */
jsVar('hasInternet', $zentaoData->hasData || $hasInternet);
jsVar('isAdminUser', $this->app->user->admin);

$buildHeader = function(string $title, string $actionUrl = '', string $titleIcon = '', string $actionLang = '', string $actionIcon = ''): h
{
    global $lang;

    return div
    (
        setClass('flex justify-between items-center pl-4 h-10'),
        div
        (
            setClass('panel-title'),
            $titleIcon ? icon
            (
                setClass('text-lg text-primary pr-1'),
                $titleIcon
            ) : null,
            $title
        ),
        $actionUrl ? a
        (
            setClass('text-gray pr-3'),
            set::target('_blank'),
            set::href($actionUrl),
            $actionLang ? $actionLang : $lang->more,
            icon($actionIcon ? $actionIcon : 'caret-right')
        ) : null
    );
};

$buildPluginHeader = function($name, $url): h
{
    return div
    (
        setClass('flex justify-between items-center'),
        div
        (
            setClass('panel-title py-2.5'),
            $name
        ),
        a
        (
            //setClass('flex items-center'),
            set::href($url),
            set::target('_blank'),
            icon
            (
                setClass('text-primary bg-primary-100 p-1'),
                'download-alt'
            )
        )
    );
};

$buildUsed = function(int $amount, string $unit = ''): array
{
    return array
    (
        $amount ? span
        (
            setClass('bg-gray-100 rounded-md text-lg mx-1 px-1 py-0.5'),
            $amount
        ) : null,
        $amount ? $unit : ''
    );
};

$settingItems = array();
$flowItems    = array();
foreach($lang->admin->menuList as $menuKey => $menu)
{
    if($config->vision == 'lite' and !in_array($menuKey, $config->admin->liteMenuList)) continue;

    $items = div
    (
        setClass('pb-4 pr-4 h-32 w-1/' . ($config->vision == 'lite' ? 3 : 5)),
        col
        (
            setClass('setting-box cursor-pointer border border-hover rounded-md px-2 py-1 h-full'),
            set('data-id', $menuKey),
            empty($menu['disabled']) ? set('data-url', zget($menu, 'link', '')) : null,
            !empty($menu['disabled']) ? setClass('disabled') : null,
            !empty($menu['disabled']) ? set::title($lang->admin->noPriv) : null,
            h4
            (
                setClass('flex my-2.5 w-full'),
                div
                (
                    setClass('flex gap-1 font-bold text-md'),
                    !empty($menu['icon']) ? icon(setClass("svg-icon rounded-lg content-center bg-{$menu['bg']} text-white"), $menu['icon']) : img(set::src("static/svg/admin-{$menuKey}.svg")),
                    $menu['name'],
                    !empty($config->admin->helpURL[$menuKey]) ?
                    a
                    (
                        setClass('text-gray'),
                        set::href($config->admin->helpURL[$menuKey]),
                        set::title($lang->help),
                        set::target('_blank'),
                        icon('help')
                    ) : '',
                )
            ),
            p
            (
                setClass('overflow-hidden text-left text-gray pb-4 h-12 leading-6'),
                set::title($menu['desc']),
                $menu['desc']
            )
        )
    );

    if(!empty($menu['group']) && $menu['group'] == 'flow')
    {
        $flowItems[] = $items;
    }
    else
    {
        $settingItems[] = $items;
    }
}

$pluginItems = array();
if($zentaoData->plugins)
{
    foreach($zentaoData->plugins as $plugin)
    {
        if(!$plugin) continue;

        $pluginDesc = empty($plugin->abstract) ? '' : preg_replace('/[[:cntrl:]]/mu', '', strip_tags($plugin->abstract));

        $pluginItems[] = div
        (
            setClass('pr-4 pb-4 w-1/3 h-32'),
            div
            (
                setClass('border rounded-md px-3 py-2'),
                $buildPluginHeader($plugin->name, $plugin->viewLink),
                div
                (
                    setClass('overflow-hidden text-gray h-12 leading-6'),
                    set::title($pluginDesc),
                    $pluginDesc
                )
            )
        );
    }
}

$classItems = array();
if($zentaoData->classes)
{
    foreach($zentaoData->classes as $class)
    {
        $classItems[] = a
        (
            setClass('border border-hover rounded-md mr-4 mb-4 p-1 w-1/3'),
            set::href($class->viewLink),
            set::target('_blank'),
            img
            (
                set::src($class->image)
            ),
            div
            (
                setClass('overflow-hidden text-md text-center font-bold text-black mb-1.5 p-1 h-6'),
                $class->name
            )
        );
    }
}

$dynamicItems = array();
foreach($zentaoData->dynamics as $dynamic)
{
    $dynamicItems[] = div
    (
        setClass('relative oeverflow-hidden flex justify-between border-t px-4 py-3 h-18'),
        div
        (
            setClass('flex-1 overflow-hidden h-11 leading-normal'),
            set::title($dynamic->title),
            icon
            (
                setClass('text-lg text-primary pr-1'),
                'horn'
            ),
            span(substr($dynamic->addedDate, 0, 10)),
            a
            (
                setClass('text-black ml-1'),
                set::href($dynamic->link),
                set::target('_blank'),
                $dynamic->title
            )
        )
    );
}

$patchItems = array();
foreach($zentaoData->patches as $patch)
{
    $patchItems[] = div
    (
        setClass('border-t pt-2.5 pb-3 px-4'),
        $buildPluginHeader($patch->name, $patch->viewLink),
        div
        (
            setClass('overflow-hidden h-10'),
            $patch->desc
        )
    );
}

if($config->edition != 'ipd')
{
    $upgradeEditions = array('biz', 'max', 'ipd');
    if($config->edition == 'biz') $upgradeEditions = array('max', 'ipd');
    if($config->edition == 'max') $upgradeEditions = array('ipd');

    $upgradeItems = array();
    foreach($upgradeEditions as $edition)
    {
        $featureItems = array();
        foreach($lang->admin->productFeature[$edition] as $feature)
        {
            $featureItems[] = div(
                setClass('flex items-center my-1 pl-5 h-5'),
                div(
                    setClass('rounded-full light mr-2 w-2 h-2')
                ),
                $feature
            );
        }

        $upgradeItems[] = div(
            setClass('border-t py-1.5'),
            $buildHeader($lang->admin->{$edition . 'Tag'}, $config->admin->apiRoot, 'zentao', $lang->admin->productDetail),
            $featureItems
        );
    }
}

div
(
    set::style(array('width' => '70%')),
    $settingItems ? div
    (
        setID('settings'),
        setClass('bg-white rounded-md mb-4'),
        $buildHeader($lang->admin->setting),
        div
        (
            setClass('flex flex-wrap pl-4'),
            on::click('redirectSetting'),
            $settingItems
        )
    ) : null,
    $flowItems ? div
    (
        setID('flows'),
        setClass('bg-white rounded-md mb-4'),
        $buildHeader($lang->admin->setFlow),
        div
        (
            setClass('flex flex-wrap pl-4'),
            on::click('redirectSetting'),
            $flowItems
        )
    ) : null,
    $pluginItems ? div
    (
        setID('plugin'),
        setClass('bg-white rounded-md mb-4'),
        $buildHeader($lang->admin->pluginRecommendation, $config->admin->extensionURL),
        div
        (
            setClass('flex flex-wrap pl-4'),
            $pluginItems
        )
    ) : null,
    div
    (
        setClass('flex'),
        div
        (
            setClass('bg-white rounded-md mb-4 w-1/3'),
            $buildHeader($lang->admin->officialAccount),
            div
            (
                setClass('flex px-4'),
                img
                (
                    setClass('w-1/3'),
                    set::src('static/images/wechat.jpg')
                ),
                div
                (
                    setClass('pl-2 w-2/3'),
                    $lang->admin->followUs,
                    div
                    (
                        setClass('text-gray'),
                        $lang->admin->followUsContent
                    )
                )
            ),
            !$bind && !$ignore && $hasInternet && common::hasPriv('admin', 'register') ? div
            (
                setClass('px-4 pb-4'),
                substr($lang->admin->notice->register, 0, strpos($lang->admin->notice->register, '%s')),
                a
                (
                    set::href(inlink('register')),
                    $lang->admin->registerNotice->submitHere
                ),
                substr($lang->admin->notice->register, strpos($lang->admin->notice->register, '%s') + 2)
            ) : null
        ),
        div
        (
            setClass('bg-white rounded-md ml-4 mb-4 w-2/3'),
            $buildHeader($lang->admin->publicClass, $config->admin->classURL),
            div
            (
                setClass('flex pl-4'),
                $classItems
            )
        )
    )
);

$isZeroDay = empty($dateUsed->year) && empty($dateUsed->month) && empty($dateUsed->day);
div
(
    setClass('bg-white rounded-md ml-4 px-4'),
    set::style(array('width' => '30%')),
    div
    (
        setClass('flex justify-between items-center h-14'),
        div
        (
            setClass('panel-title text-md py-2.5'),
            $lang->admin->zentaoInfo
        ),
        div
        (
            setStyle(array('letter-spacing' => '1px')),
            $lang->admin->zentaoUsed,
            $buildUsed((int)$dateUsed->year, $lang->year),
            $buildUsed((int)$dateUsed->month, $lang->admin->mon),
            $buildUsed((int)$dateUsed->day, $lang->admin->day),
            $isZeroDay ? span
            (
                setClass('bg-gray-100 rounded-md text-lg mx-1 px-1 py-0.5'),
                0
            ) : null,
            $isZeroDay ? $lang->admin->day : null

        )
    ),
    div
    (
        setClass('border rounded-md mb-4'),
        $buildHeader($lang->admin->updateDynamics, $config->admin->dynamicURL),
        $dynamicItems
    ),
    div
    (
        setClass('border rounded-md mb-4'),
        $buildHeader($lang->admin->updatePatch, $config->admin->patchURL),
        $patchItems
    ),
    $config->edition != 'ipd' ? div
    (
        setClass('border rounded-md mb-4'),
        $buildHeader($lang->admin->upgradeRecommend),
        $upgradeItems
    ) : null
);

render();
