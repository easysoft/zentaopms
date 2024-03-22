<?php
declare(strict_types=1);
/**
* The execution statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$active    = isset($params['active']) ? $params['active'] : key($executions);
$execution = new stdclass();
$execution->progress          = '';
$execution->doneStory         = '';
$execution->totalStory        = '';
$execution->totalTask         = '';
$execution->undoneTask        = '';
$execution->yesterdayDoneTask = '';

$items = array();
foreach($executions as $executionItem)
{
    $params  = helper::safe64Encode("module={$block->module}&projectID={$currentProjectID}&active={$executionItem->id}");
    $items[] = array
    (
        'id'        => $executionItem->id,
        'text'      => $executionItem->name,
        'url'       => createLink('execution', 'task', "executionID={$executionItem->id}"),
        'activeUrl' => createLink('block', 'printBlock', "blockID={$block->id}&params={$params}")
    );
    if($executionItem->id == $active) $execution = $executionItem;
}

$projectItems = array();
$projectItems[] = array('text' => $lang->block->executionstatistic->allProject, 'data-url' => createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("module={$block->module}")), 'data-on' => 'click', 'data-do' => "loadBlock('$block->id', options.url)");
foreach($projects as $projectID => $projectName)
{
    $url = createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("module={$block->module}&project={$projectID}"));
    $projectItems[] = array('text' => $projectName, 'data-url' => $url, 'data-on' => 'click', 'data-do' => "loadBlock('$block->id', options.url)");
}

$burn = center
(
    setClass((!$longBlock ? ' py-6 w-1/2' : '')),
    cell
    (
        setClass('flex-1 w-full'),
        div
        (
            $longBlock ? setClass('pb-2') : null,
            span(setClass('font-bold'), $lang->block->executionstatistic->burn)
        ),
        div
        (
            setClass('py-2 chart line-chart'),
            echarts
            (
                set::color(array('#2B80FF', '#17CE97')),
                set::grid(array('left' => 0, 'bottom' => 0, 'top' => 0, 'right' => 0)),
                set::legend(array('show' => false, 'width' => '100%')),
                set::xAxis(array('show' => false, 'type' => 'category', 'data' => $chartData['labels'], 'boundaryGap' => false)),
                set::yAxis(array('show' => false)),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type'       => 'line',
                            'data'       => $chartData['baseLine'],
                            'symbolSize' => 0,
                            'itemStyle'  => array('normal' => array('color' => '#D8D8D8', 'lineStyle' => array('width' => 2, 'color' => '#F1F1F1')))
                        ),
                        array
                        (
                            'data'       => $chartData['burnLine'],
                            'type'       => 'line',
                            'symbolSize' => 0,
                            'areaStyle'  => array('color' => array('type' => 'linear', 'x' => '0', 'y' => '0', 'x2' => '0', 'y2' => '1', 'colorStops' => array(array('offset' => 0, 'color' => '#DDECFE'), array('offset' => 1, 'color' => '#FFF')), 'global' => false))
                        )
                    )
                )
            )->size('100%', $longBlock ? 64 : 80)
        )
    )
);

statisticBlock
(
    to::titleSuffix
    (
        dropdown
        (
            btn
            (
                setClass('font-normal rounded-full gray-400-outline size-md ml-3'),
                set::caret(true),
                isset($projects[$currentProjectID]) ? $projects[$currentProjectID] : $lang->block->executionstatistic->allProject,
            ),
            set::items($projectItems)
        )
    ),
    set::block($block),
    set::active($active),
    set::moreLink(createLink('execution', 'all', 'status=' . $block->params->type)),
    set::items($items),
    div
    (
        setClass('flex h-full ' . ($longBlock ? '' : 'col')),
        cell
        (
            setClass('flex-1'),
            $longBlock ? set('width', '70%') : null,
            div
            (
                setClass('flex h-full ' . ($longBlock ? '' : 'col')),
                cell
                (
                    $longBlock ? set('width', '40%') : null,
                    setClass('p-4'),
                    div
                    (
                        setClass('flex align-center justify-around' . ($longBlock ? ' py-4' : '')),
                        center
                        (
                            setClass('w-1/2'),
                            center
                            (
                                setClass('relative w-28 h-28 hide-before-init opacity-0 transition-opacity'),
                                setData(array('zui' => 'ProgressCircle', 'percent' => $execution->progress, 'size' => 112, 'text' => false, 'circle-width' => 0.06)),
                                div
                                (
                                    setClass('center absolute inset-0 num gap-1'),
                                    div(span(setClass('text-2xl font-bold'), $execution->progress), '%'),
                                    div
                                    (
                                        span
                                        (
                                            setClass('text-sm text-gray'),
                                            $lang->block->executionstatistic->progress,
                                            icon
                                            (
                                                setClass('opacity-50 ml-0.5 text-sm cursor-pointer'),
                                                toggle::popover
                                                (
                                                    array
                                                    (
                                                        'content'   => array('html' => $lang->block->tooltips['executionProgress']),
                                                        'placement' => 'bottom',
                                                        'width'     => 400,
                                                        'closeBtn'  => false,
                                                        'className' => 'leading-5'
                                                    )
                                                ),
                                                'help'
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        !$longBlock ? $burn : null
                    ),
                    cell
                    (
                        setClass('flex justify-evenly gap-1'),
                        cell
                        (
                            setClass('flex-1 text-center'),
                            div
                            (
                                span(setClass('text-lg num font-bold'), !empty($execution->totalEstimate) ? $execution->totalEstimate : 0),
                                span(setClass('text-gray'), 'h')
                            ),
                            div
                            (
                                span
                                (
                                    setClass('text-sm text-gray'),
                                    $lang->block->executionstatistic->totalEstimate
                                )
                            )
                        ),
                        cell
                        (
                            setClass('flex-1 text-center'),
                            div
                            (
                                span(setClass('text-lg num font-bold'), !empty($execution->totalConsumed) ? $execution->totalConsumed : 0),
                                span(setClass('text-gray'), 'h')
                            ),
                            div
                            (
                                span
                                (
                                    setClass('text-sm text-gray'),
                                    $lang->block->executionstatistic->totalConsumed
                                )
                            )
                        ),
                        cell
                        (
                            setClass('flex-1 text-center'),
                            div
                            (
                                span(setClass('text-lg num font-bold'), !empty($execution->totalLeft) ? $execution->totalLeft : 0),
                                span(setClass('text-gray'), 'h')
                            ),
                            div
                            (
                                span
                                (
                                    setClass('text-sm text-gray'),
                                    $lang->block->executionstatistic->totalLeft
                                )
                            )
                        )
                    )
                ),
                cell
                (
                    $longBlock ? set('width', '60%') : null,
                    setClass('px-4 pb-4' . ($longBlock ? ' pt-6' : '')),
                    $longBlock ? $burn : null,
                    cell
                    (
                        setClass('pt-6'),
                        row
                        (
                            div(setClass('w-12 flex-none'), strong($lang->block->executionstatistic->task)),
                            row
                            (
                                setClass('flex-auto'),
                                cell
                                (
                                    setClass('w-1/3'),
                                    span(setClass('text-sm text-gray'), $lang->block->executionstatistic->totalTask),
                                    strong(setClass('num ml-2'), $execution->totalTask)
                                ),
                                cell
                                (
                                    setClass('w-1/3'),
                                    span(setClass('text-sm text-gray'), $lang->block->executionstatistic->undoneTask),
                                    strong(setClass('num ml-2'), $execution->undoneTask)
                                ),
                                cell
                                (
                                    setClass('w-1/3'),
                                    span(setClass('text-sm text-gray'), $lang->block->executionstatistic->yesterdayDoneTask),
                                    strong(setClass('num ml-2'), $execution->yesterdayDoneTask)
                                )
                            )
                        ),
                        row
                        (
                            setClass('pt-3'),
                            div(setClass('w-12 flex-none'), strong($lang->block->executionstatistic->story)),
                            row
                            (
                                setClass('flex-auto'),
                                cell
                                (
                                    setClass('w-1/3'),
                                    span(setClass('text-sm text-gray'), $lang->block->executionstatistic->doneStory),
                                    strong(setClass('num ml-2'), $execution->doneStory)
                                ),
                                cell
                                (
                                    setClass('w-1/3'),
                                    span(setClass('text-sm text-gray'), $lang->block->executionstatistic->totalStory),
                                    strong(setClass('num ml-2'), $execution->totalStory)
                                )
                            )
                        )
                    )
                )
            )
        )
    )
);
