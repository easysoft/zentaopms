<?php
namespace zin;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h.func.php';

class btn extends wg
{
    static $defineProps = 'type,icon,text,square,disabled,active,url,target,size,trailingIcon,caret,hint,btnType';

    public function addChild($child)
    {
        if(is_string($child) && !$this->props->has('text')) $this->props->set('text', $child);
        else parent::addChild($child);
    }

    /**
     * @return builder
     */
    protected function build($isPrint = false)
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

        $children[] = parent::build($isPrint);

        if(!empty($trailingIcon)) $children[] = new icon($trailingIcon);
        if(!empty($caret))        $children[] = h::span(setClass(is_string($caret) ? "caret-$caret" : 'caret'));

        return h::button(set($props), setClass($classList), $children);
    }
}
