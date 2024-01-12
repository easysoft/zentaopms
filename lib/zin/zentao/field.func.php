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

/**
 * Define a field list.
 *
 * @param string                               $name     The name of field list.
 * @param string|array|field|fieldList|null ...$extends  The extend fields and field list.
 *
 * @return fieldList
 */
function defineFieldList(string $name, string|array|field|fieldList|null ...$extends): fieldList
{
    return fieldList::define($name, ...$extends);
}

function defineField(string $name, ?string $listName = null): field
{
    if(str_contains($name, '/') && is_null($listName))
    {
        list($listName, $name) = explode('/', $name);
    }

    if(is_null($listName)) $listName = fieldList::$currentName;
    $fieldList = fieldList::ensure($listName);
    return $fieldList->field($name);
}

function fieldList(string $name)
{
    return fieldList::ensure($name);
}

function field(string|object|array|null $nameOrProps = null): field
{
    return new field($nameOrProps);
}

function useFields(string|array|field|fieldList|null ...$args): fieldList
{
    return fieldList::build(...$args);
}
