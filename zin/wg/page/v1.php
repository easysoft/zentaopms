<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';

class page extends pagebase
{
    static $customProps = 'metas,title,bodyProps,zui';

    public function init()
    {
        parent::init();

        global $config;
        $this->setDefaultProps(array('zui' => true));
        if($this->prop('zui') && isset($config->zin->zuiPath))
        {
            $this->importJs($config->zin->zuiPath . 'zui.zentao.umd.cjs')
                ->importCss($config->zin->zuiPath . 'zui.zentao.css');
        }
    }
}
