<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'content' . DS . 'v1.php';

class datalist extends wg
{
    protected static array $defineProps = array
    (
        'items'      => '?array',
        'labelWidth' => '?int=68'
    );

    public static function getPageCSS(): ?string
    {
        return <<<CSS
        .datalist-item {display: flex; gap: 8px; padding: 6px 0;}
        .datalist-item-label {width: var(--datalist-label-width); color: var(--color-gray-500); flex: none; display: flex; align-items: center; justify-content: flex-end; white-space: nowrap; overflow: hidden; text-overflow: clip;}
        .datalist-item-content {flex: 1; display: flex; gap: 8px; align-items: center;}
        CSS;
    }

    public function onBuildItem($item): node|null
    {
        if($item === null) return null;

        if($item instanceof setting) $item = $item->toArray();
        if($item instanceof item)
        {
            $item = array_merge($item->props->toArray(), array('children' => $item->children()));
        }
        if($item instanceof node) return $item;

        $class        = isset($item['class']) ? $item['class'] : null;
        $label        = isset($item['label']) ? $item['label'] : null;
        $children     = isset($item['children']) ? $item['children'] : null;
        $content      = isset($item['content']) ? $item['content'] : null;
        $labelClass   = isset($item['labelClass']) ? $item['labelClass'] : null;
        $contentClass = isset($item['contentClass']) ? $item['contentClass'] : null;

        unset($item['class']);
        unset($item['label']);
        unset($item['children']);
        unset($item['content']);
        unset($item['labelClass']);
        unset($item['contentClass']);
        $content = isset($item['control']) ? new content(set($item), set::content($content)) : $content;

        return div
        (
            setClass('datalist-item', $class),
            div
            (
                setClass('datalist-item-label', $labelClass),
                set::title($label),
                $label
            ),
            div
            (
                setClass('datalist-item-content whitespace-pre-wrap', $contentClass),
                $content,
                $children
            )
        );
    }

    protected function buildItems()
    {
        $items     = $this->prop('items');
        $itemsView = array();
        if(is_array($itemsView))
        {
            foreach ($items as $key => $item)
            {
                if($item === '-')        $item = array('control'  => 'divider');
                elseif(is_string($item)) $item = array('children' => $item);

                if(is_array($item) && is_string($key))  $item['label'] = $key;

                $itemsView[] = $this->onBuildItem($item);
            }
        }

        return $itemsView;
    }

    protected function build()
    {
        return div
        (
            setClass('datalist break-all overflow-hidden text-clip'),
            setStyle('--datalist-label-width', $this->prop('labelWidth') . 'px'),
            set($this->getRestProps()),
            $this->buildItems(),
            $this->children()
        );
    }
}
