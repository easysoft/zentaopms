<?php
declare(strict_types=1);
namespace zin;

/**
 * Build params rows.
 *
 * @param  object $block
 * @param  array  $params
 * @param  string $module
 * @param  string $code
 * @return array
 */
function buildParamsRows(?object $block = null, ?array $params = null, string $module = '', string $code = ''): array
{
    global $lang;

    if(empty($params)) $params = data('params');
    $rows        = array();
    $isSameBlock = !empty($block) && $block->module == $module && $block->code == $code;

    $nameRow = formRow
    (
        formGroup
        (
            set::label($lang->block->name),
            set::name('title'),
            set::value(data('blockTitle')),
            set::control('input')
        )
    );

    $rows['name'] = $nameRow;
    foreach($params as $key => $row)
    {
        $rows[$key] = formRow
        (
            formGroup
            (
                set::label($row['name']),
                set::name("params[$key]"),
                set::value($isSameBlock && !empty($block->params) ? zget($block->params, $key, '') : zget($row, 'default', '')),
                set::control(array
                (
                    'id'       => "params$key",
                    'type'     => $row['control'],
                    'items'    => isset($row['options']) ? $row['options'] : null
                )),
                set::required($row['control'] === 'picker')
            )
        );

        /* 如果有type字段， name字段放到type字段后面。*/
        if($key == 'type')
        {
            unset($rows['name']);
            $rows['name'] = $nameRow;
        }
    }
    return $rows;
}

/**
 * Build block module nav.
 *
 * @param array  $modules
 * @param string $module
 * @return array
 */
function buildBlockModuleNav(?array $modules = null, ?string $module = null): node
{
    if(empty($modules)) $modules = data('modules');
    if(empty($module)) $module   = data('module');

    $items = array();
    foreach($modules as $moduleKey => $moduleName)
    {
        if(!$moduleKey || !$moduleName) continue;

        if($moduleKey === 'welcome') $items[] = array('type' => 'divider', 'outerClass' => 'text-light');

        $item = array();
        $item['active']       = $moduleKey == $module;
        $item['text']         = $moduleName;
        $item['textClass']    = 'flex-auto group-hover:text-primary';
        $item['trailingIcon'] = 'arrow-right opacity-0 group-hover:opacity-100 text-primary active:font-bold active:canvas';
        $item['class']        = 'row group active:canvas';
        $item['data-module']  = $moduleKey;

        $items[] = $item;
    }

    return nav
    (
        set::className('block-modules-nav py-2'),
        set::stacked(true),
        set::items($items),
        on::click('.nav-item>a', 'getForm'),
        h::css('.block-modules-nav > .nav-item > a.active {box-shadow: inset 2px 0 0 var(--color-primary-500); color: var(--color-fore)}')
    );
}
