<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';

class control extends wg
{
    static $defineProps = array(
        'type?: string',         // 表单输入元素类型，值可以为：static, text, password, email, number, date, time, datetime, month, url, search, tel, color, picker, select, checkbox, radio, checkboxList, radioList, checkboxListInline, radioListInline, file, textarea
        'name: string',          // HTML name 属性
        'id?: string',           // HTML id 属性
        'value?: string',        // HTML value 属性
        'placeholder?: string',  // HTML placeholder 属性
        'required?: bool',       // 是否为必填项
        'disabled?: bool',       // 是否为禁用状态
        'items?: array'          // 表单输入元素子项数据
    );

    protected function created()
    {
        $this->setDefaultProps(array('id' => $this->prop('name')));
    }

    /**
     * Build control with static content.
     *
     * @return wg
     */
    protected function buildStatic(): wg
    {
        return div
        (
            set::class('form-control-static'),
            set($this->props->skip(array('type', 'name', 'value', 'required', 'disabled', 'placeholder', 'items'))),
            set('data-name', $this->prop('name')),
            $this->prop('value')
        );
    }

    protected function buildTextarea(): wg
    {
        return textarea(set($this->props->skip('type')));
    }

    protected function buildInputControl(): wg
    {
        $controlProps = array();
        $allProps     = $this->props->skip('type');
        $propsNames   = array_keys(inputControl::getDefinedProps());

        foreach($propsNames as $propName)
        {
            if(!isset($allProps[$propName])) continue;

            $controlProps[$propName] = $allProps[$propName];
            unset($allProps[$propName]);
        }

        return inputControl
        (
            set($controlProps),
            input(set($allProps)),
        );
    }

    protected function buildCheckbox(): wg
    {
        if($this->hasProp('items')) return $this->buildCheckList();
        return checkList
        (
            checkbox(set($this->props->skip('type')))
        );
    }

    protected function buildCheckList(): wg
    {
        return checkList
        (
            set($this->props->skip('type'))
        );
    }

    protected function buildRadioList(): wg
    {
        return radioList
        (
            set($this->props->skip('type'))
        );
    }

    protected function buildCheckListInline(): wg
    {
        return checkList
        (
            set::inline(true),
            set($this->props->skip('type'))
        );
    }

    protected function buildRadioListInline(): wg
    {
        return radioList
        (
            set::inline(true),
            set($this->props->skip('type'))
        );
    }

    protected function build(): wg
    {
        $type = $this->prop('type');
        if(empty($type)) $type = $this->hasProp('items') ? 'picker' : 'text';

        $methodName = "build{$type}";
        if(method_exists($this, $methodName)) return $this->$methodName();

        $wgName = "\\zin\\$type";
        if(class_exists($wgName)) return new $wgName(set($this->props->skip('type')), $this->children());

        return input(set($this->props));
    }
}
