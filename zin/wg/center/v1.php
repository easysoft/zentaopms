<?php

namespace zin;

class center extends wg
{
    protected function build()
    {
        return div
        (
            setClass("flex justify-center items-center"),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
