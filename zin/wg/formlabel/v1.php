<?php
namespace zin;

class formlabel extends wg
{
    protected static $defineProps = 'text?:string,required?:bool';

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
        $classList = 'form-label';
        if($this->prop('required')) $classList .= ' required';
        return h::label
        (
            setClass($classList),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->prop('text')
        );
    }
}
