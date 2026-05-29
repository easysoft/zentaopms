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

/**
 * Define a field.
 *
 * @param string                               $name     The name of field.
 * @param string|array|field|fieldList|null ...$extends  The extend fields and field list.
 *
 * @return field
 */
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

/**
 * Get a field list.
 *
 * @param string $name The name of field list.
 *
 * @return fieldList
 */
function fieldList(string $name)
{
    return fieldList::ensure($name);
}

/**
 * Create a field.
 *
 * @param string|object|array|null $nameOrProps The name of field or the props of field.
 *
 * @return field
 */
function field(string|object|array|null $nameOrProps = null): field
{
    return new field($nameOrProps);
}

/**
 * Use fields.
 *
 * @param string|array|field|fieldList|null ...$args The args of fields.
 *
 * @return fieldList
 */
function useFields(string|array|field|fieldList|null ...$args): fieldList
{
    return fieldList::build(...$args);
}

/**
 * Load fields.
 *
 * @param string $moduleName The name of module.
 * @param string $methodName The name of method.
 * @param string $viewDir    The directory of view.
 *
 * @return void
 */
function loadFields(string $moduleName, string $methodName, string $viewDir = 'ui'): void
{
    global $app;

    $moduleName = strtolower(trim($moduleName));
    $methodName = strtolower(trim($methodName));
    $appName     = $app->getAppName();
    $modulePath  = $app->getModulePath($appName, $moduleName);
    $viewPath    = $modulePath . $viewDir;

    $commonFieldFile = $viewPath . DS . 'common.field.php';
    $methodFieldFile = $viewPath . DS . $methodName . '.field.php';
    helper::import($commonFieldFile);
    helper::import($methodFieldFile);
}
