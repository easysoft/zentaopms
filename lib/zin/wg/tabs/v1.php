<?php
namespace zin;

class tabs extends wg
{
    protected static $defineProps = array(
        /* Tabs direction: h - horizontal, v - vertical */
        'direction?:string="h"',
        'items:array',
        'activeID?string'
    );

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function getItemID(array $item): string
    {
        return isset($item['id']) ? $item['id'] : $this->gid;
    }

    protected function buildLabelView(string $id, array $item)
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

    protected function buildContentView(string $id, array $item)
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

    protected function buildTabHeader($labelViews)
    {
        if(empty($labelViews)) return null;

        $isVertical = $this->prop('direction') === 'v';
        return ul
        (
            setClass('nav nav-tabs'),
            $isVertical ? setClass('nav-stacked') : null,
            $labelViews
        );
    }

    protected function buildTabBody($contentViews)
    {
        if(empty($contentViews)) return null;

        return div
        (
            setClass('tab-content'),
            $contentViews
        );
    }

    protected function build()
    {
        $items = $this->prop('items');
        // $activeID  = $this->prop('activeID');
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

        /* There is no active item, then set index 0 to be actived. */
        // if(empty($activeID))
        // {
        //     $labelViews[0]->setProp('class', 'active');
        //     $contentViews[0]->setProp('class', 'active');
        // }

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
