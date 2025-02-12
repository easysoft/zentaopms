<?php
declare(strict_types=1);
/**
 * The linear efforts view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

$this->app->loadLang('execution');
$teamOrders = array();
foreach($task->team as $team) $teamOrders[$team->order] = $team->account;

$index       = 0;
$efforts     = array_values($efforts);
$recorders   = array();
$allOrders   = array();
$allEfforts  = array();
$myOrders    = array();
$myCountList = array();
$myLastID    = array();
$myEfforts   = array();
$myLastOrder = 0;
foreach($efforts as $key => $effort)
{
    $effort->consumed = helper::formatHours($effort->consumed);
    $effort->left     = helper::formatHours($effort->left);

    $prevEffort = $key > 0 ? $efforts[$key - 1] : null;
    $order      = (!$prevEffort or $prevEffort->order == $effort->order) ? $index : ++$index;
    $account    = $effort->account;

    $allEfforts[$order][]        = $effort;
    $recorders[$order][$account] = $account;

    $allOrders[$order] = $effort->order + 1;
    if($app->user->account == $account)
    {
        if($allOrders[$myLastOrder] != $effort->order + 1) $myLastOrder = $order;
        $myCountList[$myLastOrder] = isset($myCountList[$myLastOrder]) ? ++$myCountList[$myLastOrder] : 1;
        $myLastID[$myLastOrder]    = isset($myLastID[$myLastOrder]) ? ($myLastID[$myLastOrder] < $effort->id ? $effort->id : $myLastID[$myLastOrder]) : $effort->id;
        $myEfforts[$myLastOrder][] = $effort;

        if(!isset($myOrders[$effort->order])) $myOrders[$effort->order] = 0;
        $myOrders[$effort->order] += 1;
    }
}
ksort($myOrders);

$myEffortTable  = array();
foreach($myCountList as $order => $count)
{
    $tdDom = h::td
    (
        set::rowspan($count),
        $allOrders[$order]
    );

    $i = 1;
    foreach($myEfforts[$order] as $index => $effort)
    {
        $hidden = ($taskEffortFold and $i > 3) ? 'hidden' : '';
        $myEffortTable[] = h::tr
        (
            setClass($hidden),
            $tdDom,
            h::td($effort->date),
            h::td(zget($users, $effort->account)),
            h::td(html($effort->work)),
            h::td($effort->consumed . ' H'),
            h::td($effort->left . ' H'),
            h::td
            (
                common::hasPriv('task', 'editEffort') ? a
                (
                    setClass('btn ghost toolbar-item square size-sm text-primary edit-effort'),
                    on::click("loadModal('" . createLink('task', 'editEffort', "id={$effort->id}") . "')"),
                    icon('edit')
                ) : null,
                common::hasPriv('task', 'deleteWorkhour') ? a
                (
                    setClass('btn ghost toolbar-item square size-sm ajax-submit text-primary'),
                    setData(array('confirm' => $lang->task->confirmDeleteEffort)),
                    set::href(createLink('task', 'deleteWorkhour', "id={$effort->id}")),
                    icon('trash')
                ) : null
            )
        );
        $tdDom = null;
        $i ++;
    }
}
$iconClass = $taskEffortFold ? 'angle-down' : 'angle-top';
$iconText  = $taskEffortFold ? $lang->task->unfoldEffort : $lang->task->foldEffort;

$allEffortTable = array();
foreach($recorders as $order => $accounts)
{
    $count = count($allEfforts[$order]);
    $tdDom = h::td
    (
        set::rowspan($count),
        $allOrders[$order]
    );

    foreach($allEfforts[$order] as $effort)
    {
        $allEffortTable[] = h::tr
        (
            $tdDom,
            h::td($effort->date),
            h::td(zget($users, $effort->account)),
            h::td(html($effort->work)),
            h::td($effort->consumed . ' H'),
            h::td($effort->left . ' H')
        );

        $tdDom = null;
    }
}

div
(
    setID('linearefforts'),
    tabs
    (
        tabPane
        (
            set::key('myEffort'),
            set::active(true),
            set::title($lang->task->myEffort),
            h::table
            (
                setClass('table condensed bordered taskEffort'),
                h::tr
                (
                    h::th
                    (
                        width('60px'),
                        $lang->task->teamOrder
                    ),
                    h::th
                    (
                        width('100px'),
                        $lang->task->date
                    ),
                    h::th
                    (
                        width('100px'),
                        $lang->task->recordedBy
                    ),
                    h::th($lang->task->work),
                    h::th
                    (
                        width('60px'),
                        $lang->task->consumedAB
                    ),
                    h::th
                    (
                        width('60px'),
                        $lang->task->leftAB
                    ),
                    h::th
                    (
                        width('80px'),
                        $lang->actions
                    )
                ),
                $myEffortTable
            ),
            count($myEffortTable) > 3 ?  div
            (
                setID('toggleFoldIcon'),
                on::click('toggleFold'),
                setClass('text-primary'),
                span(setClass($iconClass . ' mr-1 icon-toggle'), icon('back-circle')),
                span(setClass('text'), $iconText)
            ) : null
        ),
        tabPane
        (
            set::key('allEffort'),
            set::title($lang->task->allEffort),
            h::table
            (
                setClass('table condensed bordered'),
                h::tr
                (
                    h::th
                    (
                        width('60px'),
                        $lang->task->teamOrder
                    ),
                    h::th
                    (
                        width('100px'),
                        $lang->task->date
                    ),
                    h::th
                    (
                        width('100px'),
                        $lang->task->recordedBy
                    ),
                    h::th($lang->task->work),
                    h::th
                    (
                        width('60px'),
                        $lang->task->consumedAB
                    ),
                    h::th
                    (
                        width('60px'),
                        $lang->task->leftAB
                    )
                ),
                $allEffortTable
            )
        )
    )
);
