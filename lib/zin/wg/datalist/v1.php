<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'content' . DS . 'v1.php';

class datalist extends wg
{
    protected static array $defineProps = array
    (
        'items'      => '?array',
        'labelWidth' => '?int=76'
    );

    public function onBuildItem($item): node|null
    {
        if($item === null) return null;

        if($item instanceof setting) $item = $item->toArray();
        if($item instanceof item)
        {
            $item = array_merge($item->props->toArray(), array('children' => $item->children()));
        }
        if($item instanceof node) return $item;

        $label    = isset($item['label']) ? $item['label'] : null;
        $children = isset($item['children']) ? $item['children'] : null;
        $content  = null;

        unset($item['label']);
        unset($item['children']);
        $content = isset($item['control']) ? new content(set($item), $children) : $children;

        return div
        (
            setClass('datalist-item'),
            div
            (
                setClass('datalist-item-label'),
                $label
            ),
            div
            (
                setClass('datalist-item-content'),
                $content
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
            setClass('datalist'),
            setStyle('--datalist-label-width', $this->prop('labelWidth') . 'px'),
            set($this->getRestProps()),
            $this->buildItems(),
            $this->children()
        );
    }
}
