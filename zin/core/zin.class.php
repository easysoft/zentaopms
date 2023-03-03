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
    public static $globalRenderList = array();

    public static $enabledGlobalRender = true;

    public static $globalRenderMap = array();

    public static $data = array();

    public static function getData($namePath, $defaultValue = NULL)
    {
        $names = explode('.', $namePath);
        $data = &self::$data;
        foreach($names as $name)
        {
            if(is_object(($data)))
            {
                if(!isset($data->$name)) return $defaultValue;
                $data = &$data->$name;
                continue;
            }
            if(!is_array($data) || !isset($data[$name])) return $defaultValue;
            $data = &$data[$name];
        }
        return $data === NULL ? $defaultValue : $data;
    }

    public static function setData($namePath, $value)
    {
        $names = explode('.', $namePath);
        $lastName = array_pop($names);
        $data = &self::$data;
        if(!empty($names))
        {
            foreach($names as $name)
            {
                if(!is_array($data)) return;

                if(!isset($data[$name])) $data[$name] = array();
                $data = &$data[$name];
            }
        }

        $data[$lastName] = $value;
    }

    public static function enableGlobalRender()
    {
        self::$enabledGlobalRender = true;
    }

    public static function disableGlobalRender()
    {
        self::$enabledGlobalRender = false;
    }

    public static function renderInGlobal()
    {
        if(!self::$enabledGlobalRender) return false;

        self::$globalRenderList = array_merge(self::$globalRenderList, func_get_args());
    }
}
