<?php
declare(strict_types=1);
namespace zin;

class breadcrumb extends wg
{
    protected static array $defineProps = array
    (
        'items'      => '?array',
        'divider'    => '?string="U+003e"',
        'labelWidth' => '?int=76'
    );

    public function onBuildItem($item): node|null
    {
        if($item === null) return null;

        if($item instanceof item)
        {
            $item = array_merge($item->props->toArray(), array('children' => $item->children()));
        }
        if($item instanceof node) return $item;

        $active    = isset($item['active']) && $item['active'];
        $url       = isset($item['url']) ? $item['url'] : null;
        $text      = isset($item['text']) ? $item['text'] : null;
        $children  = isset($item['children']) ? $item['children'] : null;
        $liProps   = isset($item['liProps']) ? $item['liProps'] : null;
        $icon      = isset($item['icon']) ? $item['icon'] : null;

        unset($item['active']);
        unset($item['url']);
        unset($item['text']);
        unset($item['children']);
        unset($item['icon']);

        if(is_string($icon))   $icon = icon($icon);
        elseif(is_array($icon)) $icon = icon(set($icon));

        return h::li
        (
            $liProps ? set($liProps) : null,
            $active ? setClass('active') : null,
            h::a
            (
                set::href($url),
                set($item),
                $icon,
                $text,
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
                if(is_string($item)) $item = array('text' => $item);
                if(is_array($item) && is_string($key))  $item['text'] = $key;

                $itemsView[] = $this->onBuildItem($item);
            }
        }

        return $itemsView;
    }

    protected function build()
    {
        return h::ol
        (
            setClass('breadcrumb'),
            setStyle('--breadcrumb-divider', $this->prop('divider')),
            set($this->getRestProps()),
            $this->buildItems(),
            $this->children()
        );
    }
}
