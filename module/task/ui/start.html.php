<?php
declare(strict_types=1);
/**
 * The start view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
/* ====== Preparing and processing page data ====== */
jsVar('confirmFinish', $lang->task->confirmFinish);
jsVar('noticeTaskStart', $lang->task->noticeTaskStart);

/* zin: Set variables to define control for form. */
if($task->mode == 'linear')
{
    $assignedToControl = inputGroup(
        set::class('no-background'),
        zget($members, $assignedTo),
        input
        (
            set::class('hidden'),
            set::name('assignedTo'),
            set::value($assignedTo),
        )
    );
}
else
{
    $assignedToControl = select(
        set::name('assignedTo'),
        set::value($assignedTo),
        set::items($members),
    );
}

/* ====== Define the page structure with zin widgets ====== */

if(!$canRecordEffort)
{
    if($task->assignedTo != $app->user->account && $task->mode == 'linear')
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $task->assignedToRealName, $lang->task->start);
    }
    else
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $lang->task->teamMember, $lang->task->start);
    }

    div
    (
        set::class('alert with-icon'),
        icon('exclamation-sign'),
        div
        (
            set::class('content'),
            p
            (
                set::class('font-bold'),
                $deniedNotice
            )
        )
    );
}
else
{
    formPanel
    (
        set::title($lang->task->startAction),
        set::headingClass('status-heading'),
        set::titleClass('form-label .form-grid'),
        to::headingActions
        (
            entityLabel
            (
                setClass('my-3 gap-x-3'),
                set::level(1),
                set::text($task->name),
                set::entityID($task->id),
                set::reverse(true),
            )
        ),
        formGroup
        (
            set::class($task->mode == 'multi' ? 'hidden' : ''),
            set::width('1/3'),
            set::label($lang->task->assignedTo),
            $assignedToControl,
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->task->realStarted),
            set::name('realStarted'),
            set::control('date'),
            set::value(helper::isZeroDate($task->realStarted) ? helper::now() : $task->realStarted)
        ),
        formRow
        (
            formGroup
            (
                set::width('1/3'),
                set::label($task->mode == 'linear' ? $lang->task->myConsumed : $lang->task->consumed),
                inputControl
                (
                    input
                    (
                        set::name('consumed'),
                        set::value(!empty($currentTeam) ? (float)$currentTeam->consumed : $task->consumed),
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20),
                ),
            ),
            formGroup
            (
                set::width('1/3'),
                set::label($lang->task->left),
                inputControl
                (
                    input
                    (
                        set::name('left'),
                        set::value(!empty($currentTeam) ? (float)$currentTeam->left : $task->left),
                    ),
                    to::suffix($lang->task->suffixHour),
                    set::suffixWidth(20),
                ),
            ),
        ),
        formGroup
        (
            set::label($lang->comment),
            editor
            (
                set::name('comment'),
                set::rows('5'),
            )
        ),
        history()
    );
}

/* ====== Render page ====== */
render();
