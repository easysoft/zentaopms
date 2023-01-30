<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';

class page extends pagebase
{
    static $defaultProps = array('zui' => true);

    public function init()
    {
        // $this->setDefaultProps(array('zui' => true));

        parent::init();
    }
}
