<?php
namespace zin;

form
(
    set::actions(null),
    set::action($_SERVER['REQUEST_URI']),
    set::method('post'),
    div
    (
        setClass('flex flex-row justify-between'),
        input
        (
            set::id('title'),
            set::name('title'),
            set::type('text'),
            set::class('form-control w-5/12'),
            set::placeholder($lang->search->setCondName)
        ),
        checkbox
        (
            set::id('common'),
            set::name('common'),
            set::value(1),
            set::class('w-3/12'),
            $lang->search->setCommon
        ),
        checkbox
        (
            set::id('onMenuBar'),
            set::name('onMenuBar'),
            set::class('w-3/12'),
            $lang->search->onMenuBar
        ),
        btn(
            setClass('w-1/12 primary'),
            set::type('submit'),
            set('data-type', 'submit'),
            $lang->save
        ),
        input
        (
            set::type('hidden'),
            set::name('module'),
            set::value($module)
        )
    )
);

render('modalDialog');
