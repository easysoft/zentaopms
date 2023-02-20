<?php
namespace zin;

class formcell extends wg
{
    protected function build()
    {
        return div
        (
            setClass('form-cell'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
