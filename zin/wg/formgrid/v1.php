<?php
namespace zin;

class formgrid extends wg
{
    protected function build()
    {
        return div
        (
            setClass('form-grid'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
