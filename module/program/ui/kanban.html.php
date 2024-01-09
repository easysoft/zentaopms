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
        $group['draggable']     = true;
        $group['colWidth']      = 'auto';
        $group['maxLaneHeight'] = '300px';
        $group['getCol']        = jsRaw('window.getCol');
        $group['getItem']       = jsRaw('window.getItem');
        $group['itemRender']    = jsRaw('window.itemRender');
        $group['canDrop']       = jsRaw('window.canDrop');
        $group['onDrop']        = jsRaw('window.onDrop');
        $kanbanList[$current]['items'][$index] = $group;
    }
}

jsVar('privs',   $privs);
jsVar('delayed', $lang->project->statusList['delay']);

if(empty($kanbanList))
{
    panel
    (
        div
        (
            setClass('dtable-empty-tip'),
            div
            (
                setClass('row gap-4 items-center'),
                span
                (
                    setClass('text-gray'),
                    $lang->noData
                ),
            )
        )
    );
}
else
{
    featureBar
    (
        li
        (
            setClass('nav-item item'),
            a
            (
                $browseType == 'my' ? setClass('active') : null,
                set::href(createLink('program', 'kanban', "browseType=my")),
                $lang->program->kanban->typeList['my']
            )
        ),
        li
        (
            setClass('nav-item item'),
            set::active($browseType == 'other'),
            a
            (
                $browseType == 'other' ? setClass('active') : null,
                set::href(createLink('program', 'kanban', "browseType=other")),
                $lang->program->kanban->typeList['others']
            )
        ),
    );

    zui::kanbanList
    (
        set::key('kanban'),
        set::items($kanbanList),
        set::height('calc(100vh - 120px)')
    );
}
