<?php
/**
 * The zin class file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

class zin
{
    public static $list = array();

    public static $enabled = true;

    public static function enableGlobalRender()
    {
        self::$enabled = true;
    }

    public static function disableGlobalRender()
    {
        self::$enabled = false;
    }

    public static function renderInGlobal()
    {
        if(!self::$enabled) return false;

        self::$list = array_merge(self::$list, func_get_args());
    }
}
