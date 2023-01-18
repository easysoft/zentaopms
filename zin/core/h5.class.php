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

require_once 'ele.class.php';

/**
 * Helper methods for create html5 elements
 */
class h5 extends ele
{
    public static function __callStatic($name, $args)
    {
        return self::create($name, $args);
    }

    public static function create($name, $args, $defaultProps = NULL)
    {
        $ele = new h5(array_merge(array($name), $args));
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
