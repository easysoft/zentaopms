<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'editor' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checklist' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'radiolist' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'select' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'inputcontrol' . DS . 'v1.php';

class control extends wg
{
    static $defineProps =
    [
        'type: string', // text, password, email, number, date, time, datetime, month, url, search, tel, color, picker, select, checkbox, radio, checkboxList, radioList, checkboxListInline, radioListInline, file, textarea
        'name: string',
        'id?: string',
        'value?: string',
        'required?: bool',
        'placeholder?: string',
        'disabled?: bool',
        'form?: string',
        'items?: array'
    ];

    protected function created()
    {
        $this->setDefaultProps(['id' => $this->prop('name')]);
    }

    protected function buildTextarea()
    {
        return new textarea(set($this->props->skip('type')));
    }

    protected function buildInputControl()
    {
        $controlProps = [];
        $allProps     = $this->props->skip('type');
        $propsNames   = array_keys(inputControl::getDefinedProps());

        foreach($propsNames as $propName)
        {
            if(isset($allProps[$propName]))
            {
                $controlProps[$propName] = $allProps[$propName];
                unset($allProps[$propName]);
            }
        }

        return new inputControl
        (
            set($controlProps),
            new input(set($allProps)),
        );
    }

    protected function buildCheckbox()
    {
        if($this->hasProp('items')) return $this->buildCheckList();
        return new checkList
        (
            new checkbox(set($this->props->skip('type')))
        );
    }

    protected function buildCheckList()
    {
        return new checkList
        (
            set($this->props->skip('type'))
        );
    }

    protected function buildRadioList()
    {
        return new radioList
        (
            set($this->props->skip('type'))
        );
    }

    protected function buildCheckListInline()
    {
        return new checkList
        (
            set::inline(true),
            set($this->props->skip('type'))
        );
    }

    protected function buildRadioListInline()
    {
        return new radioList
        (
            set::inline(true),
            set($this->props->skip('type'))
        );
    }

    protected function build()
    {
        $type = $this->prop('type');
        if(empty($type))
        {
            $type = $this->hasProp('items') ? 'select' : 'text';
        }

        $methodName = "build{$type}";
        if(method_exists($this, $methodName)) return $this->$methodName();

        $wgName = "\\zin\\$type";
        if(class_exists($wgName)) return new $wgName(set($this->props->skip('type')), $this->children());

        return new input(set($this->props));
    }
}
