<?php
declare(strict_types=1);
/**
 * The pause view file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu<liumengyi@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

/* ====== Define the page structure with zin widgets ====== */

modalHeader();
if(!empty($task->members) and (!isset($task->members[$app->user->account]) or ($task->assignedTo != $app->user->account and $task->mode == 'linear')))
{
    if($task->assignedTo != $app->user->account && $task->mode == 'linear')
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $task->assignedToRealName, $lang->task->pause);
    }
    else
    {
        $deniedNotice = sprintf($lang->task->deniedNotice, $lang->task->teamMember, $lang->task->pause);
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
    formPanel
    (
        setID('taskPauseForm'),
        formGroup
        (
            set::label($lang->comment),
            editor
            (
                set::name('comment'),
                set::rows('5')
            ),
            input(
                setClass('hidden'),
                set::name('status'),
                set::value('pause')
            )
        )
    );
    hr();
    history();
}

render();
