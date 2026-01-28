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
$execution->type              = '';

$items = array();
foreach($executions as $executionItem)
{
    $params  = helper::safe64Encode("module={$block->module}&project={$currentProjectID}&active={$executionItem->id}");
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
$projectItems[] = array('value' => '0', 'text' => $lang->block->executionstatistic->allProject, 'data-url' => createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("module={$block->module}")), 'data-on' => 'click', 'data-do' => "loadBlock('$block->id', options.url)");
foreach($projects as $projectID => $projectName)
{
    $url = createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("module={$block->module}&project={$projectID}"));
    $projectItems[] = array('value' => $projectID, 'text' => $projectName, 'data-url' => $url, 'data-on' => 'click', 'data-do' => "loadBlock('$block->id', options.url)");
}

if($execution->type == 'kanban')
{
    $index       = 0;
    $chartColors = array('#33B4DB', '#7ECF69', '#FFC73A', '#FF5A61', '#50C8D0', '#AF5AFF', '#4EA3FF', '#FF8C5A', '#6C73FF');
    $chartSeries = array();
    if(!empty($chartData['line']))
    {
        foreach($chartData['line'] as $label => $set)
        {
            $chartSeries[] = array(
                'name'      => $label,
                'type'      => 'line',
                'stack'     => 'Total',
                'data'      => array_values($set),
                'color'     => $chartColors[$index],
                'areaStyle' => array('color' => $chartColors[$index], 'opacity' => 0.2),
                'itemStyle' => array('normal' => array('lineStyle' => array('width' => 1))),
                'emphasis'  => array('focus' => 'series')
            );

            $index ++;
        }
    }
    /* 任务计流图图。CFD chart. */
    $chart = div
        (
            div(setClass('font-bold mb-2'), $lang->block->executionstatistic->cfd),
            echarts
            (
                set::series($chartSeries),
                set::width('100%'),
                set::height(200),
                set::tooltip(array(
                    'trigger'     => 'axis',
                    'axisPointer' => array('type' => 'cross', 'label' => array('backgroundColor' => '#6a7985')),
                    'textStyle'   => array('fontWeight' => 100),
                    'formatter'   => "RAWJS<function(rowDatas){return window.randTipInfo(rowDatas);}>RAWJS"
                )),
                set::legend(array(
                    'data' => !empty($chartData['line']) ? array_keys(array_reverse($chartData['line'])) : null
                )),
                set::grid(array(
                    'top'          => !empty($chartData['labels']) ? '80px' : '40px',
                    'left'         => !empty($chartData['labels']) ? '2px' : '15px',
                    'right'        => '2px',
                    'bottom'       => '2px',
                    'containLabel' => true
                )),
                set::xAxis(array(array(
                    'type' => 'category',
                    'boundaryGap' => false,
                    'data' => !empty($chartData['labels']) ? $chartData['labels'] : null,
                    'name' => $lang->execution->burnXUnit,
                    'axisLine' => array('show' => true, 'lineStyle' =>array('color' => '#999', 'width' =>1))
                ))),
                set::yAxis(array(array(
                    'type'          => 'value',
                    'name'          => $lang->execution->count,
                    'minInterval'   => 1,
                    'nameTextStyle' => array('fontWeight' => 'normal'),
                    'axisPointer'   => array('label' => array('show' => true, 'precision' => 0)),
                    'axisLine'      => array('show' => true, 'lineStyle' => array('color' => '#999', 'width' => 1))
                )))
            )
        );
    $taskStoryInfo = null;
}
else
{
    /* 燃尽图。Burn chart. */
    $chart = div
        (
            div(setClass('font-bold mb-2'), $lang->block->executionstatistic->burn),
            echarts
            (
                set::color(array('#2B80FF', '#D2D6E5')),
                set::width('100%'),
                set::height(140),
                set::grid(array('left' => '2px', 'top' => '30px', 'right' => '10px', 'bottom' => '0',  'containLabel' => true)),
                set::legend(array('show' => true, 'right' => '0')),
                set::xAxis(array('type' => 'category', 'data' => $chartData['labels'], 'boundaryGap' => false, 'splitLine' => array('show' => false), 'axisTick' => array('alignWithLabel' => true, 'interval' => '0'), 'axisLabel' => array('rotate' => 45))),
                set::yAxis(array('type' => 'value', 'name' => 'h', 'minInterval' => zget($chartData['baseLine'], 0), 'splitLine' => array('show' => false), 'axisLine' => array('show' => true, 'color' => '#DDD'))),
                set::series
                (
                    array
                    (
                        array
                        (
                            'type'       => 'line',
                            'name'       => $lang->execution->charts->burn->graph->actuality,
                            'data'       => $chartData['burnLine'],
                            'emphasis'   => array('label' => array('show' => true)),
                            'symbolSize' => 8,
                            'symbol'     => 'circle'
                        ),
                        array
                        (
                            'type'       => 'line',
                            'name'       => $lang->execution->charts->burn->graph->reference,
                            'data'       => $chartData['baseLine'],
                            'emphasis'   => array('label' => array('show' => true)),
                            'symbolSize' => 8,
                            'symbol'     => 'circle'
                        ),
                        array
                        (
                            'data'       => empty($chartData['delayLine']) ? array() : $chartData['delayLine'],
                            'type'       => 'line',
                            'name'       => $lang->execution->charts->burn->graph->delay,
                            'symbolSize' => 8,
                            'symbol'     => 'circle',
                            'itemStyle'  => array
                            (
                                'normal' => array
                                (
                                    'color' => '#F00',
                                    'lineStyle' => array
                                    (
                                        'color' => '#F00'
                                    )
                                ),
                                'emphasis' => array
                                (
                                    'color' => '#fff',
                                    'borderColor' => '#F00',
                                    'borderWidth' => 2
                                )
                            ),
                        )
                    )
                )
            )
        );

    /* 任务故事信息。Task story info. */
    $taskStoryInfo = col
        (
            setClass('gap-2'),
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
                div(setClass('w-12 flex-none'), strong($lang->block->executionstatistic->story)),
                row
                (
                    setClass('flex-auto'),
                    cell
                    (
                        setClass('w-1/3'),
                        span(setClass('text-sm text-gray'), $lang->block->executionstatistic->totalStory),
                        strong(setClass('num ml-2'), $execution->totalStory)
                    ),
                    cell
                    (
                        setClass('w-1/3'),
                        span(setClass('text-sm text-gray'), $lang->block->executionstatistic->doneStory),
                        strong(setClass('num ml-2'), $execution->doneStory)
                    )
                )
            )
        );
}

