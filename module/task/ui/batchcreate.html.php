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
        set::control('picker'),
        set::items($stories),
        set::value($storyID),
        set::width('240px'),
        set::ditto(true)
    );

    $previewItem = formBatchItem(
        set::name('preview'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::width('40px'),
        set::control('hidden'),
        btn
        (
            set(
                array
                (
                    'type' => 'btn',
                    'icon' => 'eye',
                    'class' => 'ghost',
                    'hint' => $lang->preview,
                    'data-url' => '#',
                    'data-toggle' => 'modal',
                    'data-size' => 'lg',
                    'disabled' => true
                )
            )
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
            set(
                array
                (
                    'type' => 'btn',
                    'icon' => 'copy',
                    'class' => 'ghost',
                    'hint' => $lang->task->copyStoryTitle
                )
            )
        )
    );

    $storyEstimateItem = formBatchItem(
        set::name('storyEstimate'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::control('hidden')
    );

    $storyDescItem = formBatchItem(
        set::name('storyDesc'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::control('hidden')
    );

    $storyPriItem = formBatchItem(
        set::name('storyPri'),
        set::label(''),
        set('labelClass', 'hidden'),
        set::control('hidden')
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
        set::control('picker'),
        set::value($regionID),
        set::items($regionPairs),
        set::width('160px'),
        set::ditto(true),
        set::required(true),
        set::hidden($config->vision == 'lite')
    );
    $laneItem = formBatchItem(
        set::name('lane'),
        set::label($lang->kanbancard->lane),
        set::control('picker'),
        set::value($laneID),
        set::items($lanePairs),
        set::width('160px'),
        set::ditto(true),
        set::required(true),
        set::hidden($config->vision == 'lite')
    );
}


/* ====== Define the page structure with zin widgets ====== */

formBatchPanel
(
    set::title($lang->task->batchCreate),
    set::pasteField('name'),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('32px')
    ),
    formBatchItem
    (
        set::name('module'),
        set::label($lang->task->module),
        set::control('picker'),
        set::value($moduleID),
        set::items($modules),
        set::width('200px'),
        set::ditto(true)
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
        set::control('colorInput'),
        set::label($lang->task->name),
        set::width('240px')
    ),
    $regionItem,
    $laneItem,
    formBatchItem
    (
        set::name('type'),
        set::label($lang->task->type),
        set::control('picker'),
        set::items($lang->task->typeList),
        set::width('160px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('assignedTo'),
        set::label($lang->task->assignedTo),
        set::control('picker'),
        set::items($members),
        set::width('128px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('pri'),
        set::label($lang->task->pri),
        set::control('priPicker'),
        set::value(3),
        set::required(true),
        set::items($lang->task->priList),
        set::width('80px')
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
        set::name('estStarted'),
        set::label($lang->task->estStarted),
        set::control('date'),
        set::width('128px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('deadline'),
        set::label($lang->task->deadline),
        set::control('date'),
        set::width('128px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('desc'),
        set::label($lang->task->desc),
        set::control('textarea'),
        set::width('240px')
    ),
    to::headingActions
    (
        checkbox
        (
            setID('zeroTaskStory'),
            set::text($lang->story->zeroTask),
            on::change('toggleZeroTaskStory')
        )
    ),
    on::change('[data-name="module"]', 'setStories'),
    on::change('[data-name="story"]', 'setStoryRelated'),
    on::click('[data-name="copyStory"]', 'copyStoryTitle'),
    on::change('[data-name="region"]', 'loadLanes')
);

/* ====== Render page ====== */
render();
