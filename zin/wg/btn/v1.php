<?php
namespace zin;

class btn extends wg
{
    static $defineProps = array
    (
        'icon?:string',
        'text?:string',
        'square?:bool',
        'disabled?:bool',
        'active?:bool',
        'url?:string',
        'target?:string',
        'size?:string|number',
        'trailingIcon?:string',
        'caret?:string|bool',
        'hint?:string',
        'type?:string',
        'btnType?:string'
    );

    public function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->props->set('text', $child);
            return false;
        }
    }

    private function getProps()
    {
        $props = $this->props->skip(array_keys(static::getDefinedProps()));

        $url    = $this->prop('url');
        $target = $this->prop('target');

        if(empty($url))
        {
            $props['type'] = $this->prop('btnType') ?? 'button';
            if(!isset($props['data-url']))    $props['data-url']    = $url;
            if(!isset($props['data-target'])) $props['data-target'] = $target;
        }
        else
        {
            $props['tagName'] = 'a';
            if(!isset($props['href']))   $props['href'] = $url;
            if(!isset($props['target'])) $props['target'] = $target;
        }
        $props['title'] = $this->prop('hint');

        return $props;
    }

    private function getChildren()
    {
        $caret        = $this->prop('caret');
        $text         = $this->prop('text');
        $icon         = $this->prop('icon');
        $trailingIcon = $this->prop('trailingIcon');

        $children = array();
        if(!empty($icon)) $children[] = icon($icon);
        if(!empty($text)) $children[] = h::span($text, setClass('text'));
        $children[] = parent::build();
        if(!empty($trailingIcon)) $children[] = icon($trailingIcon);
        if(!empty($caret))        $children[] = h::span(setClass(is_string($caret) ? "caret-$caret" : 'caret'));

        return $children;
    }

    private function getClassList()
    {
        $url           = $this->prop('url');
        $type          = $this->prop('type');
        $caret         = $this->prop('caret');
        $text          = $this->prop('text');
        $icon          = $this->prop('icon');
        $trailingIcon  = $this->prop('trailingIcon');
        $onlyCaret     = empty($text) && !empty($caret) && empty($icon) && empty($trailingIcon);

        $classList = array
        (
            'btn'       => true,
            'disabled'  => $this->prop('disabled'),
            'active'    => $this->prop('active'),
            'btn-caret' => $onlyCaret,
            'square'    => $this->prop('square')
        );

        if(!empty($type))    $classList[$type] = true;
        elseif(!empty($url)) $classList['btn-default'] = true;

        $size = $this->prop('size');
        if(!empty($size)) $classList["size-$size"] = true;

        return $classList;
    }

    /**
     * @return builder
     */
    protected function build()
    {
        $props     = $this->getProps();
        $children  = $this->getChildren();
        $classList = $this->getClassList();

        return button
        (
            set($props),
            setClass($classList),
            $children
        );
    }
}
