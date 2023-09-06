<?php
namespace zin;

form
(
    set::actions(array()),
    set::formClass('save-search-form-container'),
    set::action($_SERVER['REQUEST_URI']),
    set::method('post'),
    div
    (
        setClass('flex flex-row justify-between items-center mr-2 save-query-form'),
        input
        (
            set::id('title'),
            set::name('title'),
            set::type('text'),
            set::className('form-control w-5/12'),
            set::placeholder($lang->search->setCondName)
        ),
        checkbox
        (
            set::id('common'),
            set::name('common'),
            set::value(1),
            set::className('w-3/12'),
            $lang->search->setCommon
        ),
        checkbox
        (
            set::id('onMenuBar'),
            set::name('onMenuBar'),
            set::className('w-3/12'),
            $lang->search->onMenuBar
        ),
        btn(
            setClass('w-1/12 primary'),
            set::btnType('submit'),
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

render();
