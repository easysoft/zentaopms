<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'dropdown' . DS . 'v1.php';

class actionItem extends wg
{
    static $defineProps = 'name:string="action", type:string="item", outerTag:string="li", tagName:string="a", icon?:string, text?:string, url?:string, target?:string, active?:bool, disabled?:bool, trailingIcon?:string, outerProps?:array';

    protected function buildDividerItem()
    {
        return null;
    }

    protected function buildHeadingItem()
    {
        list($icon, $text, $trailingIcon) = $this->prop(array('icon', 'text', 'trailingIcon'));

        return h::span
        (
            set($text),
            set($this->props->skip(array_keys(actionItem::getDefinedProps()))),
            $icon ? icon($icon) : NULL,
            $this->children(),
            $trailingIcon ? icon($trailingIcon) : NULL,
        );
    }

    protected function buildDropdownItem()
    {
        $dropdown = new dropdown($this->props->skip('tagName,type,name,outerTag,outerProps'),  $this->children());
        return $dropdown;
    }

    protected function buildBtnItem()
    {
        return new btn($this->props->skip('tagName,type,name,outerTag,outerProps'), $this->children());
    }

    protected function buildItem()
    {
        $type = $this->prop('type');
        $methodName = "build{$type}Item";
        if(method_exists($this, $methodName)) return $this->$methodName();

        list($tagName, $icon, $text, $trailingIcon, $url, $target, $active, $disabled) = $this->prop(array('tagName', 'icon', 'text', 'trailingIcon', 'url', 'target', 'active', 'disabled'));

        return h::create
        (
            $tagName,
            set($tagName === 'a' ? array('href' => $url, 'target' => $target) : array('data-url' => $url, 'data-target' => $target)),
            setClass(array('active' => $active, 'disabled' => $disabled)),
            set($this->props->skip(array_keys(actionItem::getDefinedProps()))),
            $icon ? icon($icon) : NULL,
            $text,
            $this->children(),
            $trailingIcon ? icon($trailingIcon) : NULL,
        );
    }

    protected function build()
    {
        list($name, $type, $outerTag, $outerProps) = $this->prop(array('name', 'type', 'outerTag', 'outerProps'));

        return h::create
        (
            $outerTag,
            setClass("$name-$type"),
            set($outerProps),
            $this->buildItem()
        );
    }
}
