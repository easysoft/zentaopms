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

panel
(
    setClass('teamachievement-block'),
    set::bodyClass('p-0 px-8'),
    set::title($block->title),
    div
    (
        setClass('flex flex-wrap gap-y-4 py-0.5'),
        div
        (
            setClass('flex shadow p-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('border-r item-task pl-4 pr-2'),
                    div
                    (
                        setClass('h-0 w-0'),
                        div
                        (
                            setClass('item-icon h-9 w-9'),
                        ),
                    ),
                    div
                    (
                        setClass('text-gray'),
                        $lang->block->teamachievement->finishedTasks,
                    ),
                    div
                    (
                        setClass('mt-3 flex flex-nowrap items-center'),
                        row
                        (
                            $comparedTasks > 0 ? width('1/2') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->today,
                            ),
                            span
                            (
                                setClass('ml-1 font-bold text-lg'),
                                $finishedTasks,
                            ),
                        ),
                        $comparedTasks > 0 ? row
                        (
                            width('1/2'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->block->teamachievement->comparedYesterday,
                            ),
                            span
                            (
                                setClass('ml-0.5 font-bold text-warning'),
                                '+' . $comparedTasks,
                            ),
                        ) : null,
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('pl-4 pr-2'),
                    div
                    (
                        setClass('text-gray'),
                        $lang->block->teamachievement->createdStories,
                    ),
                    div
                    (
                        setClass('mt-3 flex flex-nowrap items-center'),
                        row
                        (
                            $comparedStories > 0 ? width('1/2') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->today,
                            ),
                            span
                            (
                                setClass('ml-1 font-bold text-lg'),
                                $createdStories,
                            ),
                        ),
                        $comparedStories > 0 ? row
                        (
                            width('1/2'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->block->teamachievement->comparedYesterday,
                            ),
                            span
                            (
                                setClass('ml-0.5 font-bold text-warning'),
                                '+' . $comparedStories,
                            ),
                        ) : null,
                    )
                )
            ),
        ),
        div
        (
            setClass('flex shadow p-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('border-r item-bug pl-4 pr-2'),
                    div
                    (
                        setClass('h-0 w-0'),
                        div
                        (
                            setClass('item-icon h-9 w-9'),
                        ),
                    ),
                    div
                    (
                        setClass('text-gray'),
                        $lang->block->teamachievement->closedBugs,
                    ),
                    div
                    (
                        setClass('mt-3 flex flex-nowrap items-center'),
                        row
                        (
                            $comparedBugs > 0 ? width('1/2') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->today,
                            ),
                            span
                            (
                                setClass('ml-1 font-bold text-lg'),
                                $closedBugs,
                            ),
                        ),
                        $comparedBugs > 0 ? row
                        (
                            width('1/2'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->block->teamachievement->comparedYesterday,
                            ),
                            span
                            (
                                setClass('ml-0.5 font-bold text-warning'),
                                '+' . $comparedBugs,
                            ),
                        ) : null,
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('pl-4 pr-2'),
                    div
                    (
                        setClass('text-gray'),
                        $lang->block->teamachievement->runCases,
                    ),
                    div
                    (
                        setClass('mt-3 flex flex-nowrap items-center'),
                        row
                        (
                            $comparedCases > 0 ? width('1/2') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->today,
                            ),
                            span
                            (
                                setClass('ml-1 font-bold text-lg'),
                                $runCases,
                            ),
                        ),
                        $comparedCases > 0 ? row
                        (
                            width('1/2'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->block->teamachievement->comparedYesterday,
                            ),
                            span
                            (
                                setClass('ml-0.5 font-bold text-warning'),
                                '+' . $comparedCases,
                            ),
                        ) : null,
                    )
                )
            ),
        ),
        div
        (
            setClass('flex shadow p-4 w-full item-row'),
            cell
            (
                set::width('100%'),
                setClass('flex'),
                cell
                (
                    set::width('50%'),
                    setClass('border-r item-hour pl-4 pr-2'),
                    div
                    (
                        setClass('h-0 w-0'),
                        div
                        (
                            setClass('item-icon h-9 w-9'),
                        ),
                    ),
                    div
                    (
                        setClass('text-gray'),
                        $lang->block->teamachievement->consumedHours,
                    ),
                    div
                    (
                        setClass('mt-3 flex flex-nowrap items-center'),
                        row
                        (
                            $comparedHours > 0 ? width('1/2') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->today,
                            ),
                            span
                            (
                                setClass('ml-1 font-bold text-lg'),
                                $consumedHours,
                            ),
                            $lang->block->projectstatistic->hour,
                        ),
                        $comparedHours > 0 ? row
                        (
                            width('1/2'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->block->teamachievement->comparedYesterday,
                            ),
                            span
                            (
                                setClass('ml-0.5 font-bold text-warning'),
                                '+' . $comparedHours . $lang->block->projectstatistic->hour,
                            ),
                        ) : null,
                    )
                ),
                cell
                (
                    set::width('50%'),
                    setClass('pl-4 pr-2'),
                    div
                    (
                        setClass('text-gray'),
                        $lang->block->teamachievement->totalWorkload,
                    ),
                    div
                    (
                        setClass('mt-3 flex flex-nowrap items-center'),
                        row
                        (
                            $todayWorkload > 0 ? width('1/2') : width('full'),
                            setClass('items-center'),
                            span
                            (
                                setClass('ml-1 font-bold text-lg'),
                                $totalWorkload,
                            ),
                            $lang->block->projectstatistic->personDay
                        ),
                        $todayWorkload > 0 ? row
                        (
                            width('1/2'),
                            span
                            (
                                setClass('text-gray'),
                                $lang->today,
                            ),
                            span
                            (
                                setClass('ml-0.5 font-bold text-warning'),
                                '+' . $todayWorkload . $lang->block->projectstatistic->personDay,
                            ),
                        ) : null,
                    )
                )
            ),
        ),
    )
);

render();
