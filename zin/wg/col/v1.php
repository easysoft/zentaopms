<?php

namespace zin;

class col extends wg
{
    static $defineProps = 'justify?:string,align?:string';

    protected function build()
    {
        $classList = 'col';
        list($justify, $align) = $this->prop(array('justify', 'align'));
        if(!empty($justify)) $classList .= ' justify-' . $justify;
        if(!empty($align))   $classList .= ' items-' . $align;

        return div
        (
            setClass($classList),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
