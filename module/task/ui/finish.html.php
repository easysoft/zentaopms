<?php
declare(strict_types=1);
/**
 * The finish view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

if(!$canRecordEffort)
{
    modalHeader();
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
        set::className('alert with-icon'),
        icon('exclamation-sign icon-3x'),
        div
        (
            set::className('content'),
            p
            (
                set::className('font-bold'),
                $deniedNotice
            )
        )
    );
}
else
{
    jsVar('consumed', empty($task->team) ? $task->consumed : (float)$task->myConsumed);
    jsVar('task', $task);
    jsVar('consumedEmpty', $lang->task->error->consumedEmptyAB);

    $realStarted = substr((string)$task->realStarted, 0, 19);
    if(helper::isZeroDate($realStarted)) $realStarted = '';

    if(!empty($task->team))
    {
        $consumedControl = formGroup
            (
                set::width('1/2'),
                set::label($lang->task->my . $lang->task->hasConsumed),
                div(
                    set::className('consumed'),
                    $task->myConsumed . $lang->task->suffixHour
                )
            );

        $assignedToControl = formGroup
            (
                set::width('1/2'),
                set::label($lang->story->assignTo),
                set::control('input'),
                set::disabled(true),
                set::value(zget($members, $task->nextBy)),
                formHidden('assignedTo', $task->nextBy)
            );
    }
    else
    {
        $consumedControl = null;

        $assignedToControl = formGroup
            (
                set::width('1/2'),
                set::name('assignedTo'),
                set::label($lang->story->assignTo),
                set::control('picker'),
                set::value($task->nextBy),
                set::items($members)
            );
    }

    modalHeader
    (
        to::suffix
        (
            span
            (
                setClass('flex gap-x-2 mr-3'),
                !empty($task->team) ? $lang->task->common . $lang->task->consumed : $lang->task->hasConsumed,
                span
                (
                    setClass('label secondary-pale'),
                    $task->consumed . $lang->task->suffixHour
                )
            ),
            span
            (
                setClass('flex gap-x-2'),
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

    formPanel
    (
        setID('finishForm'),
        set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
        $consumedControl,
        formGroup
        (
            set::width('1/2'),
            set::label($lang->task->currentConsumed),
            inputControl
            (
                input
                (
                    set::name('currentConsumed'),
                    set::value(0),
                    set::type('text')
                ),
                to::suffix($lang->task->suffixHour),
                set::suffixWidth(20)
            )
        ),
        $assignedToControl,
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->realBeganAB),
            datetimePicker
            (
                set::name('realStarted'),
                set::value($realStarted),
                set('disabled', !empty($realStarted))
            ),
            !empty($realStarted) ? formHidden('realStarted', $realStarted) : null
        ),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->project->realEndAB),
            datetimePicker
            (
                set::name('finishedDate'),
                set::value(helper::now())
            )
        ),
        formGroup
        (
            set::label($lang->story->files),
            upload()
        ),
        formGroup
        (
            set::name('comment'),
            set::label($lang->comment),
            set::control("editor")
        )
    );
    hr();
    history();
}

render();
