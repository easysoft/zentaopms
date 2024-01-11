<?php

declare(strict_types=1);
/**
 * The field function file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'field.class.php';
require_once __DIR__ . DS . 'fieldlist.class.php';

function defineFieldList(string $name = null, string|array|field|fieldList|null ...$args): fieldList
{
    global $app;
    if(is_null($name)) $name = $app->rawModule;
    return fieldList::define($name, ...$args);
}

function defineField(string $name, ?string $listName = null): field
{
    if(str_contains($name, '/') && is_null($listName))
    {
        list($listName, $name) = explode('/', $name);
    }
    return defineFieldList($listName)->field($name);
}

function fieldList(string $name)
{
    return fieldList::ensure($name);
}

function field(string $name): field
{
    return new field($name);
}

function useFields(string|array|field|fieldList|null ...$args): fieldList
{
    return fieldList::build(...$args);
}
