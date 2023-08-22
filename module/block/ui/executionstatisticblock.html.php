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

/**
 * 获取区块左侧的执行列表.
 * Get execution tabs on the left side.
 *
 * @param  array    $executions
 * @param  string   $blockNavCode
 * @param  bool     $longBlock
 * @access public
 * @return array
 */
$getExecutionTabs = function(array $executions, string $blockNavCode, bool $longBlock): array
{
    $navTabs  = array();
    $selected = key($executions);
    $navTabs[] = li
    (
        set('class', 'nav-item overflow-hidden nav-prev rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-left'))
    );
    foreach($executions as $execution)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item nav-switch w-full'),
            a
            (
                set('class', 'ellipsis text-dark title ' . ($longBlock && $execution->id == $selected ? ' active' : '')),
                $longBlock ? set('data-toggle', 'tab') : null,
                set('data-name', "tab3{$blockNavCode}Content{$execution->id}"),
                set('href', $longBlock ? "#tab3{$blockNavCode}Content{$execution->id}" : helper::createLink('execution', 'task', "executionID={$execution->id}")),
                $execution->name

            ),
            !$longBlock ? a
            (
                set('class', 'hidden' . ($execution->id == $selected ? ' active' : '')),
                set('data-toggle', 'tab'),
                set('data-name', "tab3{$blockNavCode}Content{$execution->id}"),
                set('href', "#tab3{$blockNavCode}Content{$execution->id}"),
            ) : null,
            a
            (
                set('class', 'link flex-1 text-right hidden'),
                set('href', helper::createLink('execution', 'task', "executionID={$execution->id}")),
                icon
                (
                    set('class', 'rotate-90 text-primary'),
                    setStyle(array('--tw-rotate' => '270deg')),
                    'import'
                )
            )
        );
    }
    $navTabs[] = li
    (
        set('class', 'nav-item overflow-hidden nav-next rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-right'))
    );
    return $navTabs;
};

/**
 * 获取区块右侧显示的执行信息.
 * Get execution statistical information.
 *
 * @param  object   $executions
 * @param  string   $blockNavID
 * @param  bool     $longBlock
 * @access public
 * @return array
 */
