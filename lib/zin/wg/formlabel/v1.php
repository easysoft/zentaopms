<?php
declare(strict_types=1);
namespace zin;

class formLabel extends wg
{
    protected static array $defineProps = array(
        'text?:string',
        'required?:bool',
        'for?:string'
    );

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    protected function build(): wg
    {
        list($text, $required, $for) = $this->prop(['text', 'required', 'for']);
        return h::label
        (
            setClass('form-label', $required ? 'required' : null),
            set('for', $for),
            set($this->getRestProps()),
            html($text),
            $this->children(),
        );
    }
}
