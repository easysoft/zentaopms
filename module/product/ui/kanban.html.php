<?php
declare(strict_types=1);
/**
 * The kanban view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sungunagming@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

$privs = array
(
    'canViewExecution'   => common::hasPriv('execution', 'task'),
    'canViewProject'     => common::hasPriv('project', 'index'),
    'canViewRelease'     => common::hasPriv('release', 'view'),
    'canViewPlan'        => common::hasPriv('productplan', 'view'),
);

foreach($kanbanList as $current => $region)
{
    foreach($region['items'] as $index => $group)
    {
        $group['draggable']  = true;
        $group['colWidth']   = 'auto';
        $group['getCol']     = jsRaw('window.getCol');
        $group['getItem']    = jsRaw('window.getItem');
        $group['itemRender'] = jsRaw('window.itemRender');
        $group['canDrop']    = jsRaw('window.canDrop');
        $group['onDrop']     = jsRaw('window.onDrop');
        $kanbanList[$current]['items'][$index] = $group;
    }
}

jsVar('privs',   $privs);
jsVar('delayed', $lang->project->statusList['delay']);

featureBar
(
    li
    (
        setClass('nav-item item'),
        a
        (
            $browseType == 'my' ? setClass('active') : null,
            set::href(createLink('product', 'kanban', "browseType=my")),
            $lang->product->myProduct,
        )
    ),
    li
    (
        setClass('nav-item item'),
        set::active($browseType == 'other'),
        a
        (
            $browseType == 'other' ? setClass('active') : null,
            set::href(createLink('product', 'kanban', "browseType=other")),
            $lang->product->otherProduct,
        )
    ),
);
zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 120px)')
);

render();
