<?php
declare(strict_types=1);
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

data('activeMenuID', 'story');
jsVar('executionID', $executionID);
jsVar('langPreview', $lang->preview);
jsVar('copyStoryTitleTip', $lang->task->copyStoryTitle);

$fields = array();
$fields['id']         = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');
$fields['module']     = array_merge(array('name' => 'module',     'label' => $lang->task->module,     'width' => '200px', 'ditto' => true,  'defaultDitto' => 'off', 'items'  => $modules,              'control' => 'picker'),    $config->task->form->batchcreate['module']);
$fields['story']      = array_merge(array('name' => 'story',      'label' => $lang->task->story,      'width' => '250px', 'ditto' => true,  'defaultDitto' => 'off', 'items'  => $storyPairs,           'control' => 'picker'),    $config->task->form->batchcreate['story']);
$fields['name']       = array_merge(array('name' => 'name',       'label' => $lang->task->name,       'width' => '250px', 'ditto' => false, 'defaultDitto' => 'off', 'filter' => 'trim',                'control' => 'text'),      $config->task->form->batchcreate['name']);
$fields['type']       = array_merge(array('name' => 'type',       'label' => $lang->typeAB,           'width' => '140px', 'ditto' => true,  'defaultDitto' => 'off', 'items'  => $lang->task->typeList, 'control' => 'picker'),    $config->task->form->batchcreate['type']);
$fields['assignedTo'] = array_merge(array('name' => 'assignedTo', 'label' => $lang->task->assignedTo, 'width' => '120px', 'ditto' => false, 'defaultDitto' => 'off', 'items'  => $members,              'control' => 'picker'),    $config->task->form->batchcreate['assignedTo']);
$fields['estimate']   = array_merge(array('name' => 'estimate',   'label' => $lang->task->estimateAB, 'width' => '120px', 'ditto' => true,  'defaultDitto' => 'off', 'filter' => 'trim',                'control' => 'text'),      $config->task->form->batchcreate['estimate']);
$fields['estStarted'] = array_merge(array('name' => 'estStarted', 'label' => $lang->task->estStarted, 'width' => '120px', 'ditto' => true,  'defaultDitto' => 'off', 'filter' => 'trim',                'control' => 'date'),      $config->task->form->batchcreate['estStarted']);
$fields['deadline']   = array_merge(array('name' => 'deadline',   'label' => $lang->task->deadline,   'width' => '120px', 'ditto' => true,  'defaultDitto' => 'off', 'filter' => 'trim',                'control' => 'date'),      $config->task->form->batchcreate['deadline']);
$fields['pri']        = array_merge(array('name' => 'pri',        'label' => $lang->task->pri,        'width' => '80px',  'ditto' => false, 'defaultDitto' => 'off', 'items'  => $lang->task->priList,  'control' => 'priPicker'), $config->task->form->batchcreate['pri']);

$data = array();
foreach($stories as $storyID => $story)
{
    if(str_contains(',draft,closed,', ",{$story->status},")) continue;

    $task = new stdclass();
    $task->id           = count($data) + 1;
    $task->module       = 0;
    $task->story        = $storyID;
    $task->name         = $story->title;
    $task->pri          = 3;
    $task->estimate     = $hourPointValue ? $story->estimate * $hourPointValue : $story->estimate;
    $task->type         = $taskType;
    $task->assignedTo   = '';
    $task->estStarted   = '';
    $task->deadline     = '';
    if(in_array('module', $syncFields)) $task->module = $story->module;
    if(in_array('pri', $syncFields)) $task->pri = $story->pri;
    if(in_array('assignedTo', $syncFields)) $task->assignedTo = $story->assignedTo;

    $data[] = $task;
}

formBatchPanel
(
    setID('dataform'),
    set::title($lang->story->batchToTask),
    set::items($fields),
    set::maxRows(count($data)),
    set::data($data),
    set::onRenderRow(jsRaw('renderRowData')),
    on::change('[data-name="module"]', 'setStories'),
    on::change('[data-name="story"]', 'setStoryRelated'),
    on::click('.copy-title-btn', 'copyStoryTitle'),
    formHidden('syncFields', implode(',', $syncFields))
);

render();
