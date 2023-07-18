<?php
declare(strict_types=1);
namespace zin;

function buildBlockModuleNav(?array $modules = null, ?string $module = null): wg
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
        set::class('block-modules-nav'),
        set::stacked(true),
        set::items($items),
        on::click('.nav-item>a', 'getForm'),
        h::css
        (
            '.block-modules-nav > .nav-item > a.active {box-shadow: inset 2px 0 0 var(--color-primary-500); color: var(--color-fore)}'
        )
    );
}
