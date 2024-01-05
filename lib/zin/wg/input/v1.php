<?php
declare(strict_types=1);
namespace zin;

class input extends wg
{
    protected static array $defineProps = array(
        'type: string',
        'name?: string',
        'id?: string',
        'class?: string',
        'value?: string',
        'required?: bool',
        'placeholder?: string',
        'autofocus?: bool',
        'autocomplete?: bool=false',
        'disabled?: bool'
    );

    protected static array $defaultProps = array
    (
        'type' => 'text',
        'class' => 'form-control'
    );

    protected function build(): wg
    {
        $props    = $this->props->skip('required');
        $required = $this->prop('required');
        if(!$this->hasProp('id') && isset($props['name'])) $props['id'] = $props['name'];
        if(empty($props['id'])) unset($props['id']);
        if(is_bool($props['autocomplete'])) $props['autocomplete'] = $props['autocomplete'] ? 'on' : 'off';
        return h::input(set($props), $required ? setClass('is-required') : null);
    }
}
