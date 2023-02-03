<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class toolbar extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('class' => 'toolbar', 'id' => 'toolbar');

    static $customProps = 'wrap,gap,items,btnProps,itemRender,beforeRender,afterRender,firstRender';

    protected function build($isPrint = false, $parent = null)
    {
        $builder = parent::build($isPrint, $parent);

        $id = $this->prop('id');
        $builder->jsVar('options', $this->props->data);
        $builder->js(<<<END
            domReady(() => {
                const toolbar = new zui.Toolbar('#$id', options);
            });
        END);
        return $builder;
    }
}
