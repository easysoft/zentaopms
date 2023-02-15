<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';

class page extends pagebase
{
    static $defineProps = array
    (
        'zui' => array('type' => 'bool', 'default' => true)
    );
}
