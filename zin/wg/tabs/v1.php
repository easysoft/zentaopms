<?php
namespace zin;

class tabs extends wg
{
    protected static $defineProps = array
    (
        /* Tabs direction: h - horizontal, v - vertical */
        'direction?:string="h"',
        'items:array',
        'activeId?string'
    );

    protected function build()
    {
        $items     = $this->prop('items');
        $direction = $this->prop('direction');
        $activeId  = $this->prop('activeId');

        if(empty($items)) return null;

        $lables  = array();
        $content = array();
        $actived = false;
        foreach($items as $item)
        {
            /* Get ID. */
            $id = isset($item['id']) ? $item['id'] : '';
            if(empty($id)) $id = isset($item['label']) ? $item['label'] : '';
            if(empty($id)) $id = $this->gid;

            $active = isset($item['active']) ? !empty($item['active']) : null;
            if($active === null and !empty($activeId) and $activeId == $id) $active = true;
            if($active === true) $actived = true;

            $lables[] = h::li
            (
                setClass('nav-item'),
                $active === true ? setClass('active') : null,
                h::a
                (
                    set('data-toggle', 'tab'),
                    set('href', '#' . $id),
                    isset($item['label']) ? $item['label'] : null
                )
            );

            $content[] = h::div
            (
                setClass('tab-pane'),
                setId($id),
                $active === true ? setClass('active') : null,
                isset($item['data']) ? $item['data'] : null
            );
        }

        /* There is no active item, then set index 0 to be actived. */
        if(!$actived)
        {
            $l = $lables[0];
            $l->setProp('class', 'active');
            $lables[0] = $l;

            $c = $content[0];
            $c->setProp('class', 'active');
            $content[0] = $c;
        }

        return h::div
        (
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $direction == 'v' ? setClass('flex') : null,
            /* Tabs. */
            h::ul(
                setClass('nav nav-tabs'),
                $direction == 'v' ? setClass('nav-stacked') : null,
                $lables
            ),
            /* Content. */
            h::div
            (
                setClass('tab-content'),
                $content
            )
        );
    }
}
