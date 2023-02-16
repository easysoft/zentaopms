<?php

namespace zin;

class column extends wg
{
    static $defineProps = 'justify,align';

    protected function build()
    {
        $justify = empty($this->prop('justify')) ? 'start' : $this->prop('justify');
        $align = empty($this->prop('align')) ? 'start' : $this->prop('align');

        return div(
            setClass("col justify-$justify items-$align"),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
