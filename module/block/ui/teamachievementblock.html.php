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
        setClass('flex flex-wrap gap-y-3 py-0.5'),
        div
        (
            setClass('flex p-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('item-task px-1'),
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
                        setClass('mt-2 flex flex-nowrap items-center pl-1'),
                        row
                        (
                            $comparedTasks > 0 ? width('1/3') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-sm'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-lg'),
                                $finishedTasks
                            )
                        ),
                        $comparedTasks > 0 ? div(setClass('divider mx-4')) : null,
                        $comparedTasks > 0 ? row
                        (
                            width('2/3'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-sm text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success font-sm pl-1'),
                                '+' . $comparedTasks
                            )
                        ) : null
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-story pl-8'),
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
                        setClass('mt-2 flex flex-nowrap items-center pl-1'),
                        row
                        (
                            $comparedStories > 0 ? width('1/3') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-sm'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-lg'),
                                $createdStories
                            )
                        ),
                        $comparedStories > 0 ? div(setClass('divider mx-4')) : null,
                        $comparedStories > 0 ? row
                        (
                            width('2/3'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-sm text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success font-sm pl-1'),
                                '+' . $comparedStories
                            )
                        ) : null
                    )
                )
            )
        ),
        div
        (
            setClass('flex p-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('item-bug px-1'),
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
                        setClass('mt-2 flex flex-nowrap items-center pl-1'),
                        row
                        (
                            $comparedBugs > 0 ? width('1/3') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-sm'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-lg'),
                                $closedBugs
                            )
                        ),
                        $comparedBugs > 0 ? div(setClass('divider mx-4')) : null,
                        $comparedBugs > 0 ? row
                        (
                            width('2/3'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-sm text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success font-sm pl-1'),
                                '+' . $comparedBugs
                            )
                        ) : null
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-case pl-8'),
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
                        setClass('mt-2 flex flex-nowrap items-center pl-1'),
                        row
                        (
                            $comparedCases > 0 ? width('1/3') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-sm'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-lg'),
                                $runCases
                            )
                        ),
                        $comparedCases > 0 ? div(setClass('divider mx-4')) : null,
                        $comparedCases > 0 ? row
                        (
                            width('2/3'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-sm text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success font-sm pl-1'),
                                '+' . $comparedCases
                            )
                        ) : null
                    )
                )
            )
        ),
        div
        (
            setClass('flex p-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('item-hour px-1'),
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
                        setClass('mt-2 flex flex-nowrap items-center pl-1'),
                        row
                        (
                            $comparedHours > 0 ? width('1/3') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray text-sm'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('ml-0.5 text-lg'),
                                $consumedHours
                            )
                        ),
                        $comparedHours > 0 ? div(setClass('divider mx-4')) : null,
                        $comparedHours > 0 ? row
                        (
                            width('2/3'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-sm text-success'),
                                $lang->block->teamachievement->vs
                            ),
                            span
                            (
                                setClass('text-success font-sm pl-1'),
                                '+' . $comparedHours
                            ),
                        ) : null
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('item-workload pl-8'),
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
                        setClass('mt-2 flex flex-nowrap items-center'),
                        row
                        (
                            $todayWorkload > 0 ? width('1/3') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('ml-0.5 text-lg'),
                                $totalWorkload
                            )
                        ),
                        $todayWorkload > 0 ? div(setClass('divider mx-4')) : null,
                        $todayWorkload > 0 ? row
                        (
                            width('2/3'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-success text-sm'),
                                $lang->today
                            ),
                            span
                            (
                                setClass('text-success font-sm pl-1'),
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
