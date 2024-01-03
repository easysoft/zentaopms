<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';

class formGroup extends wg
{
    protected static array $defineProps = array(
        'name?: string',
        'label?: string|bool',
        'labelClass?: string',
        'labelProps?: string',
        'labelWidth?: int|string',
        'required?:bool|string="auto"',
        'tip?: string',
        'tipClass?: string|array',
        'tipProps?: array',
        'control?: array|string',
        'width?: string',
        'strong?: bool',
        'value?: string|array',
        'disabled?: bool',
        'readonly?: bool',
        'items?: array',
        'placeholder?: string'
    );

    protected function build(): wg
    {
        list($name, $label, $labelClass, $labelProps, $labelWidth, $required, $tip, $tipClass, $tipProps, $control, $width, $strong, $value, $disabled, $items, $placeholder, $readonly, $multiple, $id) = $this->prop(['name', 'label', 'labelClass', 'labelProps', 'labelWidth', 'required', 'tip', 'tipClass', 'tipProps', 'control', 'width', 'strong', 'value', 'disabled', 'items', 'placeholder', 'readonly', 'multiple', 'id']);

        if($required === 'auto') $required = isFieldRequired($name);

        if(is_string($control))                   $control = array('type' => $control, 'name' => $name);
        elseif(empty($control) && $name !== null) $control = array('name' => $name);

        if(!empty($control))
        {
            if($required !== null)    $control['required']    = $required;
            if($name !== null)        $control['name']        = $name;
            if($value !== null)       $control['value']       = $value;
            if($disabled !== null)    $control['disabled']    = $disabled;
            if($items !== null)       $control['items']       = $items;
            if($placeholder !== null) $control['placeholder'] = $placeholder;
            if($readonly !== null)    $control['readonly']    = $readonly;
            if($multiple !== null)    $control['multiple']    = $multiple;
            if($id)                   $control['id']          = '';
        }

        return div
        (
            set::className('form-group', $required ? 'required' : null, ($label === false || $label === null) ? 'no-label' : null, empty($width) ? null : 'grow-0'),
            zui::width($width),
            set($this->getRestProps()),
            setCssVar('form-horz-label-width', $labelWidth),
            empty($label) && $label !== '0' ? null : new formLabel
            (
                set::className($labelClass, $strong ? 'font-bold' : null),
                set::required($required),
                set($labelProps),
                $label
            ),
            empty($control) ? null : new control(set($control)),
            (isset($control['disabled']) && $control['disabled'] && isset($control['name']) && isset($control['value'])) ? h::input(set::type('hidden'), set::name($control['name']), set::value($control['value'])) : null,
            $this->children(),
            empty($tip) ? null : div
            (
                set::className($tipClass),
                set($tipProps),
                $tip
            )
        );
    }
}
