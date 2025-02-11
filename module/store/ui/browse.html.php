<?php
declare(strict_types=1);
/**
 * The browse view file of store module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     store
 * @link        https://www.zentao.net
 */

namespace zin;

$tree = array();
foreach($categories as $id => $category)
{
    $item = new stdclass();
    $item->id     = $id;
    $item->parent = 0;
    $item->name   = $category;
    $item->url    = helper::createLink('store', 'browse', "sortType=$sortType&categoryID=$id");
    $tree[] = $item;
}
jsVar('tree', $tree);
jsVar('postCategories', '');
jsVar('sortType', $sortType);
jsVar('currentCategoryID', $currentCategoryID);

$setting    = usePager();
$cloudApps  = array_chunk($cloudApps, 3);
$groups     = array();
$canInstall = hasPriv('instance', 'manage');
if(!empty($cloudApps))
{
    foreach ($cloudApps as $group)
    {
        $items = array();
        if(count($group) < 3) $group = array_merge($group, array_fill(count($group) - 1, 3 - count($group), new stdclass()));

        foreach($group as $cloudApp)
        {
            if(empty($cloudApp->id))
            {
                $items[] = div(setClass('flex-1 p-2 content-between flex col store-item'));
                continue;
            }

            $items[] = div
                (
                    setClass('flex-1 bg-white shadow p-2 content-between flex col store-item state open-url'),
                    set('data-url', createLink('store', 'appview', "id=$cloudApp->id")),
                    div
                    (
                        setStyle('height', '100px'),
                        setClass('flex border-b border-lighter'),
                        !empty($cloudApp->logo) ? img(set::src($cloudApp->logo), setStyle(array('width' => '80px', 'height' => '80px'))) : null,
                        div
                        (
                            setClass('ml-4'),
                            div($cloudApp->alias, setClass('app-name'), span($cloudApp->app_version, setClass('ml-5 label gray-pale rounded-full'))),
                            div($cloudApp->introduction, setClass('line-2'))
                        )
                    ),
                    div
                    (
                        setClass('mt-5 flex justify-between'),
                        span
                        (
                            $lang->store->author,
                            span($cloudApp->author, setClass('font-semibold ml-2'))
                        ),
                        $canInstall ? btn
                        (
                            $lang->store->install,
                            setClass('primary btn size-sm install-btn'),
                            setData('prevent', true),
                            setData('toggle', 'modal'),
                            setData('size', 'sm'),
                            in_array($cloudApp->id, $installedApps) ? setData('confirm', $lang->store->alreadyInstalled) : null,
                            setData('url', $this->createLink('space', 'createApplication', "id={$cloudApp->id}")),
                            on::click('installApp', array('stop' => true))
                        ) : null
                    )
                );
        }

        $groups[] = div
            (
                setClass('flex gap-5 mt-5'),
                ...$items
            );
    }
}

$closeLink = createLink('store', 'browse', "sortType=$sortType");
featureBar
(
    set::current($sortType),
    set::linkParams("sortType={key}")
);

toolbar
(
    formGroup
    (
        set::name('name'),
        on::keyup('searchApp'),
        set::control(array
        (
            'id'          => 'searchName',
            'type'        => 'inputControl',
            'prefixWidth' => 'icon',
            'placeholder' => $lang->store->searchApp,
            'value'       => $keyword
        ))
    )
);

div
(
    setID('cloudAppContainer'),
    setClass('mb-5 flex col'),
    div(...$groups),
    count($cloudApps) ? pager(
        set::props(array('id' => 'storePager')),
        set::page($setting['page']),
        set::recTotal($setting['recTotal']),
        set::recPerPage($setting['recPerPage']),
        set::linkCreator($setting['linkCreator']),
        set::items($setting['items']),
        set::gap($setting['gap'])
    ) : null
);

sidebar
(
    moduleMenu(set(array
    (
        'modules'     => $tree,
        'activeKey'   => $currentCategoryID,
        'closeLink'   => $closeLink,
        'showDisplay' => false
    )))
);
