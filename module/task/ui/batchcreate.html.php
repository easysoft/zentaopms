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

$items = array();

/* Field of ID. */
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->idAB,
    'control' => 'index',
    'width'   => '30px',
);

/* Field of module. */
$items[] = array
(
    'name'    => 'module',
    'label'   => $lang->task->module,
    'control' => 'select',
    'value'   => $moduleID,
    'items'   => $modules,
    'width'   => '180px',
);

if(!$hideStory)
{
    /* Field of story. */
    $items[] = array
    (
        'name'    => 'story',
        'label'   => $lang->task->story,
        'control' => 'select',
        'items'   => $stories,
        'width'   => '200px',
        'ditto'   => true,
    );

    /* Field of view story. */
    $items[] = array
    (
        'name'       => 'preview',
        'label'      => '',
        'labelClass' => 'hidden',
        'width'      => '40px',
        'control'    => array
        (
            'type'        => 'btn',
            'icon'        => 'eye',
            'class'       => 'btn-link',
            'hint'        => $lang->preview,
            'tagName'     => 'a',
            'url'         => '#',
            'data-toggle' => 'modal',
        )
    );

    /* Field of copy story. */
    $items[] = array
    (
        'name'       => 'copyStory',
        'label'      => '',
        'labelClass' => 'hidden',
        'width'      => '40px',
        'control'    => array
        (
            'type'  => 'btn',
            'icon'  => 'arrow-right',
            'class' => 'btn-link',
            'hint'  => $lang->task->copyStoryTitle,
        )
    );

    /* Hidden fields related to story. */
    $items[] = array
    (
        'name'  => 'storyEstimate',
        'label' => '',
        'labelClass' => 'hidden',
    );

    $items[] = array
    (
        'name'  => 'storyDesc',
        'label' => '',
        'labelClass' => 'hidden',
    );

    $items[] = array
    (
        'name'  => 'storyPri',
        'label' => '',
        'labelClass' => 'hidden',
    );
}

/* Field of name. */
$items[] = array
(
    'name'  => 'name',
    'label' => $lang->task->name,
    'width' => '165px',
);

/* Field of region and lane. */
if($execution->type == 'kanban')
{
    $items[] = array
    (
        'name'    => 'regions',
        'label'   => $lang->kanbancard->region,
        'control' => 'select',
        'value'   => $regionID,
        'items'   => $regionPairs,
        'width'   => '180px',
    );

    $items[] = array
    (
        'name'    => 'lanes',
        'label'   => $lang->kanbancard->lane,
        'control' => 'select',
        'value'   => $laneID,
        'items'   => $lanePairs,
        'width'   => '180px',
    );
}

/* Field of type. */
$items[] = array
(
    'name'    => 'type',
    'label'   => $lang->task->type,
    'control' => 'select',
    'items'   => $lang->task->typeList,
    'width'   => '100px',
    'ditto'   => true,
);

/* Field of assignedTo. */
$items[] = array
(
    'name'    => 'assignedTo',
    'label'   => $lang->task->assignedTo,
    'control' => 'select',
    'items'   => $members,
    'width'   => '130px',
    'ditto'   => true,
);

/* Field of estimate. */
$items[] = array
(
    'name'  => 'estimate',
    'label' => $lang->task->estimateAB,
    'width' => '70px',
);

/* Field of estStarted. */
$items[] = array
(
    'name'    => 'estStarted',
    'label'   => $lang->task->estStarted,
    'control' => 'date',
    'width'   => '120px',
    'ditto'   => true,
);

/* Field of deadline. */
$items[] = array
(
    'name'    => 'deadline',
    'label'   => $lang->task->deadline,
    'control' => 'date',
    'width'   => '120px',
    'ditto'   => true,
);

/* Field of desc. */
$items[] = array
(
    'name'    => 'desc',
    'label'   => $lang->task->desc,
    'control' => 'editor',
    'width'   => '150px',
);

/* Field of pri. */
$items[] = array
(
    'name'    => 'pri',
    'label'   => $lang->task->pri,
    'control' => 'select',
    'items'   => $lang->task->priList,
    'width'   => '130px',
    'ditto'   => true,
);

/* ====== Define the page structure with zin widgets ====== */

checkbox
(
    set::id('zeroTaskStory'),
    set::text($lang->story->zeroTask),
    on::change('toggleZeroTaskStory'),
);
formBatchPanel
(
    set::items($items),
    on::change('[data-name="module"]', 'setStories'),
    on::change('[data-name="story"]', 'setStoryRelated'),
    on::click('[data-name="copyStory"]', 'copyStoryTitle'),
    on::change('[data-name="regions"]', 'loadLanes'),
);

/* ====== Render page ====== */
render();
