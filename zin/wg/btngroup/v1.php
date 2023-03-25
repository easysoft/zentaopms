<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class btnGroup extends wg
{
    static $defineProps = 'items?:array';

    public function onBuildItem($item)
    {
        if(!($item instanceof item)) $item = item(set($item));
        return btn(inherit($item));
    }

    protected function build()
    {
        $items = $this->prop('items');
        return div
        (
            setClass('btn-group'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : NULL,
            $this->children()
        );
    }
}
