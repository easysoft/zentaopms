<?php
namespace zin;

class formInput extends wg
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
