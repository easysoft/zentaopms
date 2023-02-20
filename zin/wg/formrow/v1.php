<?php
namespace zin;

class formrow extends wg
{
    protected function build()
    {
        return div
        (
            setClass('form-row'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
