<?php
namespace zin;

form
(
    set::actions(array()),
    set::action($_SERVER['REQUEST_URI']),
    set::method('post'),
    inputGroup
    (
        input
        (
            set::id('title'),
            set::name('title'),
            set::type('text'),
            set::placeholder($lang->search->setCondName)
        ),
        span
        (
            setClass('input-group-addon'),
            checkbox
            (
                set::name('common'),
                set::value(1),
                $lang->search->setCommon
            )
        ),
        $onMenuBar == 'yes' ? span
        (
            setClass('input-group-addon'),
            checkbox
            (
                set::name('onMenuBar'),
                $lang->search->onMenuBar
            )
        ) : null,
        input
        (
            set::type('hidden'),
            set::name('module'),
            set::value($module)
        ),
        span
        (
            setClass('input-group-btn'),
            btn
            (
                setClass('primary'),
                set::btnType('submit'),
                set('data-type', 'submit'),
                $lang->save
            )
        )
    )
);

render();
