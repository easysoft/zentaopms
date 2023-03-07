<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

class btn extends wg
{
    static $defineProps = 'icon?:string, text?:string, square?:bool, disabled?:bool, active?:bool, url?:string, target?:string, size?:string|number, trailingIcon?:string, caret?:string|bool, hint?:string, type?:string';

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
            $props['type'] = $this->prop('type', 'button');
            if(!isset($props['data-url'])) $props['data-url']    = $url;
            if(!isset($props['data-target'])) $props['data-target'] = $target;
        }
        else
        {
            $props['tagName'] = 'a';
            if(!isset($props['href']))   $props['href'] = $url;
            if(!isset($props['target'])) $props['target'] = $target;
        }
        $props['title'] = $this->prop('hint');

        $caret         = $this->prop('caret');
        $text          = $this->prop('text');
        $icon          = $this->prop('icon');
        $trailingIcon  = $this->prop('trailingIcon');
        $square        = $this->prop('square');
        $isEmptyText   = empty($text);
        $onlyCaret     = $isEmptyText && !empty($caret) && empty($icon) && empty($trailingIcon);

        $classList = array
        (
            'disabled' => $this->prop('disabled'),
            'active' => $this->prop('active'),
            'btn-caret' => $onlyCaret,
            'square' => $square
        );

        $size = $this->prop('size');
        if(!empty($size)) $classList[] = "size-$size";

        $children      = array();
        if(!empty($icon)) $children[] = new icon($icon);
        if(!empty($text)) $children[] = h::span($text, setClass('text'));

        $children[] = parent::build();

        if(!empty($trailingIcon)) $children[] = new icon($trailingIcon);
        if(!empty($caret))        $children[] = h::span(setClass(is_string($caret) ? "caret-$caret" : 'caret'));

        return h::button
        (
            setClass('btn'),
            set($props),
            setClass($classList),
            $children
        );
    }
}