$getExecutionInfo = function(array $executions, string $blockNavID, bool $longBlock): array
{
    global $lang;

    $selected = key($executions);
    $tabItems = array();
    foreach($executions as $execution)
    {
        $burn = cell
        (
            set('class', 'flex-1'),
            div
            (
                $longBlock ? set('class', 'pb-2') : null,
                span(set('class', 'font-bold'), $lang->block->executionstatistic->burn),
            ),
            div
            (
                set('class', 'py-2 chart line-chart'),
                echarts
                (
                    set::color(array('#2B80FF', '#17CE97')),
                    set::grid(array('left' => 0, 'bottom' => 0, 'top' => 0, 'right' => 0)),
                    set::legend(array('show' => false)),
                    set::xAxis
                    (
                        array
                        (
                            'show' => false,
                            'type' => 'category',
                            'data' => array(1,2,3,4,5,6),
                            'boundaryGap' => false,
                        )
                    ),
                    set::yAxis(array('show' => false)),
                    set::series
                    (
                        array
                        (
                            array
                            (
                                'type' => 'line',
                                'data' => array(80,60,40,20,0),
                                'symbolSize' => 0,
                                'itemStyle' => array
                                (
                                    'normal' => array
                                    (
                                        'color' => '#D8D8D8',
                                        'lineStyle' => array
                                        (
                                            'width' => 2,
                                            'color' => '#F1F1F1',
                                        )
                                    ),

                                ),

                            ),
                            array
                            (
                                'data' => array(74,55,75,55),
                                'type' => 'line',
                                'symbolSize' => 0,
                                'areaStyle' => array
                                (
                                    'color' => array
                                    (
                                        'type' => 'linear',
                                        'x' => '0',
                                        'y' => '0',
                                        'x2' => '0',
                                        'y2' => '1',
                                        'colorStops' => array(array('offset' => 0, 'color' => '#DDECFE'), array('offset' => 1, 'color' => '#FFF')),
                                        'global' => false
                                    )
                                )
                            )
                        )
                    )
                )->size('100%', $longBlock ? 64 : 80),
            )
        );


        $tabItems[] = div
        (
            set('class', 'tab-pane h-full' . ($execution->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$execution->id}"),
            div
            (
                set('class', 'flex h-full ' . ($longBlock ? '' : 'col')),
                cell
                (
                    set('class', 'flex-1'),
                    $longBlock ? set('width', '70%') : null,
                    div
                    (
                        set('class', 'flex h-full ' . ($longBlock ? '' : 'col')),
                        cell
                        (
                            $longBlock ? set('width', '40%') : null,
                            set('class', 'p-4'),
                            div
                            (
                                set('class', 'flex'),
                                cell
                                (
                                    !$longBlock ? set('width', '50%') : null,
                                    set('class', 'chart pie-chart py-6'),
                                    echarts
                                    (
                                        set::color(array('#2B80FF', '#E3E4E9')),
                                        set::series
                                        (
                                            array
                                            (
                                                array
                                                (
                                                    'type'      => 'pie',
                                                    'radius'    => array('80%', '90%'),
                                                    'itemStyle' => array('borderRadius' => '40'),
                                                    'label'     => array('show' => false),
                                                    'data'      => array($execution->progress, 100 - $execution->progress)
                                                )
                                            )
                                        )
                                    )->size('100%', 120),
                                    div
                                    (
                                        set::class('pie-chart-title text-center'),
                                        div(span(set::class('text-2xl font-bold'), $execution->progress . '%')),
                                        div
                                        (
                                            span
                                            (
                                                setClass('text-sm text-gray'),
                                                $lang->block->executionstatistic->progress,
                                                icon
                                                (
                                                    'help',
                                                    toggle::tooltip(array('title' => '提示文本')),
                                                    setClass('text-light')
                                                )
                                            )
                                        )
                                    )
                                ),
                                !$longBlock ? cell
                                (
                                    set('width', '50%'),
                                    set('class', 'py-6'),
                                    $burn
                                ) : null
                            ),
                            div
                            (
                                set('class', 'flex justify-evenly px-4'),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(set('class', 'text-lg'), !empty($execution->totalEstimate) ? $execution->totalEstimate : 0),
                                        span(' h')
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm'),
                                            $lang->block->executionstatistic->totalEstimate
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(set('class', 'text-lg'), !empty($execution->totalConsumed) ? $execution->totalConsumed : 0),
                                        span(' h')
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm'),
                                            $lang->block->executionstatistic->totalConsumed
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(set('class', 'text-lg'), !empty($execution->totalLeft) ? $execution->totalLeft : 0),
                                        span(' h')
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm'),
                                            $lang->block->executionstatistic->totalLeft
                                        )
                                    )
                                )
                            )
                        ),
                        cell
                        (
                            $longBlock ? set('width', '60%') : null,
                            set('class', $longBlock ? 'py-4' : 'p-4'),
                            $longBlock ? $burn : null,
                            cell
                            (
                                set('class', 'flex py-2'),
                                cell
                                (
                                    set('width', '50%'),
                                    set('class', 'border-r pr-4'),
                                    div
                                    (
                                        div(set('class', 'pb-4'), span(set('class', 'font-bold'), $lang->block->executionstatistic->story)),
                                        div
                                        (
                                            set('class', 'progress h-2'),
                                            div
                                            (
                                                set('class', 'progress-bar'),
                                                set('role', 'progressbar'),
                                                setStyle(array('width' => '50%', 'background' => 'var(--color-primary-300)')),
                                            )
                                        ),
                                        div
                                        (
                                            set('class', 'flex pt-4'),
                                            cell
                                            (
                                                set('width', '50%'),
                                                set('class', 'text-center'),
                                                div(span($execution->doneStory)),
                                                div(set('class', 'text-sm text-gray'), span($lang->block->executionstatistic->doneStory)),
                                            ),
                                            cell
                                            (
                                                set('width', '50%'),
                                                set('class', 'text-center'),
                                                div(span($execution->totalStory)),
                                                div(set('class', 'text-sm text-gray'), span($lang->block->executionstatistic->totalStory)),
                                            )
                                        ),
                                    )
                                ),
                                cell
                                (
                                    set('width', '50%'),
                                    set('class', 'px-4 flex h-28'),
                                    cell
                                    (
                                        set('class', 'flex-1'),
                                        span(set('class', 'font-bold'), $lang->block->executionstatistic->task),
                                    ),
                                    cell
                                    (
                                        set('class', 'pr-2 flex col py-2'),
                                        cell(set('class', 'flex flex-1 items-center justify-end'), span(set('class', 'text-sm text-gray'), $lang->block->executionstatistic->totalTask)),
                                        cell(set('class', 'flex flex-1 items-center justify-end'), span(set('class', 'text-sm text-gray'), $lang->block->executionstatistic->undoneTask)),
                                        cell(set('class', 'flex flex-1 items-center justify-end'), span(set('class', 'text-sm text-gray'), $lang->block->executionstatistic->yesterdayDoneTask))
                                    ),
                                    cell
                                    (
                                        set('class', 'pl-2 flex col py-2'),
                                        cell(set('class', 'flex flex-1 items-center'), span(set('class', 'text-lg'), $execution->totalTask)),
                                        cell(set('class', 'flex flex-1 items-center'), span(set('class', 'text-lg'), $execution->undoneTask)),
                                        cell(set('class', 'flex flex-1 items-center'), span(set('class', 'text-lg'), $execution->yesterdayDoneTask))
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
    }
    return $tabItems;
};

$projectItems = array();
$projectItems[] = array('text' => $lang->block->executionstatistic->allProject, 'url' => createLink('block', 'printBlock', "blockID={$block->id}"), 'data-load' => 'target', 'data-selector' => "#executionstatistic-block-{$block->id}", 'data-partial' => true);
foreach($projects as $projectID => $projectName)
{
    $url = createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("project={$projectID}"));
    $projectItems[] = array('text' => $projectName, 'url' => $url, 'data-load' => 'target', 'data-selector' => "#executionstatistic-block-{$block->id}", 'data-partial' => true);
}

$blockNavCode = 'nav-' . uniqid();

panel
(
    set('id', "executionstatistic-block-{$block->id}"),
    on::click('.nav-prev,.nav-next', 'switchNav'),
    set('class', 'executionstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set('headingClass', 'border-b'),
    to::heading
    (
        div
        (
            set('class', 'panel-title'),
            span(span($block->title)),
            dropdown
            (
                a
                (
                    setClass('text-gray ml-4'),
                    isset($projects[$currentProjectID]) ? $projects[$currentProjectID] : $lang->block->executionstatistic->allProject,
                    span(setClass('caret align-middle ml-1'))
                ),
                set::items($projectItems)
            ),
        )
    ),
    $block->params->type != 'involved' ? to::headingActions
    (
        a
        (
            set('class', 'text-gray'),
            set('href', createLink('execution', 'all', 'status=' . $block->params->type)),
            $lang->more,
            icon('caret-right')
        )
    ) : null,
    div
    (
        set('class', "flex h-full overflow-hidden " . ($longBlock ? '' : 'col')),
        cell
        (
            $longBlock ? set('width', '22%') : null,
            set('class', $longBlock ? 'bg-secondary-pale overflow-y-auto overflow-x-hidden' : ''),
            ul
            (
                set('class', 'nav nav-tabs ' .  ($longBlock ? 'nav-stacked' : 'pt-4 px-4')),
                $getExecutionTabs($executions, $blockNavCode, $longBlock)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '78%'),
            $getExecutionInfo($executions, $blockNavCode, $longBlock)
        )
    )
);

render();
