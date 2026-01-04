<?php
declare(strict_types=1);
/**
 * The batchedit view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
/* ====== Preparing and processing page data ====== */

$noPauseStatusList = array();
foreach($lang->task->statusList as $status => $label)
{
    if ($status == 'pause') continue;
    $noPauseStatusList[] = array('text' => $label, 'value' => $status, 'key' => $label);
}

/* zin: Set variables to define picker options for form. */
jsVar('executionTeams', $executionTeams);
jsVar('users', $users);
jsVar('teams', $teams);
jsVar('currentUser', $app->user->account);
jsVar('moduleGroup', $moduleGroup);
jsVar('executionID', $executionID);
jsVar('childTasks', $childTasks);
jsVar('nonStoryChildTasks', $nonStoryChildTasks);
jsVar('childrenDateLimit', $childrenDateLimit);
jsVar('tasks', $tasks);
jsVar('noPauseStatusList', $noPauseStatusList);
jsVar('stories', $stories);
jsVar('syncStoryToChildrenTip', $lang->task->syncStoryToChildrenTip);
jsVar('parentTasks', $parentTasks);
jsVar('manageLinkList', $manageLinkList);
jsVar('noSprintPairs', $noSprintPairs);
jsVar('ignoreLang', $lang->project->ignore);
jsVar('overParentEstStartedLang', $lang->task->overParentEsStarted);
jsVar('overParentDeadlineLang', $lang->task->overParentDeadline);
jsVar('overChildEstStartedLang', $lang->task->overChildEstStarted);
jsVar('overChildDeadlineLang', $lang->task->overChildDeadline);
jsVar('manageTeamMemberText', $lang->execution->manageTeamMember);
jsVar('taskDateLimit', empty($project) ? '' : $project->taskDateLimit);

/* ====== Define the page structure with zin widgets ====== */
formBatchPanel
(
    set::title($lang->task->batchEdit),
    set::mode('edit'),
    set::data(array_values($tasks)),
    set::onRenderRow(jsRaw('renderRowData')),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchEditFields')),
    set::ajax(array('beforeSubmit' =>  jsRaw('clickSubmit'))),
    on::change('[data-name="status"]', 'statusChange'),
    on::change('[data-name="estStarted"], [data-name="deadline"]', 'checkBatchEstStartedAndDeadline'),
    on::change('[data-name="module"]', 'setStories'),
    on::change('input[name^=story]', 'setStoryRelated'),
    on::click('[data-name=story] [data-type=ditto]', 'setStoryRelated'),
    set::formID('taskBatchEditForm' . $executionID),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('hidden'),
        set::hidden(true)
    ),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('64px')
    ),
    formBatchItem
    (
        set::name('name'),
        set::control('colorInput'),
        set::label($lang->task->name),
        set::width('240px')
    ),
    formBatchItem
    (
        set::name('module'),
        set::label($lang->task->module),
        set::control('picker'),
        set::items($modules),
        set::width('200px'),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    $executionID && $execution->lifetime == 'ops' ? null : formBatchItem
    (
        set::name('story'),
        set::label($lang->task->story),
        set::control('picker'),
        set::items(array()),
        set::width('300px'),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->task->assignedTo),
        set::control(array('control' => 'taskAssignedTo')),
        set::items(array()),
        set::width('128px'),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    formBatchItem
    (
        set::name('type'),
        set::label($lang->task->type),
        set::control('picker'),
        set::items($lang->task->typeList),
        set::width('128px'),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    formBatchItem
    (
        set::name('status'),
        set::label($lang->task->status),
        set::control('picker'),
        set::items($lang->task->statusList),
        set::width('128px'),
        set::ditto(true),
        set::defaultDitto('off')
    ),
    formBatchItem
    (
        set::name('estStarted'),
        set::label($lang->task->estStarted),
        set::control('date'),
        set::width('128px')
    ),
    formBatchItem
    (
        set::name('deadline'),
        set::label($lang->task->deadline),
        set::control('date'),
        set::width('128px')
    ),
    formBatchItem
    (
        set::name('pri'),
        set::label($lang->task->pri),
        set::control('priPicker'),
        set::ditto(true),
        set::items($lang->task->priList),
        set::width('110px'),
        set::defaultDitto('off')
    ),
    formBatchItem
    (
        set::name('estimate'),
        set::label($lang->task->estimateAB),
        set::width('64px'),
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
        set::name('consumed'),
        set::label($lang->task->consumedThisTime),
        set::width('64px'),
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
        set::name('left'),
        set::label($lang->task->leftAB),
        set::width('64px'),
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
/* ====== Render page ====== */
render();
