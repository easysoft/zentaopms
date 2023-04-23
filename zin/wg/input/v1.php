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
        'autocomplete?: bool=false',
        'disabled?: bool',
    ];

    static $defaultProps =
    [
        'type' => 'text',
        'class' => 'form-control',
    ];

    protected function build()
    {
        $props    = $this->props->skip('required');
        $required = $this->prop('required');
        if(!$this->hasProp('id') && isset($props['name'])) $props['id'] = $props['name'];
        if(is_bool($props['autocomplete'])) $props['autocomplete'] = $props['autocomplete'] ? 'on' : 'off';
        return h::input(set($props), $required ? setClass('is-required') : NULL);
    }
}
