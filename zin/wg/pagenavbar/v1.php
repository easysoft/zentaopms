<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'zuinav' . DS . 'v1.php';

class pagenavbar extends \zin\wg\zuinav
{
    static $tag = 'nav';

    static $defaultProps = array('id' => 'navbar');
}
