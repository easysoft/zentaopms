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
    $projectOverview = array();
    $projectOverview[] = cell
    (
        setClass('text-left mr-6'),
        span
        (
            setClass('text-gray'),
            $lang->block->projectstatistic->leftDaysPre,
            span
            (
                setClass('font-bold text-black px-1'),
                zget($project, 'remainingDays' , 0)
            ),
            $lang->block->projectstatistic->day
        )
    );
    $projectOverview[] = $config->edition != 'open' ? cell
    (
        setClass('flex-1' . ($longBlock ? ' text-left' : ' text-right')),
        icon('bullhorn text-warning mr-2'),
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
    $lastestExecution = (!empty($project->executions) && $project->multiple) ? cell
    (
        setClass('flex-1' . ($longBlock && $config->edition != 'open' ? ' text-left' : ' text-right')),
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
                    set::name($lang->block->projectstatistic->{$field} . ' ：'),
                    span
                    (
                        setClass('font-bold text-black mr-1'),
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
                setClass('flex-1 pt-4 project-statistic-table' . (($module != 'cost' && $longBlock) || ($module != 'task' && $module != 'cost' && !$longBlock) ? ' border-l pl-4 ' : '') . (!$longBlock && $module != 'cost' && $module != 'story'? ' border-t' : '')),
                set::width($longBlock ? '25%' : '50%'),
                div
                (
                    setClass('px-2'),
                    span
                    (
                        setClass('font-bold'),
                        $lang->block->projectstatistic->{$module}
                    ),
                ),
                tableData($cellItems),
            );
        }
    }

    $projectInfo = div
    (
        div
        (
            setClass('flex flex-wrap bg-white leading-6 px-2 py-1 m-2 shadow items-center' . ($longBlock ? ' h-10' : 'h-20')),
            $projectOverview,
            $lastestExecution,
        ),
        div
        (
            setClass('flex flex-wrap'),
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
                setClass('nav nav-tabs h-12 px-1 justify-between items-center'),
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
         setClass('tab-content px-4'),
         set::width('78%'),
         $tabItems,
    );
}

panel
(
    setClass('projectstatistic-block ' . ($longBlock ? 'block-long' : 'block-sm px-4')),
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
