<?php
namespace zin;

class radio extends wg
{
    protected static $defineProps = 'text?:string';

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    protected function build()
    {
        return h::label
        (
            setClass('radio'),
            h::radio(set($this->props->skip(array_keys(static::getDefinedProps()), true))),
            $this->prop('text')
        );
    }
}
