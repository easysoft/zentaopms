<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class pageheader extends \zin\core\wg
{
    static $tag = 'header';

    static $defaultProps = array('id' => 'header');
}
