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

blockPanel
(
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
                    div
                    (
                        setClass('h-0 w-0'),
                        div(setClass('item-icon h-9 w-9'))
                    ),
                    div
                    (
                        setClass('text-gray pl-1'),
                        $lang->block->teamachievement->finishedTasks
                    ),
                    div
                    (
                        setClass('mt-2 items-center pl-1'),
                        div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-base'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-md'),
                                $finishedTasks
                            )
                        ),
                        $comparedTasks > 0 ? div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-base text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success text-md pl-1'),
                                '+' . $comparedTasks
                            )
                        ) : null
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-story pl-8 w-1/2'),
                    div
                    (
                        setClass('h-0 w-0'),
                        div(setClass('item-icon h-9 w-9'))
                    ),
                    div
                    (
                        setClass('text-gray pl-1'),
                        $lang->block->teamachievement->createdStories
                    ),
                    div
                    (
                        setClass('mt-2 items-center pl-1'),
                        div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-base'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-md'),
                                $createdStories
                            )
                        ),
                        $comparedStories > 0 ? div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-base text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success text-md pl-1'),
                                '+' . $comparedStories
                            )
                        ) : null
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
                    div
                    (
                        setClass('h-0 w-0'),
                        div(setClass('item-icon h-9 w-9'))
                    ),
                    div
                    (
                        setClass('text-gray pl-1'),
                        $lang->block->teamachievement->closedBugs
                    ),
                    div
                    (
                        setClass('mt-2 items-center pl-1'),
                        div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-base'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-md'),
                                $closedBugs
                            )
                        ),
                        $comparedBugs > 0 ? div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-base text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success text-md pl-1'),
                                '+' . $comparedBugs
                            )
                        ) : null
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-case pl-8 w-1/2'),
                    div
                    (
                        setClass('h-0 w-0'),
                        div(setClass('item-icon h-9 w-9'))
                    ),
                    div
                    (
                        setClass('text-gray pl-1'),
                        $lang->block->teamachievement->runCases
                    ),
                    div
                    (
                        setClass('mt-2 items-center pl-1'),
                        div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-base'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-md'),
                                $runCases
                            )
                        ),
                        $comparedCases > 0 ? div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-base text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success text-md pl-1'),
                                '+' . $comparedCases
                            )
                        ) : null
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
                    div
                    (
                        setClass('h-0 w-0'),
                        div(setClass('item-icon h-9 w-9'))
                    ),
                    div
                    (
                        setClass('text-gray pl-1'),
                        $lang->block->teamachievement->consumedHours . ' / ' . $lang->block->projectstatistic->hour
                    ),
                    div
                    (
                        setClass('mt-2 items-center pl-1'),
                        div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-base'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-md'),
                                $consumedHours
                            )
                        ),
                        $comparedHours > 0 ? div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-base text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success text-md pl-1'),
                                '+' . $comparedHours
                            ),
                        ) : null
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-workload pl-8 w-1/2'),
                    div
                    (
                        setClass('h-0 w-0'),
                        div(setClass('item-icon h-9 w-9'))
                    ),
                    div
                    (
                        setClass('text-gray  pl-1'),
                        $lang->block->teamachievement->totalWorkload . ' / ' . $lang->block->projectstatistic->personDay
                    ),
                    div
                    (
                        setClass('mt-2 items-center pl-1'),
                        div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-base'),
                                $lang->block->teamachievement->accrued
                            ),
                            span
                            (
                                setClass('ml-0.5 text-md'),
                                $totalWorkload
                            )
                        ),
                        $todayWorkload > 0 ? div
                        (
                            setClass('items-center'),
                            span
                            (
                                setClass('text-success text-base'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('text-success text-md pl-1'),
                                '+' . $todayWorkload
                            )
                        ) : null
                    )
                )
            )
        )
    )
);

render();
