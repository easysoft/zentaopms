<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';

class page extends pagebase
{
    static $defaultProps = array('zui' => true);

    protected function created()
    {
        $this->setDefaultProps(array('title' => data('title')));
        parent::created();
    }
}
