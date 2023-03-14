<?php

namespace zin;

class label extends wg
{
    static $defineProps = 'text?:string';

    public function build()
    {
        return span
        (
            setClass('label'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->prop('text'),
            $this->children()
        );
    }
}
