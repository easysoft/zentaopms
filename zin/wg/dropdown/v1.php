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
            $li = h::li();
            if($item === '|' or $item === 'divider') $li->addClass('menu-divider');
            else $li->addClass('menu-item')->append($item);

            return $li;
        }

        $li = h::li()->addClass('menu-item');

        /* Divider. */
        if(isset($item['text']) and ($item['text'] === '|' or $item['text'] === 'divider'))
        {
            $li->addClass('menu-divider');
            return $li;
        }

        $a = h::a();

        /* Avatar item. */
        if(isset($item['avatar']))
        {
            $div = h::div()->addClass('avatar')->addClass('avatar circle primary');
            $div->append(h::img(\zin\set('src', $item['avatar'])));
            $a->append($div);
        }
        if(isset($item['url']))  $a->prop('href', $item['url']);
        if(isset($item['icon'])) $a->append(h::i()->addClass("icon icon-{$item['icon']}"));
        if(isset($item['text'])) $a->append($item['text']);
        if(isset($item['attr']))
        {
            $attr = $item['attr'];
            foreach($attr as $key => $val)
            {
                if($key != 'class')
                {
                    $a->prop($key, $val);
                    continue;
                }

                if(strpos($val, 'switchTo') !== false)
                {
                    $li->addClass($val);
                    $a = $item['text'];
                }
                else $a->addClass($val);
            }
        }

        $li->append($a);

        return $li;
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
