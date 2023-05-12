<?php
declare(strict_types=1);
namespace zin;

class tabs extends wg
{
    protected static $defineProps = array(
        /* Tabs direction: h - horizontal, v - vertical */
        'direction?:string="h"',
        'items:array',
        'activeID?:string'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function getItemID(array $item): string
    {
        return isset($item['id']) ? $item['id'] : $this->gid;
    }

    protected function buildLabelView(string $id, array $item): wg|null
    {
        if(!isset($item['label'])) return null;
        $isActive = $this->prop('activeID') == $id || (isset($item['active']) && $item['active']);
        return li
        (
            setClass('nav-item'),
            $isActive ? setClass('active') : null,
            a
            (
                set('data-toggle', 'tab'),
                setClass('font-medium'),
                set::href("#$id"),
                $item['label']
            )
        );
    }

    protected function buildContentView(string $id, array $item): wg|null
    {
        if(!isset($item['data'])) return null;
        $isActive = $this->prop('activeID') == $id || (isset($item['active']) && $item['active']);
        return div
        (
            setClass('tab-pane'),
            setID($id),
            $isActive ? setClass('active') : null,
            $item['data']
        );
    }

    protected function buildTabHeader($labelViews): wg|null
    {
        if(empty($labelViews)) return null;

        $isVertical = $this->prop('direction') === 'v';
        return ul
        (
            setClass('nav nav-tabs gap-x-5'),
            $isVertical ? setClass('nav-stacked') : null,
            $labelViews
        );
    }

    protected function buildTabBody($contentViews): wg|null
    {
        if(empty($contentViews)) return null;

        return div
        (
            setClass('tab-content'),
            $contentViews
        );
    }

    protected function build(): wg
    {
        $items = $this->prop('items');
        $isVertical = $this->prop('direction') === 'v';

        if(empty($items))
        {
            return div
            (
                set($this->props->skip(array_keys(static::getDefinedProps()))),
                $isVertical ? setClass('flex') : null,

                $this->children()
            );
        }

        $labelViews  = array();
        $contentViews = array();
        foreach($items as $item)
        {
            $id = $this->getItemID($item);

            $labelView   = $this->buildLabelView($id, $item);
            $contentView = $this->buildContentView($id, $item);
            if(!empty($labelView))   $labelViews[]   = $labelView;
            if(!empty($contentView)) $contentViews[] = $contentView;

        }

        return div
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $isVertical ? setClass('flex') : null,

            $this->buildTabHeader($labelViews),
            $this->buildTabBody($contentViews),
            $this->children()
        );
    }
}
