<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

use \zin\core\h5;

class toolbar extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'toolbar');

    static $customProps = 'wrap,gap,items,btnProps,itemRender,beforeRender,afterRender,firstRender';

    protected function itemsWrapper()
    {
        return h5::nav()->addClass('toolbar');
    }

    protected function buildItem($item)
    {
        if ($item['type'] === 'divider') return h5::div()->addClass('toolbar-divider');

        $button = h5::button()->addClass('btn toolbar-item ghost');
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
        $button->append($a);
        $icon = $item['icon'];
        if (!empty($icon))
        {
            $i = h5::i()->addClass("icon icon-$icon");
            $a->append($i);
        }
        $span = h5::span($item['text'])->addClass('text');
        $a->append($span);
        return $button;
    }

    protected function build($isPrint = false, $parent = null)
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
                const toolbar = new zui.Toolbar('#$id', options);
            });
            END);
        }
        return $builder;
    }
}
