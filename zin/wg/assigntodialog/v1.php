<?php
namespace zin;

class assigntodialog extends wg
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
                        formgrid
                        (
                            set::action($this->prop('action')),
                            set::method('POST'),
                            formgroup
                            (
                                formlabel($lang->assignedToAB),
                                formcell
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
                                ? formgroup
                                (
                                    formlabel($lang->task->left),
                                    formcell
                                    (
                                        div
                                        (
                                            setClass('input-control has-suffix'),
                                            forminput
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
                                ? formgroup
                                (
                                    formlabel($lang->bug->mailto),
                                    formcell
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
                            formgroup
                            (
                                formlabel($lang->comment),
                                formcell
                                (
                                    textarea
                                    (
                                        setClass('form-control'),
                                        set::name('comment'),
                                        set::id('comment')
                                    )
                                )
                            ),
                            formgroup
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
