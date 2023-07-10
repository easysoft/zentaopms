<?php
declare(strict_types=1);
/**
* The project statistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$blockNavCode   = 'nav-' . uniqid();
$navTabs        = array();
$selected       = key($projects);
$statisticCells = array();
$preProjectID   = 0;
$nextProjectID  = 0;
$projectIdList  = array_keys($projects);
foreach($projects as $project)
{
    $remainingDays   = zget($project, 'remainingDays' , 0);
    $projectOverview = array();
    $projectOverview[] = cell
    (
        setClass('flex-1 text-left mr-6'),
        $project->status != 'closed' && $project->end != LONG_TIME ? span
        (
            setClass('text-gray'),
            $remainingDays >= 0 ? $lang->block->projectstatistic->leftDaysPre : $lang->block->projectstatistic->delayDaysPre,
            span
            (
                setClass('font-bold text-black px-1'),
                abs($remainingDays),
            ),
            $lang->block->projectstatistic->day
        ) : span
        (
            setClass('text-gray'),
            $project->status == 'closed' ? $lang->block->projectstatistic->projectClosed : $lang->block->projectstatistic->longTimeProject
        ),
    );
    $projectOverview[] = $config->edition != 'open' ? cell
    (
        setClass('flex-1 text-left' . (!$longBlock ? ' w-full' : '')),
        icon('bullhorn text-warning'),
        span
        (
            setClass('text-gray mr-5'),
            $lang->block->projectstatistic->existRisks,
            span
            (
                setClass('font-bold text-warning'),
                999
            )
        ),
        span
        (
            setClass('text-gray'),
            $lang->block->projectstatistic->existIssues,
            span
            (
                setClass('font-bold text-warning'),
                999
            )
        )
    ) : '';

    $width = $config->edition != 'open' ? '33%' : '50%';

    $lastestExecution = (!empty($project->executions) && $project->multiple) ? cell
    (
        setClass('flex-1 hidden-nowrap' . (!$longBlock ? ' text-left' : ' text-right')),
        $longBlock ? set::width($width) : '',
        span
        (
            setClass('text-gray'),
            $lang->block->projectstatistic->lastestExecution,
            hasPriv('execution', 'task') ? a
            (
                setClass('pl-2'),
                set::href(helper::createLink('execution', 'task', "executionID={$project->executions[0]->id}")),
                set('title', $project->executions[0]->name),
                $project->executions[0]->name,
            ) : span
            (
                setClass('pl-2'),
                $project->executions[0]->name,
            )
        )
    ) : null;

    $cells = array();
    if(in_array($project->model, array('scrum', 'kanban', 'agileplus')))
    {
        foreach($config->block->projectstatistic->items as $module => $items)
        {
            $cellItems = array();
            foreach($items as $item)
            {
                $field = $item['field'];
                $unit  = $item['unit'];
                $cellItems[] = item
                (
                    set::name($lang->block->projectstatistic->{$field} . ': '),
                    span
                    (
                        setClass('font-bold text-black mr-0.5'),
                        zget($project, $field, 0)
                    ),
                    span
                    (
                        setClass('text-gray'),
                        $lang->block->projectstatistic->{$unit}
                    )
                );
            }
            $cells[] = cell
            (
                setClass('flex-1 hidden-nowrap project-statistic-table scrum' . (($module != 'cost' && $longBlock) || ($module != 'task' && $module != 'cost' && !$longBlock) ? ' border-l pl-4 ' : ' ml-4') . (!$longBlock && $module != 'cost' && $module != 'story'? ' border-t' : '')),
                set::width($longBlock ? ($module == 'cost' ? 'calc(25% - 1rem)' : '25%') : 'calc(50% - 1rem)'),
                div
                (
                    setClass('pt-1'),
                    span
                    (
                        setClass('font-bold'),
                        $lang->block->projectstatistic->{$module}
                    ),
                ),
                tableData
                (
                    set::width('100%'),
                    $cellItems
                ),
            );
        }
    }
    else
    {
        $cells[] = cell
        (
            setClass('flex flex-wrap items-center progress-circle pt-2 mb-2'),
            set::width($longBlock ? '36%' : '100%'),
            div
            (
                setClass('flex justify-center w-full'),
                zui::progressCircle
                (
                    set('percent', $project->progress),
                    set('size', 112),
                    set('circleWidth', 6),
                    set('text', "{$project->progress}%"),
                    set('textY', 50),
                    set('textStyle', 'font-size: 30px;'),
                ),
            ),
            div
            (
                setClass('flex justify-center w-full h-0'),
                span
                (
                    setClass('text-gray text-md progress-text'),
                    $lang->block->projectstatistic->totalProgress,
                    icon
                    (
                        setClass('pl-0.5'),
                        set('data-toggle', 'tooltip'),
                        set('href', 'totalProgressTooltip'),
                        'help'
                    ),
                ),
                div
                (
                    setClass('tooltip z-50 shadow bg-white text-gray leading-6'),
                    set::id('totalProgressTooltip'),
                    $lang->block->projectstatistic->totalProgressTip
                ),
            )
        );
        $cells[] = cell
        (
            setClass('project-statistic-table pl-4' . ($longBlock ? ' border-l' : ' mt-4')),
            set::width($longBlock ? '32%' : '50%'),
            div
            (
                setClass('w-full'),
                span
                (
                    setClass('font-bold'),
                    $lang->project->progress
                ),
            ),
            h::table
            (
                setClass('table-data'),
                h::tbody
                (
                    h::tr
                    (
                        h::th
                        (
                            setClass('py-1.5 pr-2 font-normal nowrap items-center text-right'),
                            $lang->block->projectstatistic->sv,
                            icon
                            (
                                set('data-toggle', 'tooltip'),
                                set('href', 'svTooltip'),
                                'help'
                            ),
                            ':',
                            div
                            (
                                setClass('tooltip z-50 shadow bg-white text-gray leading-6'),
                                set::id('svTooltip'),
                                'svTooltip'
                            ),
                        ),
                        h::td
                        (
                            setClass('py-1.5 pl-2'),
                            span
                            (
                                setClass('font-bold text-black mr-1'),
                                zget($project, 'sv', 0) . $lang->percent,
                            ),
                        ),
                    ),
                    h::tr
                    (
                        h::th
                        (
                            setClass('py-1.5 pr-2 font-normal nowrap items-center text-right'),
                            $lang->block->projectstatistic->pv,
                            icon
                            (
                                set('data-toggle', 'tooltip'),
                                set('href', 'pvTooltip'),
                                'help'
                            ),
                            ':',
                            div
                            (
                                setClass('tooltip z-50 shadow bg-white text-gray leading-6'),
                                set::id('pvTooltip'),
                                'pvTooltip'
                            ),
                        ),
                        h::td
                        (
                            setClass('py-1.5 pl-2'),
                            span
                            (
                                setClass('font-bold text-black mr-1'),
                                zget($project, 'pv', 0) . $lang->percent,
                            ),
                        ),
                    ),
                    h::tr
                    (
                        h::th
                        (
                            setClass('py-1.5 pr-2 font-normal nowrap items-center text-right'),
                            $lang->block->projectstatistic->ev,
                            icon
                            (
                                set('data-toggle', 'tooltip'),
                                set('href', 'evTooltip'),
                                'help'
                            ),
                            ':',
                            div
                            (
                                setClass('tooltip z-50 shadow bg-white text-gray leading-6'),
                                set::id('evTooltip'),
                                'evTooltip'
                            ),
                        ),
                        h::td
                        (
                            setClass('py-1.5 pl-2'),
                            span
                            (
                                setClass('font-bold text-black mr-1'),
                                zget($project, 'ev', 0) . $lang->percent,
                            ),
                        ),
                    ),
                ),
            ),
        );
        $cells[] = cell
        (
            setClass('project-statistic-table pl-4 border-l' . (!$longBlock ? ' mt-3' : '')),
            set::width($longBlock ? '32%' : '50%'),
            div
            (
                setClass('w-full'),
                span
                (
                    setClass('font-bold'),
                    $lang->block->projectstatistic->currentCost
                ),
            ),
            h::table
            (
                setClass('table-data'),
                h::tbody
                (
                    h::tr
                    (
                        h::th
                        (
                            setClass('py-1.5 pr-2 font-normal nowrap items-center text-right'),
                            $lang->block->projectstatistic->cv,
                            icon
                            (
                                set('data-toggle', 'tooltip'),
                                set('href', 'cvTooltip'),
                                'help'
                            ),
                            ':',
                            div
                            (
                                setClass('tooltip z-50 shadow bg-white text-gray leading-6'),
                                set::id('cvTooltip'),
                                'cvTooltip'
                            ),
                        ),
                        h::td
                        (
                            setClass('py-1.5 pl-2'),
                            span
                            (
                                setClass('font-bold text-black mr-1'),
                                zget($project, 'cv', 0) . $lang->percent,
                            ),
                        ),
                    ),
                    h::tr
                    (
                        h::th
                        (
                            setClass('py-1.5 pr-2 font-normal nowrap items-center text-right'),
                            $lang->block->projectstatistic->ev,
                            icon
                            (
                                set('data-toggle', 'tooltip'),
                                set('href', 'evTooltip'),
                                'help'
                            ),
                            ':',
                            div
                            (
                                setClass('tooltip z-50 shadow bg-white text-gray leading-6'),
                                set::id('evTooltip'),
                                'evTooltip'
                            ),
                        ),
                        h::td
                        (
                            setClass('py-1.5 pl-2'),
                            span
                            (
                                setClass('font-bold text-black mr-1'),
                                zget($project, 'ev', 0) . $lang->percent,
                            ),
                        ),
                    ),
                    h::tr
                    (
                        h::th
                        (
                            setClass('py-1.5 pr-2 font-normal nowrap items-center text-right'),
                            $lang->block->projectstatistic->ac,
                            icon
                            (
                                set('data-toggle', 'tooltip'),
                                set('href', 'acTooltip'),
                                'help'
                            ),
                            ':',
                            div
                            (
                                setClass('tooltip z-50 shadow bg-white text-gray leading-6'),
                                set::id('acTooltip'),
                                'acTooltip'
                            ),
                        ),
                        h::td
                        (
                            setClass('py-1.5 pl-2'),
                            span
                            (
                                setClass('font-bold text-black mr-1'),
                                zget($project, 'ac', 0) . $lang->percent,
                            ),
                        ),
                    ),
                ),
            ),
        );
    }

    $projectInfo = div
    (
        div
        (
            setClass('flex bg-white leading-6 px-2 py-1 mt-1 mx-3 shadow items-center gap-x-2 justify-between' . ($longBlock ? ' h-10 mb-6 flex-nowrap' : 'h-20 mb-4 flex-wrap')),
            $projectOverview,
            $lastestExecution,
        ),
        div
        (
            setClass('flex' . (!$longBlock ? ' flex-wrap' : '')),
            $cells,
        )
    );
    if($longBlock)
    {
        $navTabs[] = li
        (
            setClass('nav-item'),
            a
            (
                setClass('ellipsis title ' . ($project->id == $selected ? ' active' : '')),
                set('data-toggle', 'tab'),
                set::href("#tab3{$blockNavCode}Content{$project->id}"),
                $project->name

            ),
            a
            (
                setClass('link flex-1 text-right hidden'),
                set::href(helper::createLink('project', 'index', "projectID={$project->id}")),
                icon
                (
                    setClass('rotate-90 text-primary'),
                    'export'
                )
            )
        );

        $tabItems[] = div
        (
            setClass('tab-pane' . ($project->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavCode}Content{$project->id}"),
            $projectInfo,
        );
    }
    else
    {
        $index         = array_search($project->id, $projectIdList);
        $nextProjectID = $index !== false && !empty($projectIdList[$index + 1]) ? $projectIdList[$index + 1] : 0;

        $tabItems   = array();
        $tabItems[] = cell
        (
            ul
            (
                setClass('nav nav-tabs h-10 px-1 justify-between items-center'),
                set::width('100%'),
                li
                (
                    setClass('nav-item'),
                    btn
                    (
                        setClass('size-sm shadow-lg circle pre-button'),
                        set::square(true),
                        set::disabled(empty($preProjectID)),
                        set::href("#tab3{$blockNavCode}Content{$preProjectID}"),
                        set('data-toggle', 'tab'),
                        set::iconClass('text-xl text-primary'),
                        set::icon('angle-left'),
                    ),
                ),
                li
                (
                    setClass('nav-item'),
                    hasPriv('project', 'index') ? btn
                    (
                        setClass('ghost'),
                        set::url(createLink('project', 'index', "projectID={$project->id}")),
                        span
                        (
                            setClass('text-primary'),
                            $project->name
                        ),
                        icon
                        (
                            setClass('text-primary ml-4 rotate-90'),
                            'export'
                        ),
                    ) : $project->name,
                ),
                li
                (
                    setClass('nav-item'),
                    btn
                    (
                        setClass('size-sm shadow-lg circle next-button'),
                        set::square(true),
                        set::disabled(empty($nextProjectID)),
                        set::href("#tab3{$blockNavCode}Content{$nextProjectID}"),
                        set('data-toggle', 'tab'),
                        set::iconClass('text-xl text-primary'),
                        set::icon('angle-right'),
                    ),
                ),
            ),
        );
        $tabItems[] = cell
        (
            setClass('tab-content'),
            set::width('100%'),
            div
            (
                $projectInfo,
            ),
        );
        $statisticCells[] = cell
        (
            setClass('tab-pane w-full' . ($project->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavCode}Content{$project->id}"),
            $tabItems,
        );
        $preProjectID = $project->id;
    }
}
if($longBlock)
{
    $statisticCells[] = cell
    (
        set::width('22%'),
        setClass('bg-secondary-pale overflow-y-auto overflow-x-hidden'),
        ul
        (
            setClass('nav nav-tabs nav-stacked'),
            $navTabs,
        ),
    );
    $statisticCells[] = cell
    (
         setClass('tab-content'),
         set::width('78%'),
         $tabItems,
    );
}

panel
(
    setClass('projectstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set::bodyClass('no-shadow border-t'),
    set::title($block->title),
    to::headingActions
    (
        hasPriv('project', 'browse') ? h::nav
        (
            setClass('toolbar'),
            btn
            (
                setClass('ghost toolbar-item size-sm'),
                set::url(createLink('project', 'browse', "browseType={$block->params->type}")),
                $lang->more,
                span(setClass('caret-right')),
            )
        ) : '',
    ),
    div
    (
        setClass('flex h-full' . (!$longBlock ? ' flex-wrap' : '')),
        $statisticCells,
    )
);

render();
