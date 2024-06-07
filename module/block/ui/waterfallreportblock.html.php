<?php
declare(strict_types=1);
/**
* The waterfall report block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$items[] = array
(
    'id'        => $project->id,
    'text'      => $project->name,
    'url'       => createLink('project', 'index', "projectID={$projectItem->id}"),
    'activeUrl' => createLink('block', 'printBlock', "blockID={$block->id}&params={$params}")
);

$remainingDays   = !empty($project->remainingDays) ? $project->remainingDays : 0;
$projectOverview = array();
if($project)
{
    $projectOverview[] = cell
    (
        setClass('text-left'),
        $project->status != 'closed' && $project->end != LONG_TIME ? span
        (
            setClass('text-gray'),
            $remainingDays >= 0 ? $lang->block->projectstatistic->leftDaysPre : $lang->block->projectstatistic->delayDaysPre,
            span
            (
                setClass('font-bold text-black px-1'),
                abs($remainingDays)
            ),
            $lang->block->projectstatistic->day
        ) : span
        (
            setClass('text-gray'),
            $project->status == 'closed' ? $lang->block->projectstatistic->projectClosed : $lang->block->projectstatistic->longTimeProject
        )
    );
    $projectOverview[] = $config->edition != 'open' ? div(setClass('divider mx-1 my-auto h-4')) : null;
    $projectOverview[] = $config->edition != 'open' ? cell
    (
        setClass('flex-0 text-left' . (!$longBlock ? ' w-full' : '')),
        span
        (
            setClass('text-gray mr-4'),
            $lang->block->projectstatistic->existRisks,
            span
            (
                setClass('font-bold ml-2 text-danger'),
                $project->risks
            )
        ),
        span
        (
            setClass('text-gray'),
            $lang->block->projectstatistic->existIssues,
            span
            (
                setClass('font-bold ml-2 text-danger'),
                $project->issues
            )
        )
    ) : '';

    $lastestExecution = !empty($project->executions) && $project->multiple ? cell
    (
        setClass('flex overflow-hidden whitespace-nowrap clip w-full' . (!$longBlock ? ' flex-0 text-left w-full' : ' flex-1')),
        span
        (
            setClass('text-gray'),
            $lang->block->projectstatistic->lastestExecution,
            hasPriv('execution', 'task') ? a
            (
                setClass('pl-2'),
                set::href(helper::createLink('execution', 'task', "executionID={$project->executions[0]->id}")),
                set('title', $project->executions[0]->name),
                $project->executions[0]->name
            ) : span
            (
                setClass('pl-2'),
                $project->executions[0]->name
            )
        )
    ) : null;

    $cells = array();
    if(in_array($project->model, array('scrum', 'kanban', 'agileplus')))
    {
        foreach($config->block->projectstatistic->items as $module => $moduleItems)
        {
            $cellItems = array();
            foreach($moduleItems as $item)
            {
                $field   = $item['field'];
                $unit    = $item['unit'];

                $cellItems[] = item
                (
                    set::name
                    (
                        $lang->block->projectstatistic->{$field},
                        !isset($lang->block->tooltips[$field]) ? ':' : null
                    ),
                    to::suffixName
                    (
                        isset($lang->block->tooltips[$field]) ? icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'title'     => $lang->block->tooltips[$field],
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help',
                            ':'
                        ) : null
                    ),
                    span
                    (
                        setClass('font-bold text-black mr-0.5'),
                        round(zget($project, $field, 0), 2)
                    ),
                    span
                    (
                        setClass('text-gray'),
                        $field == 'storyPoints' ? $config->block->storyUnitList[$config->custom->hourPoint] : $lang->block->projectstatistic->{$unit}
                    )
                );
            }
            $cells[] = cell
            (
                setClass('flex-1 overflow-hidden whitespace-nowrap project-statistic-table scrum' . (($module != 'cost' && $longBlock) || ($module != 'task' && $module != 'cost' && !$longBlock) ? ' border-l pl-4 ' : ' ml-4') . (!$longBlock && $module != 'cost' && $module != 'story'? ' border-t' : '')),
                set::width($longBlock ? ($module == 'cost' ? 'calc(25% - 1rem)' : '25%') : 'calc(50% - 1rem)'),
                div
                (
                    setClass('pt-1'),
                    span
                    (
                        setClass('font-bold'),
                        $lang->block->projectstatistic->{$module}
                    )
                ),
                tableData
                (
                    set::width('100%'),
                    $cellItems
                )
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
                progressCircle
                (
                    set::percent($project->progress),
                    set::size(112),
                    set::text(false),
                    set::circleWidth(0.06),
                    div(span(setClass('text-2xl font-bold'), $project->progress), '%'),
                    div
                    (
                        setClass('row text-sm text-gray items-center gap-1'),
                        $lang->block->projectstatistic->totalProgress,
                        icon
                        (
                            setClass('pl-0.5 text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'content'   => array('html' => $lang->block->projectstatistic->totalProgressTip),
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
        );
        $cells[] = cell
        (
            setClass('project-statistic-table waterfall bg-gray-50 border border-1 pt-2 px-1'),
            set::width($longBlock ? '32%' : 'calc(50% - 0.25rem)'),
            div
            (
                setClass('w-full'),
                span
                (
                    setClass('font-bold ml-3'),
                    $lang->project->progress
                )
            ),
            tableData
            (
                item
                (
                    set::name($lang->block->projectstatistic->sv),
                    to::suffixName
                    (
                        icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'title'     => $lang->block->tooltips['sv'],
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help'
                        ),
                    ),
                    span
                    (
                        setClass('font-bold text-black mr-1'),
                        (!empty($project->sv) ? $project->sv : 0) . $lang->percent
                    )
                ),
                item
                (
                    set::name($lang->block->projectstatistic->pv),
                    to::suffixName
                    (
                        icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'content'   => array('html' => $lang->block->tooltips['pv']),
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help'
                        ),
                    ),
                    span
                    (
                        setClass('font-bold text-black mr-1'),
                        !empty($project->pv) ? $project->pv : 0
                    )
                ),
                item
                (
                    set::name($lang->block->projectstatistic->ev),
                    to::suffixName
                    (
                        icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'content'   => array('html' => $lang->block->tooltips['ev']),
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help'
                        ),
                    ),
                    span
                    (
                        setClass('font-bold text-black mr-1'),
                        !empty($project->ev) ? $project->ev : 0
                    )
                )
            )
        );
        $cells[] = cell
        (
            setClass('project-statistic-table waterfall bg-gray-50 border border-1 pt-2 px-1'),
            set::width($longBlock ? '32%' : 'calc(50% - 0.25rem)'),
            div
            (
                setClass('w-full'),
                span
                (
                    setClass('font-bold ml-3'),
                    $lang->block->projectstatistic->currentCost
                )
            ),
            tableData
            (
                item
                (
                    set::name($lang->block->projectstatistic->cv),
                    to::suffixName
                    (
                        icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'title'     => $lang->block->tooltips['cv'],
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help'
                        ),
                    ),
                    span
                    (
                        setClass('font-bold text-black mr-1'),
                        (!empty($project->cv) ? $project->cv : 0) . $lang->percent
                    ),
                ),
                item
                (
                    set::name($lang->block->projectstatistic->ev),
                    to::suffixName
                    (
                        icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'content'   => array('html' => $lang->block->tooltips['ev']),
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help'
                        ),
                    ),
                    span
                    (
                        setClass('font-bold text-black mr-1'),
                        !empty($project->ev) ? $project->ev : 0
                    )
                ),
                item
                (
                    set::name($lang->block->projectstatistic->ac),
                    to::suffixName
                    (
                        icon
                        (
                            setClass('text-light text-sm'),
                            toggle::tooltip
                            (
                                array
                                (
                                    'content'   => array('html' => $lang->block->tooltips['ac']),
                                    'placement' => 'bottom',
                                    'type'      => 'white',
                                    'className' => 'text-dark border border-light leading-5'
                                )
                            ),
                            'help'
                        ),
                    ),
                    span
                    (
                        setClass('font-bold text-black mr-1'),
                        !empty($project->ac) ? $project->ac : 0
                    )
                )
            )
        );
    }
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
        )
    ),
    set::block($block),
    set::active($project->id),
    set::items($items),
    set::className('waterfallreport-block'),
    $project ? div
    (
        div
        (
            setClass('flex bg-white leading-6 px-2 py-1 mt-1 mx-3 items-center gap-x-2 gap-y-1 justify-between' . ($longBlock ? ' h-10 my-3 flex-nowrap' : ' h-14 mb-1 flex-wrap')),
            !empty($lastestExecution) || !$longBlock ? $lastestExecution : div(),
            div(setClass('flex justify-end gap-x-2 nowrap'), $projectOverview)
        ),
        div
        (
            setClass('flex gap-2 p-3 pt-1' . (!$longBlock ? ' flex-wrap gap-y-3' : ' pt-0')),
            $cells
        )
    ) : null
);
