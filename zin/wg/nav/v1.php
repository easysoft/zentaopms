<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'actionitem' . DS . 'v1.php';

class nav extends wg
{
    static $defineProps = 'items?:array';

    public function onBuildItem($item)
    {
        if(!($item instanceof item)) $item = item($item);
        return actionItem
        (
            set('name', 'nav'),
            inherit($item)
        );
    }

    /**
     * @return builder
     */
    protected function build()
    {
        $items = $this->prop('items');
        return h::nav
        (
            setClass('nav'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $this->prop('items')) : NULL,
            $this->children(),
        );
    }
}
