<?php
declare(strict_types=1);
/**
 * The batchCreate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;

/* ====== Preparing and processing page data ====== */
jsVar('edition', $config->edition);
jsVar('parentID', (int)$parent);
jsVar('projectID', $project->id);
jsVar('executionID', $execution->id);
jsVar('storyTasks', $storyTasks);
jsVar('parentEstStarted', isset($parentTask) ? $parentTask->estStarted : '');
jsVar('parentDeadline', isset($parentTask) ? $parentTask->deadline : '');
jsVar('ignoreLang', $lang->project->ignore);
jsVar('overParentEstStartedLang', isset($parentTask) ? sprintf($lang->task->overParentEsStarted, $parentTask->estStarted) : '');
jsVar('overParentDeadlineLang', isset($parentTask) ? sprintf($lang->task->overParentDeadline, $parentTask->deadline) : '');
jsVar('taskHasConsumed', $taskConsumed > 0);
jsVar('langAddChildTask', $lang->task->addChildTask);
jsVar('taskDateLimit', $project->taskDateLimit);

$items = array();

$items[] = array('name' => 'module', 'label' => $lang->task->module, 'control' => 'picker', 'value' => $moduleID, 'items' => $modules, 'width' => '200px', 'ditto' => true);
if(!$hideStory)
{
    $items[] = array('name' => 'story', 'label' => $lang->task->story, 'control' => 'picker', 'items' => $stories, 'value' => $storyID, 'width' => '240px', 'ditto' => true);
    $items[] = array('name' => 'preview', 'label' => '', 'labelClass' => 'hidden', 'width' => '40px', 'control' => array('control' => 'btn', 'icon' => 'eye', 'class' => 'ghost', 'hint' => $lang->preview, 'data-url' => empty($storyID) ? '#' : createLink('story', 'view', "storyID={$storyID}"), 'data-toggle' => 'modal', 'data-size' => 'lg', 'disabled' => empty($storyID)));
    $items[] = array('name' => 'copyStory', 'label' => '', 'labelClass' => 'hidden', 'width' => '40px', 'control' => array('control' => 'btn', 'icon' => 'arrow-right', 'class' => 'ghost', 'hint' => $lang->task->copyStoryTitle));
    $items[] = array('name' => 'storyEstimate', 'label' => '', 'labelClass' => 'hidden', 'value' => isset($story->estimate) ? $story->estimate : '', 'control' => 'hidden', 'hidden' => true);
    $items[] = array('name' => 'storyDesc', 'label' => '', 'labelClass' => 'hidden', 'value' => isset($story->desc) ? $story->desc : '', 'control' => 'hidden', 'hidden' => true);
    $items[] = array('name' => 'storyPri', 'label' => '', 'labelClass' => 'hidden', 'value' => isset($story->pri) ? $story->pri : '3', 'control' => 'hidden', 'hidden' => true);
}

/* Field of region and lane. */
if($execution->type == 'kanban')
{
    $items[] = array('name' => 'region', 'label' => $lang->kanbancard->region, 'control' => 'picker', 'value' => $regionID, 'items' => $regionPairs, 'width' => '166px', 'ditto' => true, 'required' => true, 'hidden' => $config->vision == 'lite');
    $items[] = array('name' => 'lane',   'label' => $lang->kanbancard->lane,   'control' => 'picker', 'value' => $laneID,   'items' => $lanePairs,   'width' => '166px', 'ditto' => true, 'required' => true, 'hidden' => $config->vision == 'lite');
}
$items[] = array('name' => 'name', 'control' => 'colorInput', 'label' => $lang->task->name, 'width' => '240px');
$items[] = array('name' => 'isParent', 'control' => 'hidden', 'value' => '0');
$items[] = array('name' => 'type', 'label' => $lang->task->type, 'control' => 'picker', 'items' => $lang->task->typeList, 'value' => 'devel', 'width' => '160px', 'ditto' => true, 'defaultDitto' => !empty($_POST['deliverableIdList']) ? false : true, 'data-on' => 'change', 'data-call' => 'changeType', 'data-params' => 'event');
$items[] = array('name' => 'assignedTo', 'label' => $lang->task->assignedTo, 'control' => array('control' => 'taskAssignedTo', 'manageLink' => ($manageLink ? $manageLink : '')), 'items' => $members, 'width' => '128px', 'ditto' => true);
$items[] = array('name' => 'pri', 'label' => $lang->task->pri, 'control' => array('control' => 'priPicker', 'required' => true), 'value' => 3, 'ditto' => true, 'items' => $lang->task->priList, 'width' => '110px');
$items[] = array('name' => 'estimate', 'label' => $lang->task->estimateAB, 'width' => '110px', 'ditto' => true, 'control' => array('type' => 'inputControl', 'suffix' => $lang->task->suffixHour, 'suffixWidth' => 20));
$items[] = array('name' => 'estStarted', 'label' => $lang->task->estStarted, 'control' => 'date', 'width' => '128px', 'ditto' => true);
$items[] = array('name' => 'deadline', 'label' => $lang->task->deadline, 'control' => 'date', 'width' => '128px', 'ditto' => true);
$items[] = array('name' => 'desc', 'label' => $lang->task->desc, 'control' => 'textarea', 'width' => '240px');

$batchFormOptions = array();
$batchFormOptions['fixedActions']  = true; // 滚动时固定操作列。
$batchFormOptions['actions']       = array(array('type' => 'addSibling', 'icon' => 'icon-plus', 'text' => $lang->task->addSibling), array('type' => 'addSub', 'icon' => 'icon-split', 'text' => $lang->task->addSub), 'delete');
$batchFormOptions['onClickAction'] = jsRaw('window.handleClickBatchFormAction'); // 定义操作列按钮点击事件处理函数。
$batchFormOptions['onRenderRow']   = jsRaw('window.handleRenderRow'); // 定义行渲染事件处理函数。
if($config->vision == 'lite') unset($batchFormOptions['fixedActions'], $batchFormOptions['actions'], $batchFormOptions['onClickAction']);

/* ====== Define the page structure with zin widgets ====== */
formBatchPanel
(
    set::id('taskBatchCreateForm'),
    set::title($lang->task->batchCreate),
    set::pasteField('name'),
    set::customFields(array('list' => $customFields, 'show' => explode(',', $showFields), 'key' => 'batchCreateFields')),
    set::headingActionsClass('flex-auto row-reverse justify-between w-11/12'),
    set::batchFormOptions($batchFormOptions),
    to::headingActions
    (
        checkbox
        (
            setID('zeroTaskStory'),
            set::text($lang->story->zeroTask),
            set::rootClass('items-center'),
            on::change('toggleZeroTaskStory')
        )
    ),
    set::items($items),
    set::data(!empty($tasks) ? $tasks : array()),
    on::change('[data-name="type"]', 'changeType'),
    on::change('[data-name="module"]', 'setStories'),
    on::change('input[name^=story]', 'setStoryRelated'),
    on::click('[data-name=story] [data-type=ditto]', 'setStoryRelated'),
    on::click('[data-name="copyStory"]', 'copyStoryTitle'),
    on::change('[data-name="region"]', 'loadLanes'),
    on::change('[data-name="estStarted"], [data-name="deadline"]', 'checkBatchEstStartedAndDeadline')
);

/* ====== Render page ====== */
render();
