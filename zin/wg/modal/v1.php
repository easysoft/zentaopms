<?php
namespace zin;

class modal extends wg
{
    static $defineProps = array(
        'title?:string',
        'type?:string="a"',
        'class?:string="btn"'
    );

    protected function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->setProp('text', $child);
            return false;
        }

        return $child;
    }

    private function button()
    {
        $btn = null;
        if($this->prop('type') == 'a')
        {
            $btn = h::a
            (
                $this->prop('text'),
                set($this->props->pick(array('class'))),
                set('data-toggle', 'modal'),
                set('href', '#' . $this->prop('id'))
            );
        }
        elseif($this->prop('type') == 'button')
        {
            $btn = h::button
            (
                $this->prop('text'),
                set($this->props->pick(array('class'))),
                set('data-toggle', 'modal'),
                set('data-target', '#' . $this->prop('id'))
            );
        }

        return $btn;
    }

    protected function build()
    {

        return array
        (
            $this->button(),
            h::div
            (
                setClass('modal'),
                set($this->props->skip(array_keys(static::getDefinedProps()))),
                h::div
                (
                    setClass('modal-dialog'),
                    h::div
                    (
                        setClass('modal-content'),
                        /* Header. */
                        h::div
                        (
                            setClass('modal-header'),
                            h::div
                            (
                                setClass('modal-title'),
                                $this->prop('title')
                            ),
                            /* Close button. */
                            btn
                            (
                                setClass('btn square ghost'),
                                set('data-dismiss', 'modal'),
                                h::span(setClass('close'))
                            )
                        ),
                        /* Body. */
                        h::div
                        (
                            setClass('modal-body'),
                            !isset($this->blocks['body']) ? null : $this->blocks['body']
                        ),
                        /* Footer. */
                        h::div
                        (
                            setClass('modal-footer'),
                            !isset($this->blocks['footer']) ? null : $this->blocks['footer']
                        )
                    )
                )
            )
        );
    }
}
