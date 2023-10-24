<?php
declare(strict_types=1);
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

function setPageData($name, $value)
{
    if(is_array($value) && empty($name))
    {
        foreach ($value as $key => $val) zin::setData($key, $val);
        return;
    }
    zin::setData($name, $value);
}

function getPageData($name)
{
    if(is_array($name))
    {
        $values = array();
        foreach($name as $propName)
        {
            $values[] = zin::getData($propName);
        }
        return $values;
    }

    return zin::getData($name);
}

function data()
{
    $args = func_get_args();

    if(count($args) >= 2) return setPageData($args[0], $args[1]);
    return getPageData($args[0]);
}

/**
 * Set page data
 * @deprecated Use data($name, $value) insteadOf useData($name, $value)
 */
function useData($name, $value)
{
    return setPageData($name, $value);
}
