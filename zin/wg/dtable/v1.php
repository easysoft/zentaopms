<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

class dtable extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => '$GID');

    static $customProps = 'data,height,plugins,responsive';

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        $jsRender = $this->prop('js-render');
        $this->props->remove('js-render');
        if ($jsRender)
        {
            $id = $this->prop('id');
            $this->props->remove('id');
            $builder->jsVar('options', $this->props->data);
            $builder->js("console.log(options);");
            $builder->js("domReady(() => {const dtable = new zui.DTable('#$id', options);});");
        }
        return $builder;
    }
}
