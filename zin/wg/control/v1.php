<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'textarea' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'editor' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'select' . DS . 'v1.php';

class control extends wg
{
    static $defineProps =
    [
        'type: string', // text, password, email, number, date, time, datetime, month, url, search, tel, color, picker, select, checkbox, radio, file, textarea
        'name: string',
        'id?: string',
        'value?: string',
        'required?: bool',
        'placeholder?: string',
        'disabled?: bool',
        'form?: string',
        'options?: array'
    ];

    protected function created()
    {
        $this->setDefaultProps(['id' => $this->prop('name')]);
    }

    protected function buildTextarea()
    {
        return textarea(set($this->props->skip('type')));
    }

    protected function build()
    {
        $type = $this->prop('type');
        if(empty($type))
        {
            if($this->hasProp('options')) $type = 'select';
            else $type = 'text';
        }

        $methodName = "build{$type}Item";
        if(method_exists($this, $methodName)) return $this->$methodName();

        $wgName = "\\zin\\$type";
        if(class_exists($wgName)) return new $wgName(set($this->props->skip('type')), $this->children());

        return new input(set($this->props));
    }
}
