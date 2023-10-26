<?php
declare(strict_types = 1);
/**
 * The preview view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

include strtolower($method) . '.html.php';

jsVar('dimension', $dimensionID);
jsVar('groupID', $group->id);

featureBar
(
    set::current($group->id),
    set::linkParams("dimensionID={$dimensionID}&group={key}")
);

if($config->edition == 'biz' || $config->edition == 'max')
{
    toolbar
    (
        hasPriv('pivot', 'export') ? item(set(array
        (
            'text'  => $lang->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url'   => '#export',
            'data-toggle' => 'modal'
        ))) : null,
        hasPriv('pivot', 'browse') ? item(set(array
        (
            'text'  => $lang->pivot->toDesign,
            'class' => 'primary',
            'url'   => inlink('browse'),
            'data-toggle' => 'modal'
        ))) : null,
    );
}

div
(
    setClass('flex gap-4'),
    sidebar
    (
        set::width('60'),
        moduleMenu
        (
            set::title($group->name),
            set::activeKey($currentMenu),
            set::modules($menus),
            set::closeLink(''),
            set::showDisplay(false)
        ),
        $config->edition == 'open' ? div
        (
            setClass('bg-white p-4'),
            html(empty($config->isINT) ? $lang->bizVersion : $lang->bizVersionINT)
        ) : null
    ),
    div
    (
        setClass('w-full'),
        $generateData()
    )
);
