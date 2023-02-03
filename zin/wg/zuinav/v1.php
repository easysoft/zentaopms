<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';

class zuinav extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'nav');

    static $customProps = 'className,items,hasIcons,onRenderItem,afterRender';

    /**
     * @return builder
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $builder = parent::build($isPrint, $parent);

        $id = $this->prop('id');
        $builder->jsVar('options', $this->props->data);
        $builder->js(<<<END
            domReady(() => {
                const nav = new zui.Nav('#$id', options);
            });
        END);
        return $builder;
    }
}
