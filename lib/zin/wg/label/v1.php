<?php
namespace zin;

class label extends wg
{
    static $defineProps = 'text?:string';

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    public function build()
    {
        return span
        (
            setClass('label'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->prop('text'),
            $this->children()
        );
    }
}
