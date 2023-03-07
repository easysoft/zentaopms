<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'actionitem' . DS . 'v1.php';

class nav extends wg
{
    static $defineProps = 'items?:array';

    public function onBuildItem($item)
    {
        return new actionItem
        (
            set('name', 'nav'),
            $item instanceof item ? inherit($item) : set($item)
        );
    }

    /**
     * @return builder
     */
    protected function build()
    {
        $items = $this->prop('items');
        return h::menu
        (
            setClass('nav'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : NULL,
            $this->children()
        );
    }
}
