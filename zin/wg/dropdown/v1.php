<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

use \zin\core\h5;

class dropdown extends \zin\core\wg
{
    static $tag = 'menu';

    static $defaultProps = array('class' => 'dropdown-menu menu');

    static $customProps = 'items';

    protected function buildItem($item)
    {
        if(is_string($item))
        {
            $li = h5::li();
            if($item === '|' or $item === 'divider') $li->addClass('menu-divider');
            else $li->addClass('menu-item')->append($item);

            return $li;
        }

        $li = h5::li()->addClass('menu-item');

        /* Divider. */
        if(isset($item['text']) and ($item['text'] === '|' or $item['text'] === 'divider'))
        {
            $li->addClass('menu-divider');
            return $li;
        }

        $a = h5::a();

        /* Avatar item. */
        if(isset($item['avatar']))
        {
            $div = h5::div()->addClass('avatar')->addClass('avatar circle primary');
            $div->append(h5::img(\zin\set('src', $item['avatar'])));
            $a->append($div);
        }
        if(isset($item['url']))  $a->prop('href', $item['url']);
        if(isset($item['icon'])) $a->append(h5::i()->addClass("icon icon-{$item['icon']}"));
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
}
