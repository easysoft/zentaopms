<?php
declare(strict_types=1);
/**
 * The setting view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhangXiquan<zhangxiquan@chandao.com>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;

$warning = zget($setting, 'warning', new stdClass);
$danger  = zget($setting, 'danger', new stdClass);
formPanel
(
    set::title($title),
    setID('monitorSetting'),
    setClass('w-full'),
    col
    (
        setClass('leading-10'),
        row
        (
            set::align('center'),
            cell
            (
                setClass('border rounded px-2'),
                set::width('1/3'),
                set::flex('auto'),
                '&nbsp;',
            ),
            cell
            (
                setClass('border rounded px-2'),
                set::width('1/3'),
                set::flex('none'),
                $lang->space->monitor->warning,
            ),
            cell
            (
                setClass('border rounded px-2'),
                set::width('1/3'),
                set::flex('none'),
                $lang->space->monitor->danger,
            ),
        ),
        row
        (
            set::align('center'),
            cell
            (
                setClass('border rounded px-2'),
                set::width('1/3'),
                set::flex('auto'),
                $lang->space->monitor->cpu,
            ),
            cell
            (
                setClass('border rounded p-1'),
                set::width('1/3'),
                set::flex('none'),
                formRow
                (
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->used,
                            input
                            (
                                set::name('warning[cpu][threshold]'),
                                set::value(zget($warning->cpu, 'threshold', 80))
                            ),
                            '%'
                        )
                    ),
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->duration,
                            input
                            (
                                set::name('warning[cpu][duration]'),
                                set::value(zget($warning->cpu, 'duration', 5))
                            ),
                            $lang->space->monitor->minutes,
                        )
                    )
                )
            ),
            cell
            (
                setClass('border rounded p-1'),
                set::width('1/3'),
                set::flex('none'),
                formRow
                (
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->used,
                            input
                            (
                                set::name('danger[cpu][threshold]'),
                                set::value(zget($danger->cpu, 'threshold', 90))
                            ),
                            '%'
                        )
                    ),
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->duration,
                            input
                            (
                                set::name('danger[cpu][duration]'),
                                set::value(zget($danger->cpu, 'duration', 10))
                            ),
                            $lang->space->monitor->minutes,
                        )
                    )
                )
            )
        ),
        row
        (
            set::align('center'),
            cell
            (
                setClass('border rounded px-2'),
                set::width('1/3'),
                set::flex('auto'),
                $lang->space->monitor->memory,
            ),
            cell
            (
                setClass('border rounded p-1'),
                set::width('1/3'),
                set::flex('none'),
                formRow
                (
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->over,
                            input
                            (
                                set::name('warning[memory][threshold]'),
                                set::value(zget($warning->memory, 'threshold', 80))
                            ),
                            '%'
                        )
                    )
                )
            ),
            cell
            (
                setClass('border rounded p-1'),
                set::width('1/3'),
                set::flex('none'),
                formRow
                (
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->over,
                            input
                            (
                                set::name('danger[memory][threshold]'),
                                set::value(zget($danger->memory, 'threshold', 90))
                            ),
                            '%'
                        )
                    )
                )
            )
        ),
        row
        (
            set::align('center'),
            cell
            (
                setClass('border rounded px-2'),
                set::width('1/3'),
                set::flex('auto'),
                $lang->space->monitor->disk,
            ),
            cell
            (
                setClass('border rounded p-1'),
                set::width('1/3'),
                set::flex('none'),
                formRow
                (
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->over,
                            input
                            (
                                set::name('warning[disk][threshold]'),
                                set::value(zget($warning->disk, 'threshold', 80))
                            ),
                            '%'
                        )
                    )
                )
            ),
            cell
            (
                setClass('border rounded p-1'),
                set::width('1/3'),
                set::flex('none'),
                formRow
                (
                    formGroup
                    (
                        inputGroup
                        (
                            $lang->space->monitor->over,
                            input
                            (
                                set::name('danger[disk][threshold]'),
                                set::value(zget($danger->disk, 'threshold', 90))
                            ),
                            '%'
                        )
                    )
                )
            )
        )
    )
);

render();