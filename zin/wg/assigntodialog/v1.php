<?php
namespace zin;

class assigntoDialog extends wg
{
    protected static $defineProps = array
    (
        'title:string',
        'assignID:string',
        'assignedTo:array',
        'mailto:array',
        'action:string',
        'useLeft?:bool=false',
        'useMailto?:bool=false',
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        global $lang;
        $useLeft   = $this->prop('useLeft');
        $useMailto = $this->prop('useMailto');

        return div
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
                        label($this->prop('assignID')),
                        div
                        (
                            setClass('modal-title'),
                            $this->prop('title'),
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
                            set::action($this->prop('action')),
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
                                        set::items($this->prop('assignedTo')),
                                    ),
                                )
                            ),
                            $useLeft === true
                                ? formGroup
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
                                                '小时'
                                            )
                                        )
                                    )
                                )
                                : null,
                            $useMailto === true
                                ? formGroup
                                (
                                    formLabel($lang->bug->mailto),
                                    formCell
                                    (
                                        select
                                        (
                                            set::name('mailto[]'),
                                            set::id('mailto'),
                                            set::items($this->prop('mailto'))
                                        ),
                                    )
                                )
                                : null,
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
    }
}
