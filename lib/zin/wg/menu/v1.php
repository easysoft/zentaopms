<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'actionitem' . DS . 'v1.php';

class menu extends wg
{
    protected static array $defineProps = array(
        'items?:array'
    );

    public function onBuildItem($item): wg
    {
        if(!($item instanceof item)) $item = item(set($item));
        return actionItem
        (
            set('name', 'menu'),
            set('outerClass', 'item'),
            inherit($item)
        );
    }

    /**
     * @return builder
     */
    protected function build(): wg
    {
        $items = $this->prop('items');
        return h::menu
        (
            setClass('menu'),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $this->prop('items')) : null,
            $this->children()
        );
    }
}