/* 进度环。Progress circle. */
$progressCircle = div
(
    setClass('w-full'),
    row
    (
        setClass('font-bold items-center gap-1 mb-2'),
        $lang->block->executionstatistic->progress,
        icon
        (
            'help',
            setClass('opacity-50 text-sm cursor-pointer'),
            toggle::popover(array
            (
                'content'   => array('html' => $lang->block->tooltips['executionProgress']),
                'placement' => 'bottom',
                'width'     => 400,
                'trigger'   => 'hover',
                'closeBtn'  => false,
                'className' => 'leading-5'
            ))
        )
    ),
    div
    (
        setClass('w-full center'),
        progressCircle
        (
            setClass('relative w-28 h-28 hide-before-init opacity-0 transition-opacity'),
            set::percent($execution->progress),
            set::size(112),
            set::text(false),
            set::circleWidth(0.06),
            div(span(setClass('text-2xl font-bold'), $execution->progress), '%')
        )
    )
);

/* 工时信息。 Hours info. */
$hoursInfo = row
(
    setClass('justify-evenly w-full py-1'),
    cell
    (
        setClass('flex-1 text-center'),
        div
        (
            span(setClass('text-lg num font-bold'), !empty($execution->totalEstimate) ? $execution->totalEstimate : 0),
            span(setClass('text-gray'), ' h')
        ),
        div
        (
            setClass('text-sm text-gray'),
            $lang->block->executionstatistic->totalEstimate
        )
    ),
    cell
    (
        setClass('flex-1 text-center'),
        div
        (
            span(setClass('text-lg num font-bold'), !empty($execution->totalConsumed) ? $execution->totalConsumed : 0),
            span(setClass('text-gray'), ' h')
        ),
        div
        (
            setClass('text-sm text-gray'),
            $lang->block->executionstatistic->totalConsumed
        )
    ),
    cell
    (
        setClass('flex-1 text-center'),
        div
        (
            span(setClass('text-lg num font-bold'), !empty($execution->totalLeft) ? $execution->totalLeft : 0),
            span(setClass('text-gray'), ' h')
        ),
        div
        (
            setClass('text-sm text-gray'),
            $lang->block->executionstatistic->totalLeft
        )
    )
);

$blockView = null;
if($longBlock)
{
    $blockView = row
    (
        setClass('gap-4 h-full items-center px-4'),
        col
        (
            setClass('gap-6 w-2/5 flex-none items-center pr-2'),
            $progressCircle,
            $hoursInfo
        ),
        col
        (
            setClass('gap-0 flex-auto'),
            $chart,
            $taskStoryInfo
        )
    );
}
else
{
    $blockView = col
    (
        setClass('gap-8 px-6 pt-10'),
        row
        (
            setClass('gap-2 items-center w-full'),
            cell
            (
                setClass('gap-2 w-2/5 flex-none items-center'),
                $progressCircle,
            ),
            cell
            (
                setClass('flex-auto min-w-0'),
                $chart
            )
        ),
        $hoursInfo,
        $taskStoryInfo
    );
}

statisticBlock
(
    to::titleSuffix
    (
        icon
        (
            setClass('text-light text-sm cursor-pointer'),
            toggle::tooltip
            (
                array
                (
                    'title'     => sprintf($lang->block->tooltips['metricTime'], $metricTime),
                    'placement' => 'bottom',
                    'type'      => 'white',
                    'className' => 'text-dark border border-light leading-5'
                )
            ),
            'help'
        ),
        picker
        (
            setClass('font-normal gray-400-outline ml-3 text-base circle filter-project-pricker'),
            set::width('120px'),
            set::placeholder($lang->block->filterProject),
            set::name('project'),
            set::items($projectItems),
            set::value(isset($projects[$currentProjectID]) ? $currentProjectID : 0)
        )
    ),
    set::block($block),
    set::active($active),
    set::moreLink(hasPriv('execution', 'all') && $config->vision != 'lite' ? createLink('execution', 'all', 'status=' . zget($block->params, 'type', '')) : ''),
    set::items($items),
    $blockView,
);
