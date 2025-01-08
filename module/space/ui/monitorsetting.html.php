<?php
declare(strict_types=1);
/**
 * The setting view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZhangXiquan<zhangxiquan@chandao.com>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;

$warning = zget($setting, 'warning', new stdClass());
$danger  = zget($setting, 'danger', new stdClass());
formPanel
(
    set::title($title),
    setID('monitorSetting'),
    setClass('w-full'),
    h::table
    (
        setClass('table bordered'),
        h::thead
        (
            h::tr
            (
                h::td(setClass('w-24')),
                h::td($lang->space->monitor->warning),
                h::td($lang->space->monitor->danger)
            )
        ),
        h::tr
        (
            h::td($lang->space->monitor->cpu),
            h::td
            (
                formRow
                (
                    formGroup
                    (
                        inputGroup
                        (
                            span($lang->space->monitor->used, setClass('input-group-addon ghost')),
                            input
                            (
                                set::name('warning[cpu][threshold]'),
                                set::value(zget(empty($warning->cpu) ? new stdClass() : $warning->cpu, 'threshold', 80))
                            ),
                            span(setClass('input-control-suffix'), '%')
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
                                set::value(zget(empty($warning->cpu) ? new stdClass() : $warning->cpu, 'duration', 5))
                            ),
                            $lang->space->monitor->minutes
                        )
                    )
                )
            ),
            h::td
            (
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
                                set::value(zget(empty($danger->cpu) ? new stdClass() : $danger->cpu, 'threshold', 90))
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
                                set::value(zget(empty($danger->cpu) ? new stdClass() : $danger->cpu, 'duration', 10))
                            ),
                            $lang->space->monitor->minutes
                        )
                    )
                )
            )
        ),
        h::tr
        (
            h::td($lang->space->monitor->memory),
            h::td
            (
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
                                set::value(zget(empty($warning->memory) ? new stdClass() : $warning->memory, 'threshold', 80))
                            ),
                            '%'
                        )
                    )
                )
            ),
            h::td
            (
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
                                set::value(zget(empty($danger->memory) ? new stdClass() : $danger->memory, 'threshold', 90))
                            ),
                            '%'
                        )
                    )
                )
            )
        ),
        h::tr
        (
            h::td($lang->space->monitor->disk),
            h::td
            (
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
                                set::value(zget(empty($warning->disk) ? new stdClass() : $warning->disk, 'threshold', 80))
                            ),
                            '%'
                        )
                    )
                )
            ),
            h::td
            (
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
                                set::value(zget(empty($danger->disk) ? new stdClass() : $danger->disk, 'threshold', 90))
                            ),
                            '%'
                        )
                    )
                )
            )
        )
    )
);
