<?php
namespace zin;

global $lang;

$items = [];
foreach($members as $key => $value) {
    $items[] = ['text' => $value, 'value' => $key];
}

div
(
    setClass('modal assignto-dialog'),
    div
    (
        setClass('modal-dialog'),
        div
        (
            setClass('modal-content'),
            div
            (
                setClass('modal-header'),
                label(data('task.id')),
                div
                (
                    setClass('modal-title'),
                    data('task.name'),
                ),
                btn
                (
                    setClass('square ghost'),
                    set('data-dismiss', 'modal'),
                    span(setClass('close'))
                ),
                div(setClass('modal-divider'))
            ),
            div
            (
                setClass('modal-body'),
                formGrid
                (
                    set::method('POST'),
                    formGroup
                    (
                        formLabel($lang->assignedToAB),
                        formCell
                        (
                            select
                            (
                                set::name('assignedTo'),
                                set::id('assignedTo'),
                                set::items($items)
                            ),
                        )
                    ),
                    formGroup
                    (
                        formLabel($lang->task->left),
                        formCell
                        (
                            div
                            (
                                setClass('input-control has-suffix'),
                                formInput
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
                        )
                    ),
                    formGroup
                    (
                        formLabel($lang->comment),
                        formCell
                        (
                            textarea
                            (
                                setClass('form-control'),
                                set::name('comment'),
                                set::id('comment')
                            )
                        )
                    ),
                    formGroup
                    (
                        setClass('justify-center'),
                        button
                        (
                            set::type('submit'),
                            setClass('btn primary'),
                            $lang->save
                        )
                    )
                )
            )
        )
    )
);

render();
