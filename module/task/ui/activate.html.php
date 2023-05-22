<?php
declare(strict_types=1);
/**
 * The activate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
/* ====== Preparing and processing page data ====== */

/* zin: Set variables to define control for form. */
$taskModeBox = '';
if($isMultiple)
{
    $taskModeBox = formGroup
    (
        set::width('1/4'),
        set::label($lang->task->mode),
        inputGroup
        (
            set::class('no-background'),
            zget($lang->task->modeList, $task->mode),
            input
            (
                set::class('hidden'),
                set::name('mode'),
                set::value($task->mode),
            ),
        ),
    );
}

$manageTeamBox = '';
if($isMultiple)
{
    $manageTeamBox = formGroup(
        set::width('1/10'),
        setClass('items-center'),
        checkbox
        (
            set::name('multiple'),
            set::text($lang->task->manageTeam),
            set::rootClass('ml-4'),
        )
    );
}

$leftBox = '';
if($task->parent != '-1')
{
    $leftBox = formGroup(
        set::width('1/4'),
        set::label($lang->task->left),
        set::name('left'),
        inputControl
        (
            to::suffix($lang->task->suffixHour),
            set::suffixWidth(20),
        ),
    );
}

/* ====== Define the page structure with zin widgets ====== */
formPanel
(
    $taskModeBox,
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->task->assignedTo),
            set::name('assignedTo'),
            set::items($isMultiple ? $teamMembers : $members),
            set::value($isMultiple ? '' : $task->finishedBy),
            set::required($isMultiple),
        ),
        $manageTeamBox,
    ),
    $leftBox,
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('5'),
        )
    ),
);

/* ====== Render page ====== */
render();

