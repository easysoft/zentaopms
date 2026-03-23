<?php
declare(strict_types=1);
/**
* The teamachievement block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$yesterdayClassName = $app->getClientLang() === 'en' ? 'items-center text-gray border-r pr-2 col-reverse' : 'items-center text-gray border-r pr-2';
$todayClassName     = $app->getClientLang() === 'en' ? 'items-center text-success pl-2 col-reverse' : 'items-center text-success pl-2';

blockPanel
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
    setClass('teamachievement-block'),
    set::bodyClass('ml-6'),
    div
    (
        setClass('flex flex-wrap gap-y-3'),
        div
        (
            setClass('flex mt-1 px-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('item-task px-1 w-1/2'),
                    div(setClass('h-0 w-0'), div(setClass('item-icon h-9 w-9'))),
                    div(setClass('text-gray pl-1'), $lang->block->teamachievement->finishedTasks),
                    div
                    (
                        setClass('mt-2 items-center flex pl-1'),
                        cell
                        (
                            setClass($yesterdayClassName),
                            span(setClass('text-base'), $lang->yesterday),
                            span(setClass('text-md pl-1 font-bold num'), $yesterdayTasks)
                        ),
                        cell
                        (
                            setClass($todayClassName),
                            span(setClass('text-base'), $lang->today),
                            span(setClass('text-md pl-1 font-bold num'), $finishedTasks)
                        )
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-story pl-8 w-1/2'),
                    div(setClass('h-0 w-0'), div(setClass('item-icon h-9 w-9'))),
                    div(setClass('h-0 w-0'), div(setClass('item-icon h-9 w-9'))),
                    div(setClass('text-gray pl-1'), $lang->block->teamachievement->createdStories),
                    div
                    (
                        setClass('mt-2 items-center flex pl-1'),
                        cell
                        (
                            setClass($yesterdayClassName),
                            span(setClass('text-base'), $lang->yesterday),
                            span(setClass('text-md pl-1 font-bold num'), $yesterdayStories)
                        ),
                        cell
                        (
                            setClass($todayClassName),
                            span(setClass('text-base'), $lang->today),
                            span(setClass('text-md pl-1 font-bold num'), $createdStories)
                        )
                    )
                )
            )
        ),
        div
        (
            setClass('flex px-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('item-bug px-1 w-1/2'),
                    div(setClass('h-0 w-0'), div(setClass('item-icon h-9 w-9'))),
                    div(setClass('text-gray pl-1'), $lang->block->teamachievement->closedBugs),
                    div
                    (
                        setClass('mt-2 items-center flex pl-1'),
                        cell
                        (
                            setClass($yesterdayClassName),
                            span(setClass('text-base'), $lang->yesterday),
                            span(setClass('text-md pl-1 font-bold num'), $yesterdayBugs)
                        ),
                        cell
                        (
                            setClass($todayClassName),
                            span(setClass('text-base'), $lang->today),
                            span(setClass('text-md pl-1 font-bold num'), $closedBugs)
                        )
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-case pl-8 w-1/2'),
                    div(setClass('h-0 w-0'), div(setClass('item-icon h-9 w-9'))),
                    div(setClass('text-gray pl-1'), $lang->block->teamachievement->runCases),
                    div
                    (
                        setClass('mt-2 items-center flex pl-1'),
                        cell
                        (
                            setClass($yesterdayClassName),
                            span(setClass('text-base'), $lang->yesterday),
                            span(setClass('text-md pl-1 font-bold num'), $yesterdayCases)
                        ),
                        cell
                        (
                            setClass($todayClassName),
                            span(setClass('text-base'), $lang->today),
                            span(setClass('text-md pl-1 font-bold num'), $runCases)
                        )
                    )
                )
            )
        ),
        div
        (
            setClass('flex px-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('item-hour px-1 w-1/2'),
                    div(setClass('h-0 w-0'), div(setClass('item-icon h-9 w-9'))),
                    div(setClass('text-gray pl-1'), $lang->block->teamachievement->consumedHours . ' / ' . $lang->block->projectstatistic->hour),
                    div
                    (
                        setClass('mt-2 items-center flex pl-1'),
                        cell
                        (
                            setClass($yesterdayClassName),
                            span(setClass('text-base'), $lang->yesterday),
                            span(setClass('text-md pl-1 font-bold num'), $yesterdayHours)
                        ),
                        cell
                        (
                            setClass($todayClassName),
                            span(setClass('text-base'), $lang->today),
                            span(setClass('text-md pl-1 font-bold num'), $consumedHours)
                        )
                    )
                )
            )
        )
    )
);

render();
