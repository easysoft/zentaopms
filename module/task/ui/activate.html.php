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
jsVar('oldConsumed', $task->consumed);
jsVar('currentUser', $app->user->account);
jsVar('members', $members);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('teamLeftEmpty', $lang->task->error->teamLeftEmpty);
jsVar('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));
jsVar('estimateNotEmpty', sprintf($lang->task->error->notempty, $lang->task->estimate));
jsVar('leftNotEmpty', sprintf($lang->task->error->notempty, $lang->task->left));
jsVar('teamNotEmpty', sprintf($lang->error->notempty, $lang->task->assignedTo));
jsVar('isMultiple', $isMultiple);
jsVar('taskMode', $task->mode);
if($isMultiple) jsVar('assignedToHtml', html::select('assignedTo', $teamMembers, '', "class='form-control' disabled"));

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

$modalTeamBtn = array();
if($isMultiple)
{
    $modalTeamBtn = btn(
        set::text($lang->task->team),
        set::class('team-group hidden'),
        set::url('#modalTeam'),
        set('data-toggle', 'modal'),
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
            set::required($isMultiple),
            inputGroup
            (
                select
                (
                    set::name('assignedTo'),
                    set::items($isMultiple ? $teamMembers : $members),
                    set::value($isMultiple ? '' : $task->finishedBy),
                ),
                $modalTeamBtn,
            ),
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

$teamData = array();
if($isMultiple)
{
    $index = 1;
    foreach($task->team as $member)
    {
        $member->id           = $index;
        $member->team         = $member->account;
        $member->teamSource   = $member->account;
        $member->teamEstimate = $member->estimate;
        $member->teamConsumed = $member->consumed;
        $member->teamLeft     = $member->left;

        $teamData[] = $member;
        $index ++;
    }
}

modalTrigger
(
    modal
    (
        set::id('modalTeam'),
        set::title($lang->task->team),
        to::footer
        (
            set::footerClass('flex-center'),
            btn
            (
                set::class('primary btn-wide'),
                set::id('confirmButton'),
                set::text($lang->confirm),
            )
        ),
        formBatch
        (
            set::mode('edit'),
            set::data($teamData),
            set::minRows(10),
            set::actions(array()),
            formBatchItem
            (
                set::name('id'),
                set::label($lang->task->id),
                set::control('index'),
                set::width('10px'),
            ),
            formBatchItem
            (
                set::name('team'),
                set::label($lang->task->assignedTo),
                set::control('select'),
                set::items($members),
                set::width('50px'),
                input
                (
                    set::name('teamSource'),
                    set::class('hidden'),
                )
            ),
            formBatchItem
            (
                set::name('teamEstimate'),
                set::label($lang->task->estimate),
                set::width('50px'),
            ),
            formBatchItem
            (
                set::name('teamConsumed'),
                set::label($lang->task->consumed),
                set::width('50px'),
            ),
            formBatchItem
            (
                set::name('teamLeft'),
                set::label($lang->task->left),
                set::width('50px'),
            ),
        ),
    )
);

/* ====== Render page ====== */
render();

