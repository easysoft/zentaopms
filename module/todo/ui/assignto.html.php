<?php
declare(strict_types=1);
namespace zin;
/**
 * AssignTo view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Liu <liuyue@easycorp.ltd>
 * @package     todo
 * @link        https://www.zentao.net
 */

set::title($lang->todo->assignedTo);

formPanel
(
    set::class('bg-white', 'p-6'),
    formGroup
    (
        set::name('assignedTo'),
        set::label($lang->todo->assignedTo),
        set::width('1/2'),
        set::items($members)
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->todo->date),
            set::width('1/2'),
            input
            (
                set::name('date'),
                set::type('date'),
                set::value(date('Y-m-d'))
            )
        ),
        formGroup
        (
            set::class(array('items-center', 'pl-2')),
            checkbox
            (
                set::name('future'),
                set::text($lang->todo->periods['future']),
                on::change('togglePending(this)')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->todo->beginAndEnd),
            inputGroup
            (
                select
                (
                    set::name('begin'),
                    set::id('begin'),
                    set::items($times),
                    set::value(date('Y-m-d') != $todo->date ? key($times) : $time),
                    on::change('selectNext')
                ),
                span($lang->todo->to, set::class('input-group-addon')),
                select
                (
                    set::name('end'),
                    set::id('end'),
                    set::items($times)
                )
            )
        ),
        formGroup
        (
            set::class(array('items-center', 'pl-2')),
            checkbox
            (
                set::name('lblDisableDate'),
                set::text($lang->todo->periods['future']),
                on::change('switchDateFeature(this)')
            )
        )
    )
);

render();
