<?php
namespace zin;

class input extends wg
{
    static $defineProps =
    [
        'type: string',
        'name: string',
        'id?: string',
        'className?: string',
        'value?: string',
        'required?: bool',
        'placeholder?: string',
    ];

    static $defaultProps =
    [
        'type' => 'text',
        'className' => 'form-control',
    ];

    protected function build()
    {
        return h::input(set($this->props));
    }
}
