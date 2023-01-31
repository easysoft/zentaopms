<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class toolbar extends \zin\core\wg
{
    static $tag = 'nav';

    static $defaultProps = array('class' => 'toolbar');

    static buildItem($item)
    {
        
    }
}
