<?php
declare(strict_types=1);
/**
 * The finish view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
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

    $realStarted = substr((string)$task->realStarted, 0, 19);
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
                set::width('1/3'),
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
        set::shadow(!isonlybody()),
        to::heading
        (
            entityLabel
            (
                setClass('my-3 gap-x-3'),
                set::level(1),
                set::text($task->name),
                set::entityID($task->id),
                set::reverse(true),
            ),
        ),
        to::headingActions
        (
            span
            (
                setClass('flex gap-x-2 mr-3'),
                $lang->task->hasConsumed,
                span
                (
                    set::class('label secondary-pale'),
                    $task->consumed . $lang->task->suffixHour,
                ),
            ),
            span
            (
                setClass('flex gap-x-2'),
                $lang->task->consumed,
                span
                (
                    set::class('label warning-pale'),
                    $task->consumed . $lang->task->suffixHour,
                )
            ),
        ),
        $consumedControl,
        formGroup
        (
            set::width('1/3'),
            set::label($lang->task->currentConsumed),
            inputControl
            (
                input
                (
                    set::name('currentConsumed'),
                    set::value(0),
                    set::type('text'),
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20),
            ),
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
            set::name('comment'),
            set::label($lang->comment),
            set::control("editor")
        ),
    );
}

h::hr(set::class('mt-6'));

history();

render(isonlybody() ? 'modalDialog' : 'page');
