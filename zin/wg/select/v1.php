<?php
namespace zin;

class select extends wg
{
    protected static $defineProps = 'items:array';

    public function onBuildItem($item)
    {
        $text = $item['text'];
        unset($item['text']);
        $item = array_filter($item, function($v) {return $v !== false;});
        if(!($item instanceof item)) $item = item(set($item));
        return h::option(inherit($item), $text);
    }

    protected function build()
    {
        $items = $this->prop('items');
        return h::select
        (
            setClass('form-control'),
            set($this->props->skip(array_keys(static::getDefinedProps()), true)),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : NULL,
            $this->children()
        );
    }
}
