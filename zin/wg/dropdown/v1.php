<?php
namespace zin;

class dropdown extends wg
{
    protected static $defineProps = array
    (
        'class?:string="dropdown-menu menu"',
        'id?:string'
    );

    private $skipProps = array('items');

    protected function buildItem($item)
    {
        if(is_string($item))
        {
            return h::li
            (
                ($item === '|' or $item === 'divider') ? null : $item,
                ($item === '|' or $item === 'divider') ? setClass('menu-divider') : setClass('menu-item')
            );
        }

        /* Divider. */
        if(isset($item['text']) and ($item['text'] === '|' or $item['text'] === 'divider')) return h::li(setClass('menu-divider'));

        $classes = array('menu-item');
        $props   = array();

        if(isset($item['url'])) $props['href'] = $item['url'];
        if(isset($item['attr']))
        {
            $attr = $item['attr'];
            foreach($attr as $key => $val)
            {
                if($key != 'class')
                {
                    $props[$key] = $val;
                    continue;
                }

                /* switchTo with different bussiness logic. */
                if(strpos($val, 'switchTo') !== false) $classes[] = $val;
                else $classes[] = $val;
            }
        }

        return h::li
        (
            setClass($classes),
            h::a
            (
                set($props),
                (!isset($item['avatar']) or empty($item['avatar'])) ? null : h::div
                (
                    setClass('avatar circle primary'),
                    h::img(set('src', $item['avatar']))
                ),
                (!isset($item['icon']) or empty($item['icon'])) ? null : h::i(setClass("icon icon-{$item['icon']}")),
                (!isset($item['text']) or empty($item['text'])) ? null : $item['text'],
            )
        );
    }

    protected function build()
    {
        $items = $this->prop('items');

        return h::menu
        (
            set($this->props->skip($this->skipProps)),
            is_array($items) ? array_map(array($this, 'buildItem'), $items) : null,
            $this->children()
        );
    }
}
