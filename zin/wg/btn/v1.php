<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

class btn extends wg
{
    static $defineProps = 'type?:string, icon?:string, text?:string, square?:bool, disabled?:bool, active?:bool, url?:string, target?:string, size?:string|number, trailingIcon?:string, caret?:string|bool, hint?:string, btnType?:string';

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    /**
     * @return builder
     */
    protected function build()
    {
        $props = $this->props->skip(array_keys(static::getDefinedProps()));

        $url           = $this->prop('url');
        $target        = $this->prop('target');

        if(empty($url))
        {
            $props['type']        = $this->prop('btnType');
            $props['data-url']    = $url;
            $props['data-target'] = $target;
        }
        else
        {
            $props['tagName'] = 'a';
            $props['href'] = $url;
            $props['target'] = $target;
        }
        $props['title'] = $this->prop('hint');

        $caret         = $this->prop('caret');
        $text          = $this->prop('text');
        $icon          = $this->prop('icon');
        $trailingIcon  = $this->prop('trailingIcon');
        $square        = $this->prop('square');
        $isEmptyText   = empty($text);
        $onlyCaret     = $isEmptyText && empty($icon) && empty($trailingIcon);

        $classList = array
        (
            'btn',
            $this->prop('type'),
            'disabled' => $this->prop('disabled'),
            'active' => $this->prop('active'),
            'btn-caret' => $onlyCaret,
            'square' => $square
        );

        $size = $this->prop('size');
        if(!empty($size)) $classList[] = "size-$size";

        $children      = array();
        if(!empty($icon))         $children[] = new icon($icon);
        if(!empty($text))         $children[] = h::span($text, setClass('text'));

        $children[] = parent::build();

        if(!empty($trailingIcon)) $children[] = new icon($trailingIcon);
        if(!empty($caret))        $children[] = h::span(setClass(is_string($caret) ? "caret-$caret" : 'caret'));

        return h::button(set($props), setClass($classList), $children);
    }
}
