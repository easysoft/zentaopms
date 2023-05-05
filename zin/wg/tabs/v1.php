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

    protected function getItemID(array $item): string
    {
        return isset($item['id']) ? $item['id'] : $this->gid;
    }

    protected function buildLabelView(string $id, array $item)
    {
        $isActive = $this->prop('activeID') == $id;
        return li
        (
            setClass('nav-item'),
            $isActive ? setClass('active') : null,
            a
            (
                set('data-toggle', 'tab'),
                set::href("#$id"),
                $item['label']
            )
        );
    }

    protected function buildContentView(string $id, array $item)
    {
        $isActive = $this->prop('activeID') == $id;
        return div
        (
            setClass('tab-pane'),
            setID($id),
            $isActive ? setClass('active') : null,
            isset($item['data']) ? $item['data'] : null
        );
    }

    protected function build()
    {
        $items     = $this->prop('items');
        $direction = $this->prop('direction');
        $activeID  = $this->prop('activeID');

        if(empty($items)) return null;

        $labelViews  = array();
        $contentViews = array();
        foreach($items as $item)
        {
            $id = $this->getItemID($item);
            $labelViews[] = $this->buildLabelView($id, $item);
            $contentViews[] = $this->buildContentView($id, $item);
        }

        /* There is no active item, then set index 0 to be actived. */
        if(empty($activeID))
        {
            $labelViews[0]->setProp('class', 'active');
            $contentViews[0]->setProp('class', 'active');
        }

        return div
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $direction == 'v' ? setClass('flex') : null,
            /* Tabs. */
            ul
            (
                setClass('nav nav-tabs'),
                $direction == 'v' ? setClass('nav-stacked') : null,
                $labelViews
            ),
            /* Content. */
            div
            (
                setClass('tab-content'),
                $contentViews
            )
        );
    }
}
