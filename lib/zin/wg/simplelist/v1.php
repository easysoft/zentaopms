<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'listitem' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'content' . DS . 'v1.php';

class simpleList extends wg
{
    public bool $hasIcons    = false;
    public bool $hasCheckbox = false;

    protected static array $defineProps = array
    (
        'items'        => '?array',
        'tagName'      => '?string="ul"',
        'divider'      => '?bool',        // 是否显示分割线。
        'border'       => '?bool',        // 是否显示边框。
        'hover'        => '?bool=true',   // 是否有鼠标悬停效果。
        'compact'      => '?bool=true',   // 是否显示为紧凑模式。
        'onRenderItem' => '?callable',    // 渲染条目的回调函数。
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .simple-list.has-border {border: 1px solid var(--color-border);}
        .simple-list.has-border .list-item {padding-left: 8px; padding-right: 8px;}
        .simple-list.has-divider * + .list-item {border-top: 1px solid var(--color-border);}
        .simple-list.has-hover .list-item {transition: .2s background-color;}
        .simple-list.has-hover .list-item:hover {background-color: var(--state-color);}
        .simple-list.is-loose .list-item {padding-top: 2px; padding-bottom: 2px;}
        CSS;
    }

    public bool $isH5List = true;

    public function onBuildItem($item): node|null
    {
        if($item === null) return null;

        if($item instanceof item)
        {
            $item = array_merge($item->props->toArray(), array('children' => $item->children()));
        }
        if($item instanceof node) return $item;

        if(isset($item['control']))
        {
            return new content(set($item));
        }

        $type = isset($item['type']) ? $item['type'] : 'item';
        if($type === 'divider')
        {
            return div(setClass('divider list-divider item'));
        }

        if(isset($item['icon']) && $item['icon'] !== null) $this->hasIcons = true;
        if(isset($item['checked']) && $item['checked'] !== null) $this->hasCheckbox = true;

        return new listItem
        (
            $this->isH5List ? set::tagName('li') : null,
            setClass("list-$type"),
            set($item)
        );
    }

    protected function buildItems()
    {
        $items         = $this->prop('items');
        $onRenderItem  = $this->prop('onRenderItem');
        $itemsView     = array();

        if(is_array($itemsView))
        {
            foreach ($items as $key => $item)
            {
                if(is_callable($onRenderItem)) $item = $onRenderItem($item, $key);
                if(is_string($item)) $item = array('title' => $item);
                if(is_array($item) && is_string($key))  $item['title'] = $key;

                $itemsView[] = $this->onBuildItem($item);
            }
        }

        return $itemsView;
    }

    protected function build()
    {
        $tagName = $this->prop('tagName');
        $divider = $this->prop('divider');
        $border  = $this->prop('border');
        $this->isH5List = $tagName === 'ul' || $tagName === 'ol';

        $items = $this->buildItems();

        return h::$tagName
        (
            setClass('list simple-list', array('has-icons' => $this->hasIcons, 'has-checkbox' => $this->hasCheckbox, 'has-divider' => $divider, 'has-border' => $border, 'has-hover' => $this->prop('hover')), $this->prop('compact') ? 'is-compact' : 'is-loose'),
            set($this->getRestProps()),
            $items,
            $this->children()
        );
    }
}
