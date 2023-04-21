<?php
namespace zin;

class btnGroup extends wg
{
    static $defineProps = [
        'items?:array',
        'disabled?:bool',
        'size?:string',
    ];

    public function onBuildItem($item)
    {
        if(!($item instanceof item)) $item = item(set($item));
        return btn(inherit($item));
    }

    protected function build()
    {
        $items    = $this->prop('items');
        $disabled = $this->prop('disabled');
        $size     = $this->prop('size');

        $classList = 'btn-group';
        if(!empty($disabled)) $classList .= ' disabled';
        if(!empty($size))     $classList .= " size-$size";

        return div
        (
            setClass($classList),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : NULL,
            $this->children()
        );
    }
}
