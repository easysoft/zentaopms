<?php
namespace zin;

class formlabel extends wg
{
    protected static $defineProps = 'text?:string,required?:bool,auto?:bool';

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
        $style = array();
        if($this->prop('auto')) $style['width'] = 'auto !important';
        return h::label
        (
            setClass($classList),
            setStyle($style),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->prop('text')
        );
    }
}
