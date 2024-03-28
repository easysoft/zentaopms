<?php
declare(strict_types=1);
/**
 * The executionkanban view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;
$privs = array
(
    'canViewExecution'     => common::hasPriv('execution', 'task'),
    'canStartExecution'    => common::hasPriv('execution', 'start'),
    'canSuspendExecution'  => common::hasPriv('execution', 'suspend'),
    'canCloseExecution'    => common::hasPriv('execution', 'close'),
    'canActivateExecution' => common::hasPriv('execution', 'activate'),
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
    zui::kanbanList
    (
        set::key('kanban'),
        set::items($kanbanList),
        set::height('calc(100vh - 120px)')
    );
}
