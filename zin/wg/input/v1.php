<?php
namespace zin;

class input extends wg
{
    static $defineProps =
    [
        'type: string',
        'name: string',
        'id?: string',
        'class?: string',
        'value?: string',
        'required?: bool',
        'placeholder?: string',
        'autofocus?: bool',
        'autocomplete?: bool',
        'disabled?: bool',
    ];

    static $defaultProps =
    [
        'type' => 'text',
        'class' => 'form-control',
    ];

    protected function build()
    {
        return h::input(set($this->props));
    }
}
