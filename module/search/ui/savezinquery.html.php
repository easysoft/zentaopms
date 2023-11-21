<?php
namespace zin;

if(isInModal())
{
    set::size('sm');
    set::title($lang->search->saveCondition);
    set::bodyClass('pb-7');
}

formBase
(
    set::actions(false),
    set::action($_SERVER['REQUEST_URI']),
    row
    (
        setClass('items-start gap-4 pt-1'),
        formGroup
        (
            setClass('flex-auto'),
            inputGroup
            (
                input
                (
                    set::name('title'),
                    set::type('text'),
                    set::placeholder($lang->search->setCondName)
                ),
                inputGroupAddon
                (
                    checkbox
                    (
                        set::name('common'),
                        set::value(1),
                        $lang->search->setCommon
                    )
                ),
                $onMenuBar == 'yes' ? inputGroupAddon
                (
                    checkbox
                    (
                        set::name('onMenuBar'),
                        $lang->search->onMenuBar
                    )
                ) : null
            )
        ),
        btn
        (
            set::type('primary'),
            set::btnType('submit'),
            set::className('btn-wide'),
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
