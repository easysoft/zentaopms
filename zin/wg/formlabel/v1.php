<?php
namespace zin;

class formLabel extends wg
{
    protected static $defineProps = 'text?:string, required?:bool, for?: string';

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
        list($text, $required, $for) = $this->prop(['text', 'required', 'for']);
        return h::label
        (
            setClass('form-label', $required ? 'required' : NULL),
            set('for', $for),
            set($this->getRestProps()),
            $text,
            $this->children(),
        );
    }
}
