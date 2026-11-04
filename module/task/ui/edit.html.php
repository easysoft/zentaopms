<?php
declare(strict_types=1);
/**
 * The edit view of task of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zenggang<zenggang@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

namespace zin;

$taskStatus = isset($task->rawStatus) ? $task->rawStatus : $task->status;

/* ====== Preparing and processing page data ====== */
jsVar('projectID', $task->project);
jsVar('oldStoryID', $task->story);
jsVar('oldAssignedTo', $task->assignedTo);
jsVar('oldExecutionID', $task->execution);
jsVar('oldConsumed', $task->consumed);
jsVar('objectID', $execution->multiple ? $execution->id : $execution->project);
jsVar('taskStatus', $taskStatus);
jsVar('currentUser', $app->user->account);
jsVar('team', array_values($task->members));
jsVar('members', $members);
jsVar('page', 'edit');
jsVar('confirmChangeExecution', $lang->task->confirmChangeExecution);
jsVar('teamMemberError', $lang->task->error->teamMember);
jsVar('totalLeftError', sprintf($this->lang->task->error->leftEmptyAB, zget($this->lang->task->statusList, $taskStatus)));
jsVar('confirmRecord', $lang->task->confirmRecord);
jsVar('estimateNotEmpty', sprintf($lang->error->gt, $lang->task->estimate, '0'));
jsVar('leftNotEmpty', sprintf($lang->error->gt, $lang->task->left, '0'));
jsVar('requiredFields', $config->task->edit->requiredFields);
jsVar('+parentEstStarted', !empty($parentTask) ? $parentTask->estStarted : '');
jsVar('+parentDeadline', !empty($parentTask) ? $parentTask->deadline : '');
jsVar('childDateLimit', $childDateLimit);
jsVar('ignoreLang', $lang->project->ignore);
jsVar('+overParentEstStartedLang', !empty($parentTask) ? sprintf($lang->task->overParentEsStarted, $parentTask->estStarted) : '');
jsVar('+overParentDeadlineLang', !empty($parentTask) ? sprintf($lang->task->overParentDeadline, $parentTask->deadline) : '');
jsVar('+overChildEstStartedLang', sprintf($lang->task->overChildEstStarted, $childDateLimit['estStarted']));
jsVar('+overChildDeadlineLang', sprintf($lang->task->overChildDeadline, $childDateLimit['deadline']));
jsVar('taskDateLimit', empty($project) ? '' : $project->taskDateLimit);

$confirmSyncTip = '';
if(!empty($syncChildren) && !empty($task->children)) $confirmSyncTip = sprintf($lang->task->syncStoryToChildrenTip, 'ID' . implode(', ID', $syncChildren));
jsVar('confirmSyncTip', $confirmSyncTip);
jsVar('taskID', $task->id);
jsVar('taskStory', $task->story);

/* 检查是否可以开始执行。*/
if($this->config->edition == 'ipd')
{
    $execution->canStartExecution = $this->loadModel('execution')->checkStageStatus($execution->id, 'start');
    $task->executionInfo          = $execution;
}

if(!empty($task->team))
{
    foreach($task->team as $member)
    {
        $member->team           = $member->account;
        $member->teamSource     = $member->account;
        $member->teamEstimate   = (float)$member->estimate;
        $member->teamConsumed   = (float)$member->consumed;
        $member->teamLeft       = (float)$member->left;
        $member->memberDisabled = false;
        $member->memberStatus   = $member->status;
        if($member->memberStatus == 'done') $member->memberDisabled = true;
        if(strpos('|closed|cancel|pause|', $taskStatus) !== false && $app->rawMethod != 'activate')
        {
            $member->memberStatus   = $taskStatus;
            $member->memberDisabled = true;
        }

        $member->hourDisabled = $member->memberDisabled;
    }
}

/* ====== Load fields ====== */
$fields = useFields('task.edit');

/* ====== Define form ====== */
detailForm
(
    /* Event handlers */
    on::change('[name=execution]', 'loadAll'),
    on::change('[name=module]', 'loadStories'),
    on::change('[name=parent]', 'getParentEstStartedAndDeadline'),
    on::change('[name=mode]', 'changeMode'),
    on::change('[name=showAllModule]', 'loadAllModule'),
    on::change('[name=estStarted]', 'checkEstStartedAndDeadline'),
    on::change('[name=deadline]', 'checkEstStartedAndDeadline'),
    on::change('[name=story]', 'setStoryModule'),
    on::change('[name=status]')->do('statusChange(target)'),
    $task->mode == 'multi' ? on::click('.team-group')->call('disableMembers') : null,
    set::object($task),
    set::objectType('task'),
    set::objectID($task->id),
    set::fields($fields),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    to::suffix
    (
        aiAgentEntry
        (
            set::module('task'),
            set::method('edit'),
            set::type('form')
        )
    ),
    to::side
    (
        modalTrigger
        (
            modal
            (
                setID('modalTeam'),
                set::title($lang->task->teamMember),
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
                        set::control(array('control' => 'taskAssignedTo', 'manageLink' => ($manageLink ? $manageLink : ''))),
                        set::items($members)
                    ),
                    formBatchItem
                    (
                        set::name('estimateBox'),
                        set::label($lang->task->estimate),
                        set::width('135px'),
                        set::control('inputGroup'),
                        set::required(true),
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
                    set::actions(array(array('text' => $lang->save, 'class' => 'primary team-saveBtn'))),
                    on::click('.team-saveBtn')->call('saveTeam')
                )
            )
        )
    )
);

render();
