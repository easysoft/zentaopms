<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class formGroup extends wg
{
    protected static array $defineProps = array(
        'name?: string',
        'label?: string|bool',
        'labelFor?: string',
        'labelClass?: string',
        'labelProps?: string',
        'labelWidth?: int|string',
        'labelHint?: string',
        'labelHintIcon?: string="help"',
        'labelHintClass?: string',
        'labelHintProps?: array',
        'labelActions?: array',
        'labelActionsClass?: string',
        'labelActionsProps?: array',
        'checkbox?: bool|array',
        'required?:bool|string="auto"',
        'requiredFields?: string',
        'tip?: string',
        'tipClass?: string|array',
        'tipProps?: array',
        'control?: array|string',
        'builder?: callable',
        'width?: string',
        'strong?: bool',
        'value?: string|array',
        'disabled?: bool',
        'readonly?: bool',
        'multiple?: bool',
        'hidden?: bool',
        'items?: array',
        'placeholder?: string',
        'foldable?: bool',
        'pinned?: bool',
        'children?: array|object'
    );

    protected function buildLabel(): ?wg
    {
        list($name, $label, $labelFor, $labelClass, $labelProps, $labelHint, $labelHintClass, $labelHintProps, $labelHintIcon, $labelActions, $labelActionsClass, $labelActionsProps, $checkbox, $required, $strong) = $this->prop(array('name', 'label', 'labelFor', 'labelClass', 'labelProps', 'labelHint', 'labelHintClass', 'labelHintProps', 'labelHintIcon', 'labelActions', 'labelActionsClass', 'labelActionsProps', 'checkbox', 'required', 'strong'));

        if(is_null($label)) return null;

        return new formLabel
        (
            set::className($labelClass, $strong ? 'font-bold' : null),
            set::required($required),
            set::hint($labelHint),
            set::hintIcon($labelHintIcon),
            set::hintClass($labelHintClass),
            set::hintProps($labelHintProps),
            set::actions($labelActions),
            set::actionsClass($labelActionsClass),
            set::actionsProps($labelActionsProps),
            set::checkbox($checkbox),
            set('for', is_null($labelFor) ? $name : $labelFor),
            set($labelProps),
            $label
        );
    }

    protected function build(): wg
    {
        list($name, $label, $labelWidth, $required, $requiredFields, $tip, $tipClass, $tipProps, $control, $width, $value, $disabled, $items, $placeholder, $readonly, $multiple, $id, $hidden, $foldable, $pinned, $children) = $this->prop(array('name', 'label', 'labelWidth', 'required', 'requiredFields', 'tip', 'tipClass', 'tipProps', 'control', 'width', 'value', 'disabled', 'items', 'placeholder', 'readonly', 'multiple', 'id', 'hidden', 'foldable', 'pinned', 'children'));

        if($required === 'auto') $required = isFieldRequired($name, $requiredFields);

        if(is_string($control))                   $control = array('type' => $control, 'name' => $name);
        elseif(empty($control) && $name !== null) $control = array('name' => $name);

        if(!empty($control))
        {
            if($required !== null && !isset($control['required']))       $control['required']    = $required;
            if($name !== null && !isset($control['name']))               $control['name']        = $name;
            if($value !== null && !isset($control['value']))             $control['value']       = $value;
            if($disabled !== null && !isset($control['disabled']))       $control['disabled']    = $disabled;
            if($items !== null && !isset($control['items']))             $control['items']       = $items;
            if($placeholder !== null && !isset($control['placeholder'])) $control['placeholder'] = $placeholder;
            if($readonly !== null && !isset($control['readonly']))       $control['readonly']    = $readonly;
            if($multiple !== null && !isset($control['multiple']))       $control['multiple']    = $multiple;
            if($id && !isset($control['id']))                            $control['id'] = '';

            if(isset($control['type']) && $control['type'] === 'hidden') return new input(set($control));
        }

        return div
        (
            setClass('form-group', array('required' => $required, 'no-label' => $label === false || $label === null, 'hidden' => $hidden, 'is-foldable' => $foldable, 'is-pinned' => $pinned)),
            zui::width($width),
            set($this->getRestProps()),
            setData('name', $name),
            setCssVar('form-horz-label-width', $labelWidth),
            $this->buildLabel(),
            empty($control) ? null : new control(set($control)),
            (isset($control['disabled']) && $control['disabled'] && isset($control['name']) && isset($control['value'])) ? h::input(set::type('hidden'), set::name($control['name']), set::value($control['value'])) : null,
            $children,
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
