<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'actionitem' . DS . 'v1.php';

class nav extends wg
{
    protected static array $defineProps = array(
        'stacked?: bool',    // 是否为垂直模式。
        'justified?: bool',  // 是否为自适应模式。
        'compact?: bool',    // 是否为紧凑模式。
        'type?: string',     // 导航类型，包括 primary, tabs, secondary，pills
        'items?:array'       // 使用数组指定导航中的每一项。
    );

    public function onBuildItem($item): node
    {
        return new actionItem
        (
            set('name', 'nav'),
            set('outerClass', 'item'),
            $item instanceof item ? inherit($item) : set($item)
        );
    }

    protected function build()
    {
        list($items, $type, $stacked, $justified, $compact) = $this->prop(array('items', 'type', 'stacked', 'justified', 'compact'));
        return h::menu
        (
            setClass('nav', $type ? "nav-$type" : null, $stacked ? 'nav-stacked' : '', $justified ? 'nav-justified' : '', $compact ? 'nav-compact' : ''),
            set($this->getRestProps()),
            is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : null,
            $this->children()
        );
    }
}
