<?php
namespace zin;

class checkbox extends wg
{
    protected static $defineProps = 'text?:string,checked?:bool';

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
        $input = h::checkbox(set($this->props->skip(array_keys(static::getDefinedProps()))));
        if($this->prop('checked')) $input->setProp('checked', true);

        return h::label
        (
            setClass('checkbox'),
            $input,
            $this->prop('text')
        );
    }
}
