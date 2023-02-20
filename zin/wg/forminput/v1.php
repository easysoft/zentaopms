<?php
namespace zin;

class forminput extends wg
{
    protected function build()
    {
        return h::input
        (
            setClass('form-control'),
            set($this->props->skip(array_keys(static::getDefinedProps()), true))
        );
    }
}
