<?php
declare(strict_types=1);
/**
 * The edit view of task of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming<sunguangming@easycorp.ltd>
 * @package     task
 * @link        http://www.zentao.net
 */

namespace zin;

/* zin: Define the form in main content */
formPanel
(
    set::title($lang->task->assignAction),
    set::headingClass('status-heading'),
    set::titleClass('form-label .form-grid'),
    set::actions(array('submit')),
    set::shadow(false),
    to::headingActions
    (
        entityLabel
        (
            setClass('my-3 gap-x-3'),
            set::level(1),
            set::text($task->name),
            set::entityID($task->id),
            set::reverse(true),
        )
    ),
    formGroup
    (
        set::width("1/3"),
        set::name("assignedTo"),
        set::label($lang->task->assignedTo),
        set::value((empty($task->team) or strpos('done,cencel,closed', $task->status) !== false) ? $task->assignedTo : $task->nextUser),
        set::control("picker"),
        set::items($members)
    ),
    formGroup
    (
        set::width("1/3"),
        set::label($lang->task->left),
        inputGroup
        (
            control(set(array
            (
                'name' => "left",
                'id' => "left",
                'value' => $task->left,
                'disabled' => false,
                'type' => "text"
            ))),
            $lang->task->hour
        )
    ),
    formGroup
    (
        set::width("2/3"),
        set::name("comment"),
        set::label($lang->comment),
        set::control("editor")
    )
);

h::hr(set::class('mt-6'));

history();

render();
