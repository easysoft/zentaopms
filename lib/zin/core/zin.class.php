<?php
declare(strict_types=1);
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

require_once dirname(__DIR__) . DS . 'utils' . DS . 'deep.func.php';

class zin
{
    public static array $globalRenderList = array();

    public static bool $enabledGlobalRender = true;

    public static array $data = array();

    public static bool $rendered = false;

    public static bool $rawContentCalled = false;

    public static function getData(string $namePath, mixed $defaultValue = null): mixed
    {
        return \zin\utils\deepGet(static::$data, $namePath, $defaultValue);
    }

    public static function setData(string $namePath, mixed $value)
    {
        \zin\utils\deepSet(static::$data, $namePath, $value);
    }

    public static function enableGlobalRender()
    {
        static::$enabledGlobalRender = true;
    }

    public static function disableGlobalRender()
    {
        static::$enabledGlobalRender = false;
    }

    public static function renderInGlobal(): bool
    {
        if(!static::$enabledGlobalRender) return false;

        static::$globalRenderList = array_merge(static::$globalRenderList, func_get_args());
        return true;
    }

    public static function getGlobalRenderList(bool $clear = true): array
    {
        $globalItems = array();

        foreach(static::$globalRenderList as $item)
        {
            if(is_object($item))
            {
                if((isset($item->parent) && $item->parent) || ($item instanceof wg && $item->shortType() === 'wg'))
                continue;
            }
            $globalItems[] = $item;
        }

        /* Clear globalRenderList. */
        if($clear) static::$globalRenderList = array();

        return $globalItems;
    }
}
