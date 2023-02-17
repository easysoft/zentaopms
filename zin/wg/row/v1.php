<?php
namespace zin;

class row extends wg
{
    static $defineProps = 'justify?:string, align?:string';

    protected function build()
    {
        list($justify, $align) = $this->prop(array('justify', 'align'));

        return div
        (
            setClass
            (
                'row',
                empty($justify) ? null : "justify-$justify",
                empty($align) ? null : "items-$align"
            ),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->children()
        );
    }
}
