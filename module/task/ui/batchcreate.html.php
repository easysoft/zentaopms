<?php
declare(strict_types=1);
/**
 * The batchCreate view file of task module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
namespace zin;
/* ====== Preparing and processing page data ====== */
jsVar('executionID', $execution->id);
jsVar('storyTasks', $storyTasks);

/* zin: Set variables to define picker options for form. */
$storyItem         = '';
$previewItem       = '';
$copyStoryItem     = '';
$storyEstimateItem = '';
$storyDescItem     = '';
$storyPriItem      = '';
if(!$hideStory)
{
    $storyItem = formBatchItem(
        set::name('story'),
        set::label($lang->task->story),
        set::control('select'),
        set::items($stories),
        set::width('200px'),
        set::ditto(true),
    );

    $previewItem = formBatchItem(
        set::name('preview'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::width('40px'),
        set::control('hidden'),
        btn
        (
            set('type', 'btn'),
            set('icon', 'eye'),
            set('class', 'btn-link'),
            set('hint', $lang->preview),
            set('tagName', 'a'),
            set('url', '#'),
            set('data-toggle', 'modal'),
        )
    );

    $copyStoryItem = formBatchItem(
        set::name('copyStory'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::width('40px'),
        set::control('hidden'),
        btn
        (
            set('type', 'btn'),
            set('icon', 'arrow-right'),
            set('class', 'btn-link'),
            set('hint', $lang->task->copyStoryTitle),
        )
    );

    $storyEstimateItem = formBatchItem(
        set::name('storyEstimate'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::control('hidden'),
    );

    $storyDescItem = formBatchItem(
        set::name('storyDesc'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::control('hidden'),
    );

    $storyPriItem = formBatchItem(
        set::name('storyPri'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::control('hidden'),
    );
}

/* Field of region and lane. */
$regionItem = '';
$laneItem   = '';
if($execution->type == 'kanban')
{
    $regionItem = formBatchItem(
        set::name('region'),
        set::label($lang->kanbancard->region),
        set::control('select'),
        set::value($regionID),
        set::items($regionPairs),
        set::width('180px'),
        set::ditto(true),
    );
    $laneItem = formBatchItem(
        set::name('lane'),
        set::label($lang->kanbancard->lane),
        set::control('select'),
        set::value($laneID),
        set::items($lanePairs),
        set::width('180px'),
        set::ditto(true),
    );
}


/* ====== Define the page structure with zin widgets ====== */

formBatchPanel
(
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('30px'),
    ),
    formBatchItem
    (
        set::name('module'),
        set::label($lang->task->module),
        set::control('select'),
        set::value($moduleID),
        set::items($modules),
        set::width('180px'),
        set::ditto(true),
    ),
    $storyItem,
    $previewItem,
    $copyStoryItem,
    $storyEstimateItem,
    $storyDescItem,
    $storyPriItem,
    formBatchItem
    (
        set::name('name'),
        set::label($lang->task->name),
        set::width('165px'),
    ),
    $regionItem,
    $laneItem,
    formBatchItem
    (
        set::name('type'),
        set::label($lang->task->type),
        set::control('select'),
        set::items($lang->task->typeList),
        set::width('100px'),
        set::ditto(true),
    ),
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->task->assignedTo),
        set::control('select'),
        set::items($members),
        set::width('130px'),
        set::ditto(true),
    ),
    formBatchItem
    (
        set::name('estimate'),
        set::label($lang->task->estimateAB),
        set::width('70px'),
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
        set::name('estStarted'),
        set::label($lang->task->estStarted),
        set::control('date'),
        set::width('120px'),
        set::ditto(true),
    ),
    formBatchItem
    (
        set::name('deadline'),
        set::label($lang->task->deadline),
        set::control('date'),
        set::width('120px'),
        set::ditto(true),
    ),
    formBatchItem
    (
        set::name('desc'),
        set::label($lang->task->desc),
        set::control('editor'),
        set::width('150px'),
    ),
    formBatchItem
    (
        set::name('pri'),
        set::label($lang->task->pri),
        set::control('select'),
        set::items($lang->task->priList),
        set::width('130px'),
        set::ditto(true),
    ),
    to::headingActions
    (
        checkbox
        (
            set::id('zeroTaskStory'),
            set::text($lang->story->zeroTask),
            on::change('toggleZeroTaskStory'),
        ),
    ),
    on::change('[data-name="module"]', 'setStories'),
    on::change('[data-name="story"]', 'setStoryRelated'),
    on::click('[data-name="copyStory"]', 'copyStoryTitle'),
    on::change('[data-name="region"]', 'loadLanes'),
);

/* ====== Render page ====== */
render();
