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
    set::className((!$longBlock ? ' py-6 w-1/2' : '')),
    cell
    (
        set::className('flex-1 w-full'),
        div
        (
            $longBlock ? set::className('pb-2') : null,
            span(set::className('font-bold'), $lang->block->executionstatistic->burn)
        ),
        div
        (
            set::className('py-2 chart line-chart'),
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
                setClass('ghost text-gray font-normal'),
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
        set::className('flex h-full ' . ($longBlock ? '' : 'col')),
        cell
        (
            set::className('flex-1'),
            $longBlock ? set('width', '70%') : null,
            div
            (
                set::className('flex h-full ' . ($longBlock ? '' : 'col')),
                cell
                (
                    $longBlock ? set('width', '40%') : null,
                    set::className('p-4'),
                    div
                    (
                        set::className('flex align-center justify-around' . ($longBlock ? ' py-4' : '')),
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
                                                setClass('text-light ml-0.5 text-sm'),
                                                toggle::tooltip
                                                (
                                                    array
                                                    (
                                                        'content'   => array('html' => $lang->block->tooltips['executionProgress']),
                                                        'placement' => 'bottom',
                                                        'type'      => 'white',
                                                        'className' => 'text-dark border border-light leading-5'
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
                        set::className('flex justify-evenly px-4'),
                        cell
                        (
                            set::className('flex-1 text-center'),
                            div
                            (
                                span(set::className('text-lg'), !empty($execution->totalEstimate) ? $execution->totalEstimate : 0),
                                span(' h')
                            ),
                            div
                            (
                                span
                                (
                                    set::className('text-sm'),
                                    $lang->block->executionstatistic->totalEstimate
                                )
                            )
                        ),
                        cell
                        (
                            set::className('flex-1 text-center'),
                            div
                            (
                                span(set::className('text-lg'), !empty($execution->totalConsumed) ? $execution->totalConsumed : 0),
                                span(' h')
                            ),
                            div
                            (
                                span
                                (
                                    set::className('text-sm'),
                                    $lang->block->executionstatistic->totalConsumed
                                )
                            )
                        ),
                        cell
                        (
                            set::className('flex-1 text-center'),
                            div
                            (
                                span(set::className('text-lg'), !empty($execution->totalLeft) ? $execution->totalLeft : 0),
                                span(' h')
                            ),
                            div
                            (
                                span
                                (
                                    set::className('text-sm'),
                                    $lang->block->executionstatistic->totalLeft
                                )
                            )
                        )
                    )
                ),
                cell
                (
                    $longBlock ? set('width', '60%') : null,
                    set::className('px-4 pb-4' . ($longBlock ? ' pt-4' : '')),
                    $longBlock ? $burn : null,
                    cell
                    (
                        set::className('flex py-2'),
                        cell
                        (
                            set('width', '50%'),
                            set::className('border-r pr-4'),
                            div
                            (
                                div(set::className('pb-4'), span(set::className('font-bold'), $lang->block->executionstatistic->story)),
                                div
                                (
                                    set::className('progress h-2'),
                                    div
                                    (
                                        set::className('progress-bar'),
                                        set('role', 'progressbar'),
                                        setStyle(array('width' => '50%', 'background' => 'var(--color-primary-300)'))
                                    )
                                ),
                                div
                                (
                                    set::className('flex pt-4'),
                                    cell
                                    (
                                        set('width', '50%'),
                                        set::className('text-center'),
                                        div(span($execution->doneStory)),
                                        div(set::className('text-sm text-gray'), span($lang->block->executionstatistic->doneStory))
                                    ),
                                    cell
                                    (
                                        set('width', '50%'),
                                        set::className('text-center'),
                                        div(span($execution->totalStory)),
                                        div(set::className('text-sm text-gray'), span($lang->block->executionstatistic->totalStory))
                                    )
                                )
                            )
                        ),
                        cell
                        (
                            set('width', '50%'),
                            set::className('px-4 flex h-28'),
                            cell
                            (
                                set::className('flex-1'),
                                span(set::className('font-bold'), $lang->block->executionstatistic->task)
                            ),
                            cell
                            (
                                set::className('pr-2 flex col py-2'),
                                cell(set::className('flex flex-1 items-center justify-end'), span(set::className('text-sm text-gray'), $lang->block->executionstatistic->totalTask)),
                                cell(set::className('flex flex-1 items-center justify-end'), span(set::className('text-sm text-gray'), $lang->block->executionstatistic->undoneTask)),
                                cell(set::className('flex flex-1 items-center justify-end'), span(set::className('text-sm text-gray'), $lang->block->executionstatistic->yesterdayDoneTask))
                            ),
                            cell
                            (
                                set::className('pl-2 flex col py-2'),
                                cell(set::className('flex flex-1 items-center'), span(set::className('text-lg'), $execution->totalTask)),
                                cell(set::className('flex flex-1 items-center'), span(set::className('text-lg'), $execution->undoneTask)),
                                cell(set::className('flex flex-1 items-center'), span(set::className('text-lg'), $execution->yesterdayDoneTask))
                            )
                        )
                    )
                )
            )
        )
    )
);
