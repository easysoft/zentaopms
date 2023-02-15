<?php
namespace zin;

class actionItem extends wg
{
    static $defineProps = 'name:string="action", type:string="item", outerTag:string="li", tagName:string="a", icon?:string, text?:string, url?:string, target?:string, active?:bool, disabled?:bool, trailingIcon?:string, outerProps?:array';

    protected function buildItem()
    {
        list($tagName, $icon, $text, $trailingIcon) = $this->prop('tagName', 'icon', 'text', 'trailingIcon');
        return h::create
        (
            $tagName,
            set($text),
            set($this->props->skip(array_keys(actionItem::getDefinedProps()))),
            $icon ? icon($icon) : NULL,
            $this->children(),
            $trailingIcon ? icon($trailingIcon) : NULL,
        );
    }

    protected function build()
    {
        list($name, $type, $outerTag, $tagName, $url, $target, $active, $disabled, $outerProps) = $this->prop('name', 'type', 'outerTag', 'tagName', 'url', 'target', 'active', 'disabled', 'outerProps');

        return h::create
        (
            $outerTag,
            set($tagName === 'a' ? array('href' => $url, 'target' => $target) : array('data-url' => $url, 'data-target' => $target)),
            setClass("$name-$type"),
            setClass(array('active' => $active, 'disabled' => $disabled)),
            set($outerProps),
            $this->buildItem()
        );
    }
}
