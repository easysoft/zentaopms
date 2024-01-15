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

    protected static array $controlExtendProps = array('required', 'name', 'value', 'disabled', 'items', 'placeholder', 'readonly', 'multiple');

    protected bool $isHiddenField = false;

    protected function created()
    {
        if($this->hasProp('name') && $this->prop('required') == 'auto')
        {
            $this->setProp('required', isFieldRequired($this->prop('name'), $this->prop('requiredFields')));
        }
    }

    protected function buildLabel(): wg|directive
    {
        list($name, $label, $labelFor, $labelClass, $labelProps, $labelHint, $labelHintClass, $labelHintProps, $labelHintIcon, $labelActions, $labelActionsClass, $labelActionsProps, $checkbox, $required, $strong) = $this->prop(array('name', 'label', 'labelFor', 'labelClass', 'labelProps', 'labelHint', 'labelHintClass', 'labelHintProps', 'labelHintIcon', 'labelActions', 'labelActionsClass', 'labelActionsProps', 'checkbox', 'required', 'strong'));

        if(is_null($label) || $label === false) return setClass('no-label');

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

    protected function buildControl(): wg|array|null
    {
        $control = $this->prop('control');

        if($control instanceof wg) return $control;

        if(is_string($control))                             $control = array('control' => $control);
        elseif($control instanceof item)                    $control = $control->props->toJSON();
        elseif(is_object($control))                         $control = get_object_vars($control);
        elseif(is_null($control) && $this->hasProp('name')) $control = array();

        if(!is_array($control)) return null;

        if($this->hasProp('id') && !isset($control['id'])) $control['id'] = '';
        foreach(static::$controlExtendProps as $controlPropName)
        {
            $controlPropValue = $this->prop($controlPropName);
            if($controlPropValue !== null && !isset($control[$controlPropName])) $control[$controlPropName] = $controlPropValue;
        }

        if(isset($control['control']) && $control['control'] === 'hidden')
        {
            unset($control['control']);
            $this->isHiddenField = true;
            return new input(set::type('hidden'), set($control));
        }

        $controlView = new control(set($control));

        if((isset($control['disabled']) && $control['disabled'] && isset($control['name']) && isset($control['value'])))
        {
            return array($controlView, h::input
            (
                set::type('hidden'),
                set::name($control['name']),
                set::value($control['value'])
            ));
        }

        return $controlView;
    }

    protected function buildTip(): ?wg
    {
        list($tip, $tipClass, $tipProps) = $this->prop(array('tip', 'tipClass', 'tipProps'));
        if(empty($tip)) return null;

        return div
        (
            setClass('form-tip', $tipClass),
            set($tipProps),
            $tip
        );
    }

    protected function build(): wg
    {
        list($name, $labelWidth, $required, $width, $id, $hidden, $foldable, $pinned, $children) = $this->prop(array('name', 'labelWidth', 'required', 'width', 'id', 'hidden', 'foldable', 'pinned', 'children'));

        $control = $this->buildControl();
        if($this->isHiddenField) return $control;

        return div
        (
            setClass('form-group', array('required' => $required, 'hidden' => $hidden, 'is-foldable' => $foldable, 'is-pinned' => $pinned)),
            zui::width($width),
            setID($id),
            setData('name', $name),
            setCssVar('form-horz-label-width', $labelWidth),
            set($this->getRestProps()),
            $this->buildLabel(),
            $control,
            $children,
            $this->children(),
            $this->buildTip()
        );
    }
}
