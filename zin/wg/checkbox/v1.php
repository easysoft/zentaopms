<?php
namespace zin;

class checkbox extends wg
{
    protected static $defineProps = 'text?:string,checked?:bool';

    protected function build()
    {
        return h::label
        (
            setClass('checkbox'),
            h::checkbox
            (
                set('checked', $this->prop('checked')),
                set($this->props->skip(array_keys(static::getDefinedProps()))),
            ),
            $this->prop('text')
        );
    }
}
