<?php
namespace zin;

class formGrid extends wg
{
    protected function build()
    {
        return h::form
        (
            setClass('form-grid'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
