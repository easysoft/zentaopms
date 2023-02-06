<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

use \zin\core\h5;

class zuinav extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'nav');

    static $customProps = 'className,items,hasIcons,onRenderItem,afterRender';

    public $cssList = array(':root{--nav-active-color: white;}');

    protected function itemsWrapper()
    {
        return h5::menu()->addClass('nav');
    }

    protected function buildItem($item)
    {
        if ($item['type'] === 'divider') return h5::li()->addClass('nav-divider');

        $li = h5::li()->addClass('nav-item');
        $a = h5::a();
        if ($item['active'])
        {
            $a->addClass('active');
        }
        $url = $item['url'];
        if (!empty($url))
        {
            $a->prop('href', "$url");
        }
        $li->append($a);
        $icon = $item['icon'];
        if (!empty($icon))
        {
            $i = h5::i()->addClass("icon icon-$icon");
            $a->append($i);
        }
        $span = h5::span($item['text'])->addClass('text');
        $a->append($span);
        return $li;
    }

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        $jsRender = $this->prop('js-render');
        if ($jsRender)
        {
            $id = $this->prop('id');
            $this->props->remove('id');
            $builder->jsVar('options', $this->props->data);
            $builder->js(<<<END
            domReady(() => {
                const nav = new zui.Nav('#$id', options);
            });
            END);
        }
        return $builder;
    }
}
