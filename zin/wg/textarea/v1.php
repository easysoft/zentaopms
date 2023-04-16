<?php
namespace zin;

class textarea extends wg
{
    static $defineProps =
    [
        'name: string',
        'id?: string',
        'className?: string',
        'value?: string',
        'required?: bool',
        'placeholder?: string',
        'rows?: number',
        'cols?: number',
    ];

    static $defaultProps =
    [
        'className' => 'form-control',
        'rows' => 4
    ];

    protected function build()
    {
        return h::textarea(set($this->props));
    }
}
