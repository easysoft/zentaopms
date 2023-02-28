<?php
namespace zin;

class toolbar extends wg
{
    protected static $defineProps = 'items?:array';

    protected function onBuildItem($item)
    {
        if (isset($item['type']) && $item['type'] === 'divider') return div()->addClass('toolbar-divider');

        if(!($item instanceof item)) $item = item(set($item));
        return btn(
            setClass('toolbar-item'),
            inherit($item)
        );
    }

    protected function build()
    {
        $items = $this->prop('items');
        return div(
            setClass('toolbar'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            $this->children()
        );
    }
}
