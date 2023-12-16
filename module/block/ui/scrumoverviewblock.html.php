<?php
declare(strict_types=1);
/**
* The scrumoverview block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$cells = array();
foreach($config->block->projectstatistic->items as $module => $items)
{
    $cellItems = array();
    foreach($items as $item)
    {
        $field = $item['field'];
        $unit  = $item['unit'];
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
                $lang->block->projectstatistic->{$unit}
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

$width         = $config->edition != 'open' ? '33%' : '50%';
$remainingDays = zget($project, 'remainingDays' , 0);

panel
(
    setClass('scrumoverview-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set::bodyClass('no-shadow border-t'),
    set::title($block->title),
    div
    (
        setClass('flex flex-wrap h-full w-full' . (!$longBlock ? ' flex-wrap' : '')),
        div
        (
            setClass('flex w-full bg-white leading-6 px-2 py-1 mt-1 mx-3 shadow-sm items-center gap-x-2 justify-between' . ($longBlock ? ' h-10 mb-2 flex-nowrap' : 'h-20 mb-4 flex-wrap')),
            cell
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
            ),
            $config->edition != 'open' ? cell
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
                        round(zget($project, 'risks', 0), 2)
                    )
                ),
                span
                (
                    setClass('text-gray'),
                    $lang->block->projectstatistic->existIssues,
                    span
                    (
                        setClass('font-bold text-warning'),
                        zget($project, 'issues', 0)
                    )
                )
            ) : '',
            (!empty($project->executions) && $project->multiple) ? cell
            (
                setClass('flex-1 overflow-hidden whitespace-nowrap' . (!$longBlock ? ' text-left w-full' : ' text-right')),
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
                        $project->executions[0]->name
                    ) : span
                    (
                        setClass('pl-2'),
                        $project->executions[0]->name
                    )
                )
            ) : null,
        ),
        div
        (
            setClass('flex w-full' . (!$longBlock ? ' flex-wrap' : '')),
            $cells
        )
    )
);

render();
