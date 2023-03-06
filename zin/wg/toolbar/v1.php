<?php
namespace zin;

class toolbar extends wg
{
    protected static $defineProps = 'items?:array';

    protected function onBuildItem($item)
    {
        $item = ($item instanceof item) ? $item : (item(set($item)));
        $type = $item->prop('type');

        if($type === 'divider') return div(setClass('toolbar-divider'));

        return btn
        (
            setClass('toolbar-item'),
            inherit($item)
        );
    }

    protected function build()
    {
        $items = $this->prop('items');
        return div
        (
            setClass('toolbar'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            $this->children()
        );
    }
}
