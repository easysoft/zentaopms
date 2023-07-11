<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'actionitem' . DS . 'v1.php';

class nav extends wg
{
    protected static array $defineProps = array(
        'items?:array' // 使用数组指定导航中的每一项。
    );

    public function onBuildItem($item): wg
    {
        return new actionItem
        (
            set('name', 'nav'),
            $item instanceof item ? inherit($item) : set($item)
        );
    }

    protected function build(): wg
    {
        $items = $this->prop('items');
        return h::menu
        (
            setClass('nav'),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            $this->children()
        );
    }
}
