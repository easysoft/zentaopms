<?php

namespace zin;

class label extends wg
{
    static $defineProps = 'text';

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
        return null;
    }

    public function build()
    {
        return span(
            $this->prop('text'),
            setClass(array(
                'label',
                $this->props->class->toStr(),
            )),
        );
    }
}
