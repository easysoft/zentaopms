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

$todoDate = formatTime($todo->date, DT_DATE1);
$isFuture = FUTURE_TIME == $todoDate;
formPanel
(
    setClass('bg-white', 'p-6'),
    set::submitBtnText($lang->save),
    formGroup
    (
        set::name('assignedTo'),
        set::label($lang->todo->assignedTo),
        set::width('1/2'),
        set::items($members),
        set::required(true)
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->todo->date),
            set::width('1/2'),
            datePicker
            (
                setClass('date'),
                set::name('date'),
                set::disabled($isFuture),
                set::value($isFuture ? null : $todoDate),
                on::change('changeDate')
            )
        ),
        formGroup
        (
            setClass(array('items-center', 'pl-2')),
            checkbox
            (
                setID('switchDate'),
                set::name('future'),
                set::checked($isFuture),
                set::text($lang->todo->periods['future']),
                on::change('togglePending')
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
                picker
                (
                    setID('begin'),
                    set::name('begin'),
                    set::items($times),
                    set::value(date('Y-m-d') != $todoDate ? key($times) : $todo->begin),
                    set::required(true),
                    on::change('selectNext')
                ),
                span($lang->todo->timespanTo, setClass('input-group-addon')),
                picker
                (
                    setID('end'),
                    set::name('end'),
                    set::items($times),
                    set::value($todo->end),
                    set::required(true)
                )
            )
        ),
        formGroup
        (
            setClass(array('items-center', 'pl-2')),
            checkbox
            (
                set::name('lblDisableDate'),
                set::text($lang->todo->future),
                on::change('switchDateFeature')
            )
        )
    )
);
hr();
history();
