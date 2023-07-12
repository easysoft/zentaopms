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

$blockID = $block->module . '-' . $block->code . '-' . $block->id;

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
        $execution->addYesterday      = rand(0, 100);
        $execution->addToday          = rand(0, 100);
        $execution->resolvedYesterday = rand(0, 100);
        $execution->resolvedToday     = rand(0, 100);
        $execution->closedYesterday   = rand(0, 100);
        $execution->closedToday       = rand(0, 100);
        $execution->progress          = rand(0, 100);
        $execution->totalEstimate     = rand(0, 100);
        $execution->totalConsumed     = rand(0, 100);
        $execution->totalLeft         = rand(0, 100);

        $waitTesttasks = array();
        if(!empty($execution->waitTesttasks))
        {
            foreach($execution->waitTesttasks as $waitTesttask)
            {
                $waitTesttasks[] = div(set('class', 'py-1'), common::hasPriv('testtask', 'cases') ? a(set('href', createLink('testtask', 'cases', "taskID={$waitTesttask->id}")), $waitTesttask->name) : span($waitTesttask->name));
                if(count($waitTesttasks) >= 2) break;
            }
        }

        $doingTesttasks = array();
        if(!empty($execution->doingTesttasks))
        {
            foreach($execution->doingTesttasks as $doingTesttask)
            {
                $doingTesttasks[] = div(set('class', 'py-1'), common::hasPriv('testtask', 'cases') ? a(set('href', createLink('testtask', 'cases', "taskID={$doingTesttask->id}")), $doingTesttask->name) : span($doingTesttask->name));
                if(count($doingTesttasks) >= 2) break;
            }
        }

        $progressMax = max($execution->addYesterday, $execution->addToday, $execution->resolvedYesterday, $execution->resolvedToday, $execution->closedYesterday, $execution->closedToday);
        $progressBlcok = array();
        foreach(array(array('addYesterday', 'addToday'), array('resolvedYesterday', 'resolvedToday'), array('closedYesterday', 'closedToday')) as $group)
        {
            $progress = array();
            $progressLabel = array();
            foreach($group as $key => $field)
            {
                $progressLabel[] = div(set('class', 'py-1 ' . ($key === 0 ? '' : 'text-gray')), span($lang->block->qastatistic->{$field}), span(set('class', 'ml-1'), $execution->{$field}));
                $progress[] = div
                (
                    set('class', $key === 0 ? 'pt-2' : 'pt-5'),
                    div
                    (
                        set('class', 'progress h-2'),
                        div
                        (
                            set('class', 'progress-bar'),
                            set('role', 'progressbar'),
                            setStyle(array('width' => ($execution->{$field} / $progressMax * 100) . '%', 'background' => $key === 0 ? 'var(--color-secondary-200)' : 'var(--color-primary-300)')),
                        )
                    )
                );
            }
            $progressBlcok[] = div
            (
                set('class', 'flex py-1 pr-4 ' . ($waitTesttasks || $doingTesttasks ? 'border-r' : '')),
                cell($progressLabel),
                cell
                (
                    set('class', 'flex-1 px-3'),
                    $progress
                )
            );
        }

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
                            set('class', $longBlock ? 'p-4' : 'px-4'),
                            div
                            (
                                set('class', 'chart pie-chart ' . ($longBlock ? 'py-6' : 'py-1')),
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
                                    div(span(set::class('text-sm text-gray'), $lang->block->executionstatistic->progress, icon('help', set('data-toggle', 'tooltip'), set('id', 'storyTip'), set('class', 'text-light'))))
                                )
                            ),
                            div
                            (
                                set('class', 'flex h-full story-num w-44'),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(!empty($execution->totalEstimate) ? $execution->totalEstimate : 0, 'h')
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->executionstatistic->totalEstimate
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(!empty($execution->totalConsumed) ? $execution->totalConsumed : 0, 'h')
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->executionstatistic->totalConsumed
                                        )
                                    )
                                ),
                                cell
                                (
                                    set('class', 'flex-1 text-center'),
                                    div
                                    (
                                        span(!empty($execution->totalLeft) ? $execution->totalLeft : 0, 'h')
                                    ),
                                    div
                                    (
                                        span
                                        (
                                            set('class', 'text-sm text-gray'),
                                            $lang->block->executionstatistic->totalLeft
                                        )
                                    )
                                )
                            )
                        ),
                        cell
                        (
                            $longBlock ? set('width', '60%') : null,
                            set('class', 'py-4 ' . (!$longBlock ? 'px-4 flex' : '')),
                            cell
                            (
                                set('class', 'flex-1'),
                                div
                                (
                                    $longBlock ? set('class', 'pb-2') : null,
                                    $lang->block->qastatistic->bugStatistics
                                ),
                                $progressBlcok
                            ),
                            !$longBlock && ($doingTesttasks || $waitTesttasks) ? cell
                            (
                                set('width', '50%'),
                                set('class', 'px-4'),
                                div(span($lang->block->qastatistic->latestTesttask)),
                                $doingTesttasks ? div
                                (
                                    set('class', 'py-2'),
                                    div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['doing']),
                                    $doingTesttasks
                                ) : null,
                                $waitTesttasks ? div
                                (
                                    set('class', 'py-2'),
                                    div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['wait']),
                                    $waitTesttasks
                                ) : null
                            ) : null
                        )
                    )
                ),
                $longBlock && ($doingTesttasks || $waitTesttasks) ? cell
                (
                    set('width', '30%'),
                    set('class', 'py-2 px-6'),
                    div
                    (
                        set('class', 'py-2'),
                        span($lang->block->qastatistic->latestTesttask)
                    ),
                    $doingTesttasks ? div
                    (
                        set('class', 'py-2'),
                        div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['doing']),
                        $doingTesttasks
                    ) : null,
                    $waitTesttasks ? div
                    (
                        set('class', 'py-2'),
                        div(set('class', 'text-sm pb-2'), $lang->testtask->statusList['wait']),
                        $waitTesttasks
                    ) : null
                ) : null
            )
        );
    }
    return $tabItems;
};

$url = createLink('block', 'printBlock', "blockID={$block->id}");
$projectItems = array();
$projectItems[] = array('text' => $lang->block->executionstatistic->allProject, 'data-on' => 'click', 'data-call' => "loadPage('{$url}', '#executionstatistic-block-{$block->id}')");
foreach($projects as $projectID => $projectName)
{
    $url = createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("project={$projectID}"));
    $projectItems[] = array('text' => $projectName, 'data-on' => 'click', 'data-call' => "loadPage('{$url}', '#executionstatistic-block-{$block->id}')");
}

$blockNavCode = 'nav-' . uniqid();

panel
(
    set('id', "executionstatistic-block-{$block->id}"),
    on::click('.nav-prev,.nav-next', 'switchNav'),
    set('class', 'executionstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set('headingClass', 'border-b'),
    set::title($block->title),
    to::headingActions
    (
        dropdown
        (
            a
            (
                setClass('text-gray ml-4'),
                isset($projects[$currentProjectID]) ? $projects[$currentProjectID] : $lang->block->executionstatistic->allProject,
                span(setClass('caret align-middle ml-1'))
            ),
            set::placement('bottom-end'),
            set::menu(array('style' => array('minWidth' => 70, 'width' => 70))),
            set::items($projectItems)
        ),
        a
        (
            set('class', 'text-gray'),
            set('href', createLink('execution', 'all', 'status=' . $block->params->type)),
            $lang->more,
            icon('caret-right')
        )
    ),
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
