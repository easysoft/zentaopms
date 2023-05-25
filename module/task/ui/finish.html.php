<?php
declare(strict_types=1);
/**
 * The finish view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      yourname<yourname@easycorp.ltd>
 * @package     task
 * @link        http://www.zentao.net
 */

namespace zin;

if(!$canRecordEffort)
{
    if($task->assignedTo != $app->user->account && $task->mode == 'linear')
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $task->assignedToRealName, $lang->task->finish);
    }
    else
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $lang->task->teamMember, $lang->task->finish);
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
    jsVar('task', $task);
    jsVar('consumedEmpty', $lang->task->error->consumedEmptyAB);

    $realStarted = substr($task->realStarted, 0, 19);
    if(helper::isZeroDate($realStarted)) $realStarted = '';

    if(!empty($task->team))
    {
        $consumedControl = formGroup
            (
                set::width('1/3'),
                set::label($lang->task->currentConsumed),
                div(
                    set::class('consumed'),
                    $task->myConsumed . $lang->task->suffixHour
                )
            );

        $assignedToControl = formHidden('assignedTo', $assignedTo);
    }
    else
    {
        $consumedControl = null;

        $assignedToControl = formGroup
            (
                set::width('1/4'),
                set::name('assignedTo'),
                set::label($lang->story->assignTo),
                set::control('picker'),
                set::value($task->nextBy),
                set::items($members)
            );
    }

    formPanel
    (
        set::title($lang->task->finishAction),
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
            set::width('1/3'),
            set::label(empty($task->team) ? $lang->task->hasConsumed : $lang->task->common . $lang->task->consumed),
            div(
                set::class('consumed'),
                $task->consumed . $lang->task->suffixHour
            )
        ),
        $consumedControl,
        formGroup
        (
            set::width('1/4'),
            set::label($lang->task->currentConsumed),
            inputGroup
            (
                control(set(array
                (
                    'name'  => 'currentConsumed',
                    'id'    => 'currentConsumed',
                    'value' => 0,
                    'type'  => 'text'
                ))),
                $lang->task->suffixHour
            )
        ),
        $assignedToControl,
        formGroup
        (
            set::width('1/3'),
            set::name('realStarted'),
            set::label($lang->project->realBeganAB),
            set::control('datetime'),
            set::value($realStarted),
            set('disabled', $realStarted)
        ),
        formGroup
        (
            set::width('1/3'),
            set::name('finishedDate'),
            set::label($lang->project->realEndAB),
            set::value(helper::now()),
            set::control('datetime')
        ),
        formGroup
        (
            set::width('2/3'),
            set::name('files[]'),
            set::label($lang->story->files),
            set::control('file')
        ),
        formGroup
        (
            set::width('2/3'),
            set::name('comment'),
            set::label($lang->comment),
            set::control("editor")
        ),
        history()
    );
}

render();
