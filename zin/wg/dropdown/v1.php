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

        $a = h5::a();
        if(isset($item['url'])) $a->prop('href', $item['url']);
        if(isset($item['icon'])) $a->append(h5::i()->addClass("icon icon-{$item['icon']}"));
        if(isset($item['text'])) $a->append($item['text']);

        $li = h5::li()->addClass('menu-item');
        $li->append($a);

        return $li;
    }
}
