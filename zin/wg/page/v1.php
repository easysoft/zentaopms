<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(__DIR__) . DS . 'pagebase' . DS . 'v1.php';

class page extends pagebase
{
    static $defineProps = array
    (
        'metas' => array('type' => 'string|array', 'default' => array('<meta charset="utf-8">', '<meta http-equiv="X-UA-Compatible" content="IE=edge">', '<meta name="viewport" content="width=device-width, initial-scale=1">', '<meta name="renderer" content="webkit">')),
        'title' => array('type' => 'string', 'default' => ''),
        'bodyProps' => array('type' => 'array', 'optional' => true),
        'zui' => array('type' => 'bool', 'default' => true),
        'print' => array('type' => 'bool', 'default' => false)
    );
}
