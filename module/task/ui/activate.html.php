<?php
declare(strict_types=1);
/**
 * The activate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
$fields = useFields('task.activate');
/* ====== Preparing and processing page data ====== */
jsVar('oldConsumed', $task->consumed);
jsVar('currentUser', $app->user->account);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('teamLeftEmpty', $lang->task->error->teamLeftEmpty);
jsVar('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]));
jsVar('estimateNotEmpty', sprintf($lang->task->error->notempty, $lang->task->estimate));
jsVar('leftNotEmpty', sprintf($lang->task->error->notempty, $lang->task->left));
jsVar('teamNotEmpty', sprintf($lang->error->notempty, $lang->task->assignedTo));
jsVar('isMultiple', $isMultiple);
jsVar('taskMode', $task->mode);

$teamData  = array();
$teamUsers = array();
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

        $teamData[]  = $member;
        $teamUsers[] = $member->account;
        $index ++;
    }

    $teamItems = array();
    foreach($teamMembers as $key => $value) $teamItems[] = array('text' => $value, 'value' => $key);
    jsVar('teamItems', $teamItems);
}

$memberItems = array();
foreach($members as $key => $value) $memberItems[] = array('text' => $value, 'value' => $key);
jsVar('memberItems', $memberItems);

if(!empty($task->team))
{
    foreach($task->team as $member)
    {
        $member->memberDisabled = false;
        if($member->status == 'done') $member->memberDisabled = true;

        $member->hourDisabled = $member->memberDisabled;
        if($task->mode == 'multi') $member->hourDisabled = false;
    }
}

/* zin: Set variables to define control for form. */
modalHeader();
/* ====== Define the page structure with zin widgets ====== */
formPanel
(
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    set::fields($fields),
    modalTrigger
    (
        modal
        (
            setID('modalTeam'),
            setData(array('backdrop' => false)),
            set::title($lang->task->team),
            formBatch
            (
                set::tagName('div'),
                setID('teamTable'),
                set::mode('add'),
                !empty($task->team) ? set::data(array_values($task->team)) : null,
                set::sortable(true),
                set::size('sm'),
                set::minRows(3),
                set::onRenderRow(jsRaw('renderRowData')),
                formBatchItem
                (
                    set::name('id'),
                    set::width('32px'),
                    set::control('index')
                ),
                formBatchItem
                (
                    set::name('team'),
                    set::label($lang->task->teamMember),
                    set::width('160px'),
                    set::control('picker'),
                    set::items($members)
                ),
                formBatchItem
                (
                    set::name('estimateBox'),
                    set::label($lang->task->estimateAB),
                    set::width('135px'),
                    set::control('inputGroup'),
                    inputControl
                    (
                        input(set::name("teamEstimate")),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                ),
                !empty($task->team) ? formBatchItem
                (
                    set::name('consumedBox'),
                    set::label($lang->task->consumedAB),
                    set::width('135px'),
                    set::control('inputGroup'),
                    inputControl
                    (
                        input(set::name("teamConsumed")),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                ) : null,
                !empty($task->team) ? formBatchItem
                (
                    set::name('leftBox'),
                    set::label($lang->task->left),
                    set::width('135px'),
                    set::control('inputGroup'),
                    set::required(true),
                    inputControl
                    (
                        input(set::name("teamLeft")),
                        to::suffix($lang->task->suffixHour),
                        set::suffixWidth(20)
                    )
                ) : null,
                formBatchItem
                (
                    set::name('teamSource'),
                    set::control('input'),
                    set::hidden(true)
                ),
                set::actions(array(array('text' => $lang->save, 'class' => 'primary team-saveBtn', 'id' => 'confirmButton')))
            )
        )
    )
);
hr();
history();

/* ====== Render page ====== */
render();
