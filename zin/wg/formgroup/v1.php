<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formcontrol' . DS . 'v1.php';

class formGroup extends wg
{
    protected static $defineProps = 'label?:string|bool, labelClass?: string, labelClass?: string, required?:bool, tip?: string, tipClass?: string|array, tipProps: array, $control?: array, width?: string';

    protected function build()
    {
        list($label, $labelClass, $labelProps, $required, $tip, $tipClass, $tipProps, $control, $width) = $this->prop(['label', 'labelClass', 'labelProps', 'required', 'tip', 'tipClass', 'tipProps', 'control', 'width']);

        return div
        (
            set::class('form-group', $required ? 'required' : NULL, $label === false ? 'no-label' : NULL),
            zui::width($width),
            set($this->getRestProps()),
            empty($label) ? null : new formLabel
            (
                set::class($labelClass),
                set::required($required),
                set($labelProps),
            ),
            empty($control) ? NULL : new formControl(set($control)),
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
