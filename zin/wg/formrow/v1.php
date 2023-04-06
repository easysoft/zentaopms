<?php
namespace zin;

class formRow extends wg
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
