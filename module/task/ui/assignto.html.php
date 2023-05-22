<?php
declare(strict_types=1);
namespace zin;

/* zin: Define the form in main content */
formPanel
(
    set::title($task->name),
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


render();
