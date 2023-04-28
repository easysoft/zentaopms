<?php
declare(strict_types=1);
namespace zin;
/**
 * The batch create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Liu <liuyue@easycorp.ltd>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21 $
 * @link        http://www.zentao.net
 */

set::title($lang->todo->assignedTo);

formPanel
(
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
            set::class(array('items-center', 'pl-3')),
            checkbox
            (
                set::name('future'),
                set::text($lang->todo->periods['future']),
                on::change('switchDateTodo(this)')
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
            set::class(array('items-center', 'pl-3')),
            checkbox
            (
                set::name('lblDisableDate'),
                set::text($lang->todo->lblDisableDate),
                on::change('switchDateFeature(this)')
            )
        )
    )
);

render();
