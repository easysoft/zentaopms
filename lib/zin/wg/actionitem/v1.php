<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'dropdown' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'btngroup' . DS . 'v1.php';

class actionItem extends wg
{
    protected static array $defineProps = array(
        'name:string="action"',
        'type:string="item"',
        'outerTag:string="li"',
        'tagName:string="a"',
        'icon?:string',
        'text?:string',
        'textClass?: string',
        'url?:string',
        'target?:string',
        'active?:bool',
        'disabled?:bool',
        'selected?:bool',
        'trailingIcon?:string',
        'outerProps?:array',
        'outerClass?:string',
        'badge?:string|array|object',
        'props?:array',
        'dropdown?:array',
        'items?:array',
        'caret?:bool|string'
    );

    protected function buildDividerItem()
    {
        return setClass('divider');
    }

    protected function buildHeadingItem()
    {
        list($icon, $text, $trailingIcon, $textClass) = $this->prop(array('icon', 'text', 'trailingIcon', 'textClass'));

        return h::div
        (
            set($this->props->skip(array_keys(actionItem::definedPropsList()))),
            set($this->prop('props')),
            $icon ? icon($icon) : null,
            empty($text) ? null : span($text, setClass('text', $textClass)),
            $this->children(),
            $trailingIcon ? icon($trailingIcon) : null
        );
    }

    protected function buildDropdownItem()
    {
        list($dropdown, $items, $icon, $text, $trailingIcon, $active, $disabled, $badge, $props, $caret, $textClass, $trigger, $menu, $placement) = $this->prop(array('dropdown', 'items', 'icon', 'text', 'trailingIcon', 'active', 'disabled', 'badge', 'props', 'caret', 'textClass', 'trigger', 'menu', 'placement'));

        if(is_string($badge))
        {
            $badge = label($badge);
        }
        elseif(is_array($badge))
        {
            $badge = label(set($badge));
        }

        $dropdown = new dropdown
        (
            set::items($items),
            set::trigger($trigger),
            set::menu($menu),
            set::placement($placement),
            set($dropdown),
            h::a(
                setClass(array('active' => $active, 'disabled' => $disabled)),
                set($this->getRestProps()),
                set($props),
                $icon ? icon($icon) : null,
                span($text, setClass('text', $textClass)),
                $badge,
                $this->children(),
                $trailingIcon ? icon($trailingIcon) : null,
                $caret !== false ? h::span(setClass(is_string($caret) ? "caret-$caret" : 'caret')) : null
            )
        );
        return $dropdown;
    }

    protected function buildBtnItem()
    {
        return new btn($this->props->skip('tagName,type,name,outerTag,outerProps,props'), set($this->prop('props')),$this->children());
    }

    protected function buildCheckboxItem()
    {
        return new checkbox($this->props->skip('tagName,type,name,outerTag,outerProps,props'), set($this->prop('props')),$this->children());
    }

    protected function buildBtnGroupItem()
    {
        return new btnGroup($this->props->skip('tagName,type,name,outerTag,outerProps,props'), set($this->prop('props')),$this->children());
    }

    protected function buildItem()
    {
        $type = $this->prop('type');
        $methodName = "build{$type}Item";
        if(method_exists($this, $methodName)) return $this->$methodName();

        list($tagName, $icon, $text, $trailingIcon, $url, $target, $active, $disabled, $badge, $textClass, $selected) = $this->prop(array('tagName', 'icon', 'text', 'trailingIcon', 'url', 'target', 'active', 'disabled', 'badge', 'textClass', 'selected'));

        if(is_string($badge))     $badge = label($badge);
        else if(is_array($badge)) $badge = label(set($badge));

        return h::create
        (
            $tagName,
            set($tagName === 'a' ? array('href' => $url, 'target' => $target) : array('data-url' => $url, 'data-target' => $target)),
            setClass(array('active' => $active, 'disabled' => $disabled, 'selected' => $selected)),
            set($this->getRestProps()),
            set($this->prop('props')),
            $icon ? icon($icon) : null,
            span($text, setClass('text', $textClass)),
            $badge,
            $this->children(),
            $trailingIcon ? icon($trailingIcon) : null
        );
    }

    protected function build()
    {
        list($name, $type, $outerTag, $outerProps, $outerClass) = $this->prop(array('name', 'type', 'outerTag', 'outerProps', 'outerClass'));

        return h::create
        (
            $outerTag,
            setClass(($type !== 'item' && $type !== 'divider' && $type != 'text') ? 'nav-item' : '', "$name-$type", $outerClass),
            set($outerProps),
            $this->buildItem()
        );
    }
}
