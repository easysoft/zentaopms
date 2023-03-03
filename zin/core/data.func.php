<?php
/**
 * The data function file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

function data($name, $defaultValue = NULL)
{
    if(is_array($name))
    {
        $values = array();
        foreach($name as $index => $propName)
        {
            $values[] = zin::getData($propName, is_array($defaultValue) ? (isset($defaultValue[$propName]) ? $defaultValue[$propName] : $defaultValue[$index]) : $defaultValue);
        }
        return $values;
    }

    return zin::getData($name, $defaultValue);
}

function useData($name, $value)
{
    if(is_array($name))
    {
        foreach($name as $key => $value) zin::setData($key, $value);
        return;
    }
    return zin::setData($name, $value);
}
