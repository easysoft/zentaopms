<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';

class formGroup extends wg
{
    protected static $defineProps = array
    (
        'name?: string',
        'label?: string|bool',
        'labelClass?: string',
        'labelProps?: string',
        'required?:bool|string="auto"',
        'tip?: string',
        'tipClass?: string|array',
        'tipProps: array',
        'control?: array|string',
        'width?: string',
        'strong?: bool',
        'value?: string|array',
        'disabled?: bool',
        'items?: array',
        'placeholder?: string'
    );

    protected function build()
    {
        list($name, $label, $labelClass, $labelProps, $required, $tip, $tipClass, $tipProps, $control, $width, $strong, $value, $disabled, $items, $placeholder) = $this->prop(['name', 'label', 'labelClass', 'labelProps', 'required', 'tip', 'tipClass', 'tipProps', 'control', 'width', 'strong', 'value', 'disabled', 'items', 'placeholder']);

        if($required === 'auto') $required = isFieldRequired($name);

        if(is_string($control))                   $control = ['type' => $control, 'name' => $name];
        elseif(empty($control) && $name !== null) $control = ['name' => $name];

        if(!empty($control))
        {
            if($required !== null)    $control['required']    = $required;
            if($name !== null)        $control['name']        = $name;
            if($value !== null)       $control['value']       = $value;
            if($disabled !== null)    $control['disabled']    = $disabled;
            if($items !== null)       $control['items']       = $items;
            if($placeholder !== null) $control['placeholder'] = $placeholder;
        }

        return div
        (
            set::class('form-group', $required ? 'required' : null, ($label === false || $label === null) ? 'no-label' : null, empty($width) ? null : 'grow-0'),
            zui::width($width),
            set($this->getRestProps()),
            empty($label) ? null : new formLabel
            (
                set::class($labelClass, $strong ? 'font-bold' : null),
                set::required($required),
                set($labelProps),
                $label
            ),
            empty($control) ? null : new control(set($control)),
            (isset($control['disabled']) && $control['disabled'] && isset($control['name']) && isset($control['value'])) ? h::input(set::type('hidden'), set::name($control['name']), set::value($control['value'])) : null,
            $this->children(),
            empty($tip) ? null : div
            (
                set::class($tipClass),
                set($tipProps),
                $tip
            )
        );
    }
}
