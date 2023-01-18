<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class btn extends \zin\core\wg
{
    static $tag = 'button';

    static $defaultProps = array('type' => 'button', 'class' => 'btn');
}
