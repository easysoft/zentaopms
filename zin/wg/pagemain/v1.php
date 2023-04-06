<?php
namespace zin;

class pageMain extends wg
{
    protected function build()
    {
        return div
        (
            setId('main'),
            div
            (
                setClass('container'),
                set($this->props->skip(array_keys(static::getDefinedProps()))),
                $this->children()
            )
        );
    }
}
