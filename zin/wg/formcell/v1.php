<?php
namespace zin;

class formCell extends wg
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
