<?php
namespace zin;

global $lang;

$items = [];
foreach($members as $key => $value)
{
    $items[] = ['text' => $value, 'value' => $key];
}

set::itemID($task->id);
set::title($task->name);

form
(
    formGroup
    (
        set::label($lang->assignedToAB),
        set::name('assignedTo'),
        set::control(['type' => 'select', 'items' => $items]),
    ),
    formGroup
    (
        set::label($lang->task->left),
        div
        (
            setClass('input-control has-suffix'),
            input
            (
                set::type('number'),
                set::min(0),
                set::name('left'),
                set::id('left'),
            ),
            h::label
            (
                setClass('input-control-suffix'),
                $lang->workingHour
            )
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control(['type' => 'textarea']),
    ),
    set::actions(['save'])
);

render('modalDialog');
