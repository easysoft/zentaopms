<?php
declare(strict_types=1);
/**
 * The batchedit view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
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

$items = array();

$items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'hidden', 'hidden' => true);
$items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '64px');
$items[] = array('name' => 'name', 'control' => 'colorInput', 'label' => $lang->task->name, 'width' => '240px');
$items[] = array('name' => 'module', 'label' => $lang->task->module, 'control' => 'picker', 'items' => $modules, 'width' => '200px', 'ditto' => true, 'defaultDitto' => 'off');

if(!$executionID || $execution->lifetime != 'ops') $items[] = array('name' => 'story', 'label' => $lang->task->story, 'control' => 'picker', 'items' => array(), 'width' => '300px', 'ditto' => true, 'defaultDitto' => 'off');

$items[] = array('name' => 'assignedTo', 'label' => $lang->task->assignedTo, 'control' => array('control' => 'taskAssignedTo'), 'items' => array(), 'width' => '128px', 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'type', 'label' => $lang->task->type, 'control' => 'picker', 'items' => $lang->task->typeList, 'width' => '128px', 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'status', 'label' => $lang->task->status, 'control' => 'picker', 'items' => $lang->task->statusList, 'width' => '128px', 'ditto' => true, 'defaultDitto' => 'off');
$items[] = array('name' => 'estStarted', 'label' => $lang->task->estStarted, 'control' => 'date', 'width' => '128px');
$items[] = array('name' => 'deadline', 'label' => $lang->task->deadline, 'control' => 'date', 'width' => '128px');
$items[] = array('name' => 'pri', 'label' => $lang->task->pri, 'control' => 'priPicker', 'ditto' => true, 'items' => $lang->task->priList, 'width' => '110px', 'defaultDitto' => 'off');
$items[] = array('name' => 'estimate', 'label' => $lang->task->estimateAB, 'width' => '64px', 'control' => array('type' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20));
$items[] = array('name' => 'consumed', 'label' => $lang->task->consumedThisTime, 'width' => '64px', 'control' => array('type' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20));
$items[] = array('name' => 'left', 'label' => $lang->task->leftAB, 'width' => '64px', 'control' => array('type' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20));

/* ====== Define the page structure with zin widgets ====== */
formBatchPanel
(
    set::title($lang->task->batchEdit),
    set::mode('edit'),
    set::data(array_values($tasks)),
    set::onRenderRow(jsRaw('renderRowData')),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchEditFields')),
    set::ajax(array('beforeSubmit' =>  jsRaw('clickSubmit'))),
    on::change('[data-name="type"]', 'changeType'),
    on::change('[data-name="status"]', 'statusChange'),
    on::change('[data-name="estStarted"], [data-name="deadline"]', 'checkBatchEstStartedAndDeadline'),
    on::change('[data-name="module"]', 'setStories'),
    on::change('input[name^=story]', 'setStoryRelated'),
    on::change('[data-name="estimate"],[data-name="consumed"]', 'computeForParent'),
    on::click('[data-name=story] [data-type=ditto]', 'setStoryRelated'),
    set::formID('taskBatchEditForm' . $executionID),
    set::items($items)
);
/* ====== Render page ====== */
render();
