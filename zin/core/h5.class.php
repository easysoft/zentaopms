<?php
/**
 * The h5 helper class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

 namespace zin\core;

use stdClass;

require_once 'ele.class.php';

/**
 * Helper methods for create html5 elements
 */
class h5 extends ele
{
    public function __construct()
    {
        $args = func_get_args();
        if(isset($args[0]) && is_string($args[0]))
        {
            $tag = new stdClass();
            $tag->tag = $args[0];
            $args[0] = $tag;
        }
        parent::__construct($args);
    }

    public static function __callStatic($tagName, $args)
    {
        return h5::create($tagName, $args);
    }

    public static function create($tagName, $args, $defaultProps = NULL)
    {
        $ele = (new h5($args))->setTag($tagName);
        if(is_array($defaultProps)) $ele->setDefaultProps($defaultProps);
        return $ele;
    }

    public static function button()
    {
        return self::create('button', func_get_args(), array('type' => 'button'));
    }

    public static function input()
    {
        return self::create('input', func_get_args(), array('type' => 'text'));
    }

    public static function checkbox()
    {
        return self::create('input', func_get_args(), array('type' => 'checkbox'));
    }

    public static function radio()
    {
        return self::create('input', func_get_args(), array('type' => 'radio'));
    }
}
