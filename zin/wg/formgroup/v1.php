<?php
namespace zin;

class formGroup extends wg
{
    protected static $defineProps = 'label?:string';

    protected function build()
    {
        $label = $this->prop('label');
        return div
        (
            setClass('form-group'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            empty($label) ? null : formLabel(inherit(item(set($label)))),
            $this->children()
        );
    }
}
