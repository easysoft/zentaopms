<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';

class formGroup extends wg
{
    protected static $defineProps = 'name?: string, label?:string|bool, labelClass?: string, labelClass?: string, required?:bool|string="auto", tip?: string, tipClass?: string|array, tipProps: array, control?: array|string, width?: string, strong?: bool, value?: string|array, disabled?: bool, options?: array';

    protected function build()
    {
        list($name, $label, $labelClass, $labelProps, $required, $tip, $tipClass, $tipProps, $control, $width, $strong, $value, $disabled, $options) = $this->prop(['name', 'label', 'labelClass', 'labelProps', 'required', 'tip', 'tipClass', 'tipProps', 'control', 'width', 'strong', 'value', 'disabled', 'options']);

        if($required === 'auto') $required = isFieldRequired($name);

        if(is_string($control)) $control = ['type' => $control];
        elseif(empty($control)) $control = [];

        if($required !== NULL)  $control['required'] = $required;
        if($name !== NULL)      $control['name']     = $name;
        if($value !== NULL)     $control['value']    = $value;
        if($disabled !== NULL)  $control['disabled'] = $disabled;
        if($options !== NULL)   $control['options']  = $options;

        return div
        (
            set::class('form-group', $required ? 'required' : NULL, $label === false ? 'no-label' : NULL, empty($width) ? NULL : 'grow-0'),
            zui::width($width),
            set($this->getRestProps()),
            empty($label) ? null : new formLabel
            (
                set::class($labelClass, $strong ? 'font-bold' : NULL),
                set::required($required),
                set($labelProps),
                $label
            ),
            empty($control) ? NULL : new control(set($control)),
            (isset($control['disabled']) && $control['disabled'] && isset($control['name']) && isset($control['value'])) ? h::input(set::type('hidden'), set::name($control['name']), set::value($control['value'])) : NULL,
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
