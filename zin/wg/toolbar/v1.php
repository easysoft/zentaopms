<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

use \zin\core\h5;

class toolbar extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('class' => 'toolbar');

    static $customProps = 'wrap,gap,items,btnProps,itemRender,beforeRender,afterRender,firstRender';

    static function create($props)
    {
        $toolbar = new toolbar();
        foreach($props as $key => $value) $toolbar->prop($key, $value);
        return $toolbar;
    }

    protected function buildItem($item)
    {
        if (isset($item['type']) && $item['type'] === 'divider') return h5::div()->addClass('toolbar-divider');

        return btn::create($item)->addClass('toolbar-item ghost');
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
