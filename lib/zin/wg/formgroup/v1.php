<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'control' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

/**
 * 表单控件组部件。
 * Form control group widget.
 */
class formGroup extends wg
{
    protected static array $defineProps = array(
        'id?: string',                          // ID。
        'name?: string',                        // 字段名，可能影响到表单提交的域名称，如果是多个值的表单控件，可能需要将名称定义为 `key[]` 的形式。
        'label?: string|bool',                  // 标签文本。
        'labelFor?: string',                    // 标签的 for 属性。
        'labelClass?: string',                  // 标签的 class 属性。
        'labelProps?: string',                  // 标签的其它属性。
        'labelWidth?: int|string',              // 标签的宽度。
        'labelHint?: string',                   // 标签的提示文本。
        'labelHintIcon?: string="help"',        // 标签的提示图标。
        'labelHintClass?: string',              // 标签的提示 class 属性。
        'labelHintProps?: array',               // 标签的提示其它属性。
        'labelActions?: array',                 // 标签的操作按钮。
        'labelActionsClass?: string',           // 标签的操作按钮 class 属性。
        'labelActionsProps?: array',            // 标签的操作按钮其它属性。
        'checkbox?: bool|array',                // 标签的复选框属性定义。
        'required?:bool|string="auto"',         // 是否必填。
        'requiredFields?: string',              // 必填字段列表，例如 `'product,branch'`。
        'tip?: string',                         // 提示文本。
        'tipClass?: string|array',              // 提示 class 属性。
        'tipProps?: array',                     // 提示其它属性。
        'control?: array|string',               // 表单控件类型或控件属性定义。
        'width?: string',                       // 界面宽度。
        'strong?: bool',                        // 是否加粗。
        'value?: string|array',                 // 值。
        'disabled?: bool',                      // 是否禁用。
        'readonly?: bool',                      // 是否只读。
        'multiple?: bool',                      // 是否多选。
        'hidden?: bool',                        // 是否隐藏。
        'items?: array',                        // 选项列表。
        'placeholder?: string',                 // 占位符。
        'foldable?: bool',                      // 是否可折叠。
        'pinned?: bool',                        // 是否固定。
        'children?: array|object'               // 内部自定义内容。
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

        if(is_string($control))                   $control = array('control' => $control, 'name' => $name);
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
            if(isset($control['control']) && $control['control'] === 'hidden')
            {
                unset($control['control']);
                return new input(set::type('hidden'), set($control));
            }
        }

        return div
        (
            setClass('form-group', array('required' => $required, 'no-label' => $label === false || $label === null, 'hidden' => $hidden, 'is-foldable' => $foldable, 'is-pinned' => $pinned)),
            zui::width($width),
            setID($id),
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
                setClass('form-tip', $tipClass),
                set($tipProps),
                $tip
            )
        );
    }
}
