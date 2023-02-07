<?php
namespace zin\wg;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';
require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'h5.class.php';
require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';

class btnGroup extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('class' => 'btn-group');

    static function create($btns)
    {
        $btnGroup = new btnGroup();
        foreach($btns as $props)
        {
            $btnGroup->append(btn::create($props));
        }
        return $btnGroup;
    }
}
