<?php
declare(strict_types=1);
/**
 * The properties setter class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'directive.class.php';

class set
{
    public static function __callStatic($prop, $args)
    {
        $value = array_shift($args);
        if(is_object($value)) $value = (array)$value;
        return directive('prop', array($prop => $value));
    }

    public static function class(...$args)
    {
        return directive('prop', array('class' => $args));
    }
}
