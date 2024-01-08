<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'editor' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'radiolist' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'select' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'picker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'datepicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'timepicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'pripicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'severitypicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'colorpicker' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'colorinput' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'upload' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'modulepicker' . DS . 'v1.php';

class control extends wg
{
    protected static array $defineProps = array
    (
        'type?: string',         // 表单输入元素类型，值可以为：static, text, password, email, number, date, time, datetime, month, url, search, tel, color, picker, pri, severity, select, checkbox, radio, checkboxList, radioList, checkboxListInline, radioListInline, file, textarea
        'name: string',          // HTML name 属性
        'id?: string',           // HTML id 属性
        'value?: string',        // HTML value 属性
        'placeholder?: string',  // HTML placeholder 属性
        'readonly?: bool',       // HTML readonly 属性
        'required?: bool',       // 是否为必填项
        'disabled?: bool',       // 是否为禁用状态
        'items?: array'          // 表单输入元素子项数据
    );

    protected function created()
    {
        $name = $this->prop('name');
        if($this->prop('type') === 'static' && $name === null) $this->setProp('name', '');
        if(!$this->hasProp('id') && $this->prop('name') !== null)
        {
            $name = $this->prop('name');
            $id   = substr($name, -2) == '[]' ? substr($name, 0, - 2) : $name;
            $this->setProp('id', $id);
        }
        elseif($this->prop('id') === '')
        {
            $this->setProp('id', null);
        }
    }

    /**
     * Build control with static content.
     *
     * @return wg
     */
    protected function buildStatic(): wg
    {
        $name = $this->prop('name');
        return div
        (
            set::className('form-control-static'),
            set($this->props->skip(array('type', 'name', 'value', 'required', 'disabled', 'placeholder', 'items', 'required'))),
            $name ? set('data-name', $name) : null,
            $this->prop('value')
        );
    }

    protected function buildTextarea(): wg
    {
        return new textarea(set($this->props->skip('type')));
    }

    protected function buildInputControl(): wg
    {
        $controlProps = array();
        $allProps     = $this->props->skip(array('type', 'required', 'name'));
        $propsNames   = array_keys(inputControl::definedPropsList());

        foreach($propsNames as $propName)
        {
            if(!isset($allProps[$propName])) continue;

            $controlProps[$propName] = $allProps[$propName];
            unset($allProps[$propName]);
        }

        return new inputControl
        (
            set($controlProps),
            new input(set($allProps))
        );
    }

    protected function buildInputGroup(): wg
    {
        return new inputGroup
        (
            set($this->props->skip(array('type', 'required', 'name'))),
        );
    }

    protected function buildCheckbox(): wg
    {
        if($this->hasProp('items')) return $this->buildCheckList();
        return new checkList
        (
            new checkbox(set($this->props->skip('type')))
        );
    }

    protected function buildCheckList(): wg
    {
        return new checkList
        (
            set($this->props->skip('type'))
        );
    }

    protected function buildRadioList(): wg
    {
        return new radioList
        (
            set($this->props->skip('type'))
        );
    }

    protected function buildCheckListInline(): wg
    {
        return new checkList
        (
            set::inline(true),
            set($this->props->skip('type'))
        );
    }

    protected function buildRadioListInline(): wg
    {
        return new radioList
        (
            set::inline(true),
            set($this->props->skip('type'))
        );
    }

    protected function buildDate(): wg
    {
        return new datePicker(set($this->props->skip('type')));
    }

    protected function buildTime(): wg
    {
        return new timePicker(set($this->props->skip('type')));
    }

    protected function buildPri(): wg
    {
        return new priPicker(set($this->props->skip('type')));
    }

    protected function buildSeverity(): wg
    {
        return new severityPicker(set($this->props->skip('type')));
    }

    protected function buildColor(): wg
    {
        return new colorPicker(set($this->props->skip('type')));
    }

    protected function buildColorInput(): wg
    {
        return new colorInput(set($this->props->skip('type')));
    }

    protected function buildFiles(): wg
    {
        return new upload(set($this->props->skip('type')));
    }

    protected function build(): wg
    {
        $type = $this->prop('type');
        if(empty($type)) $type = $this->hasProp('items') ? 'picker' : 'text';

        $methodName = "build{$type}";
        if(method_exists($this, $methodName)) return $this->$methodName();

        $wgName = "\\zin\\$type";
        if(class_exists($wgName)) return new $wgName(set($this->props->skip('type')), $this->children());

        return new input(set($this->props));
    }
}
