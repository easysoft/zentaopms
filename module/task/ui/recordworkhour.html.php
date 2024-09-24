<?php
declare(strict_types=1);
/**
 * The batchCreate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmRecord', $lang->task->confirmRecord);
jsVar('foldEffort', $lang->task->foldEffort);
jsVar('unfoldEffort', $lang->task->unfoldEffort);

if(isInModal()) set::id("modal-record-hours-task-{$task->id}");

modalHeader
(
    set::title($lang->task->addEffort),
    set::entityID($task->id),
    to::suffix
    (
        span
        (
            setClass('flex gap-x-2 mx-3 nowrap'),
            $lang->task->estimate,
            span
            (
                setClass('label secondary-pale'),
                $task->estimate . $lang->task->suffixHour
            )
        ),
        span
        (
            setClass('flex gap-x-2 pr-4 nowrap'),
            $lang->task->consumed,
            span
            (
                setClass('label warning-pale'),
                span
                (
                    setID('totalConsumed'),
                    $task->consumed
                ),
                $lang->task->suffixHour
            )
        )
    )
);

if($efforts)
{
    /* 多人串行任务工时分两部分. */
    if(!empty($task->team) and $task->mode == 'linear')
    {
        include './linearefforts.html.php';
    }
    else
    {
        $effortRows = array();
        $i = 1;
        foreach($efforts as $effort)
        {
            $canOperateEffort = $this->task->canOperateEffort($task, $effort);
            $operateTips      = $canOperateEffort ? '' : $lang->task->effortOperateTips;
            $hidden           = ($taskEffortFold and $i > 3) ? 'hidden' : '';
            $effortRows[] = h::tr
            (
                setClass($hidden),
                h::td($effort->id),
                h::td($effort->date),
                h::td(zget($users, $effort->account)),
                h::td(html($effort->work), setClass('break-all')),
                h::td("{$effort->consumed} {$lang->task->suffixHour}"),
                h::td("{$effort->left} {$lang->task->suffixHour}"),
                h::td
                (
                    common::hasPriv($app->rawModule, 'editEffort') ? a
                    (
                        setClass('btn ghost toolbar-item square size-sm text-primary edit-effort'),
                        $canOperateEffort ? on::click()->call('loadModal', createLink($app->rawModule, 'editEffort', "id={$effort->id}")) : null,
                        !$canOperateEffort ? set::disabled(true) : null,
                        set::title($operateTips ? sprintf($operateTips, $lang->task->update) : ''),
                        icon('edit'),
                    ) : null,
                    common::hasPriv($app->rawModule, 'deleteWorkhour') ? a
                    (
                        setClass('btn ghost toolbar-item square size-sm ajax-submit text-primary'),
                        set('data-confirm', $lang->task->confirmDeleteEffort),
                        set::href(createLink($app->rawModule, 'deleteWorkhour', "id={$effort->id}")),
                        !$canOperateEffort ? set::disabled(true) : null,
                        set::title($operateTips ? sprintf($operateTips, $lang->delete) : ''),
                        icon('trash')
                    ) : null
                )
            );
            $i ++;
        }
        div
        (
            setClass('table-title'),
            $lang->task->committed
        );
        h::table
        (
            setClass('table condensed bordered taskEffort'),
            h::tr
            (
                h::th
                (
                    width('32px'),
                    $lang->idAB
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
                    $lang->task->consumedHours
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
            $effortRows
        );
        if(count($efforts) > 3)
        {
            $iconClass = $taskEffortFold ? 'angle-down' : 'angle-top';
            $iconText  = $taskEffortFold ? $lang->task->unfoldEffort : $lang->task->foldEffort;
            div
            (
                setID('toggleFoldIcon'),
                on::click('toggleFold'),
                setClass('text-primary'),
                span(setClass($iconClass . ' mr-1 icon-toggle'), icon('back-circle')),
                span(setClass('text'), $iconText)
            );
        }
    }
}

if(!$this->task->canOperateEffort($task))
{
    if($task->status != 'closed')
    {
        $notice = '';
        if(!isset($task->members[$app->user->account]))
        {
            $notice = html(sprintf($lang->task->deniedNotice, '<strong>' . $lang->task->teamMember . '</strong>', $lang->task->logEfforts));
        }
        elseif($task->assignedTo != $app->user->account and $task->mode == 'linear')
        {
            $notice = html(sprintf($lang->task->deniedNotice, '<strong>' . zget($users, $task->assignedTo) . '</strong>', $lang->task->logEfforts));
        }

        div
        (
            setClass('alert with-icon'),
            icon('exclamation-sign text-gray text-4xl'),
            div
            (
                setClass('content'),
                $notice
            )
        );
    }
}
else
{
    formBatchPanel
    (
        set::title($lang->task->addEffort),
        set::shadow(!isAjaxRequest('modal')),
        set::actions(array('submit')),
        set::actionsClass('btn-actions'),
        set::maxRows(3),
        formBatchItem
        (
            set::name('id'),
            set::label($lang->idAB),
            set::control('index'),
            set::width('32px')
        ),
        formBatchItem
        (
            set::required(true),
            set::name('date'),
            set::label($lang->task->date),
            set::width('130px'),
            set::control(array('control' => 'date', 'id' => '$GID')),
            set::value(helper::today())
        ),
        formBatchItem
        (
            set::name('work'),
            set::label($lang->task->work),
            set::width('auto'),
            set::control('textarea'),
            set::required($config->edition != 'open')
        ),
        formBatchItem
        (
            set::required(true),
            set::name('consumed'),
            set::label($lang->task->consumedHours),
            set::width('80px'),
            set::control
            (
                array(
                    'type' => 'inputControl',
                    'suffix' => $lang->task->suffixHour,
                    'suffixWidth' => 20
                )
            )
        ),
        formBatchItem
        (
            set::required(true),
            set::name('left'),
            set::label($lang->task->leftAB),
            set::width('80px'),
            set::control
            (
                array(
                    'type' => 'inputControl',
                    'suffix' => $lang->task->suffixHour,
                    'suffixWidth' => 20
                )
            )
        )
    );
}

render();
