<?php
declare(strict_types=1);
/**
 * The langitem view file of dev module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong<yidong@easycorp.ltd>
 * @package     dev
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGetFeatureBarItems = function() use ($language, $type, $module, $method)
{
    global $config, $lang;

    $langItems = array();
    foreach($config->langs as $key => $value)
    {
        $key = str_replace('-', '_', $key);
        $langItems[] = array('text' => $value, 'url' => helper::createLink('dev', 'langItem', "type=$type&module=$module&method=$method&language=$key"));
    }

    $items = array();
    $items[] = array('text' => sprintf($lang->dev->language, $config->langs[str_replace('_', '-', $language)]), 'active' => false, 'type' => 'dropdown', 'caret' => 'down', 'class' => 'btn mr-2', 'items' => $langItems);
    foreach($lang->dev->featureBar['langItem'] as $key => $label) $items[] = array('text' => $label, 'active' => $type == $key, 'url' => inlink('langItem', "type=$key&module=&method=&language=$language"));

    return $items;
};

$isCurrentLang = str_replace('-', '_', $this->app->getClientLang()) == $language;
$devModel      = $this->dev;
$fnShowCompareItems = function() use ($isCurrentLang, $originalLangs, $moduleName, $currentLangs, $currentCommonLang, $customedLangs, $language, $devModel)
{
    global $config;

    $fnBuildSubItems = function($foreachLang, $originalLangChanged, $itemKey)
    {
        global $config;
        $subItems = array();
        foreach($foreachLang as $i => $subLang)
        {
            if(isset($config->custom->commonLang[$subLang]))
            {
                $subItems[] = div
                (
                    setClass('input-group-addon flex-center'),
                    set::title($config->custom->commonLang[$subLang]),
                    $config->custom->commonLang[$subLang],
                    input(set::type('hidden'), set::name("{$itemKey}[]"), set::value($subLang)),
                );
            }
            else
            {
                $customedSubLang = $subLang;
                if(!$originalLangChanged) $customedSubLang = empty($customedLang) ? '' : zget($customedLang, $i, '');
                $subItems[] = input(setClass('shadow-primary-hover'), set::name("{$itemKey}[]"), set::value($customedSubLang), $originalLangChanged ? null : set::placeholder($subLang));
            }
        }
        return $subItems;
    };

    $items = array();
    foreach($originalLangs as $langKey => $originalLang)
    {
        if(isset($config->custom->commonLang[$originalLang])) continue;

        $itemKey = "{$moduleName}_{$langKey}";
        if(!$isCurrentLang) $currentLangs[$langKey] = strtr($currentLangs[$langKey], $currentCommonLang);
        $defaultValue = $devModel->parseCommonLang($originalLang);
        $customedLang = $devModel->parseCommonLang(zget($customedLangs, $langKey, ''));
        $originalLang = strtr($originalLang, $config->custom->commonLang);
        $originalLangChanged = $devModel->isOriginalLangChanged($defaultValue, $customedLang);

        $items[] = div
        (
            set('data-id', $itemKey),
            setClass('form-item flex items-center' . ($isCurrentLang ? '' : ' w-expand')),
            $isCurrentLang ? null : div(set('data-id', $itemKey), setClass('item-label h-full'), set::title($currentLangs[$langKey]), $currentLangs[$langKey]),
            div(set('data-id', $itemKey), setClass('item-label h-full' . ($language != 'zh-cn' ? ' lg' : '')), set::title($originalLang), $originalLang),
            div
            (
                setClass('input-group'),
                icon(setClass('text-primary'), 'angle-right'),
                (($originalLangChanged and is_array($customedLang)) or (!$originalLangChanged and is_array($defaultValue))) ? $fnBuildSubItems($originalLangChanged ? $customedLang : $defaultValue, $originalLangChanged, $itemKey) : input(set::name($itemKey), setClass('shadow-primary-hover'), set::placeholder($originalLang), set::value($customedLang)),
            )
        );
    }

    return $items;
};

featureBar(set::items($fnGetFeatureBarItems()));

if(in_array($type, $config->dev->navTypes))
{
    $activeGroup = '';
    $activeChild = '';
    foreach($menuTree as $menu)
    {
        $menu->id   = "{$menu->module}_{$menu->method}";
        $menu->text = $menu->title;
        $menu->url  = helper::createLink('dev', 'langItem', "type={$type}&module={$menu->module}&method={$menu->method}&language={$language}");
        if($menu->active) $activeGroup = $menu->id;
        if(!empty($menu->children))
        {
            unset($menu->url);
            foreach($menu->children as $subMenu)
            {
                $subMenu->id   = "{$subMenu->module}_{$subMenu->method}";
                $subMenu->text = $subMenu->title;
                $subMenu->url  = helper::createLink('dev', 'langItem', "type={$type}&module={$subMenu->module}&method={$subMenu->method}&language={$language}");
                if($subMenu->active)
                {
                    $activeChild = $subMenu->id;
                    $activeGroup = $menu->id;
                }
                if(empty($subMenu->children)) unset($subMenu->children);
                $menu->items[] = $subMenu;
            }
        }
        unset($menu->children);
    }

    if($activeGroup) h::css(" .sidebar .tree [data-level=\"0\"][id=\"{$activeGroup}\"] {color: var(--color-primary-600); font-weight:bolder}");
    if($activeChild) h::css(" .sidebar .tree [data-level=\"1\"][id=\"{$activeChild}\"] {color: var(--color-primary-600); font-weight:bolder}");

    jsVar('menuTree', $menuTree);
    sidebar
    (
        setClass('bg-white'),
        h::header
        (
            inputControl
            (
                setClass("search-box search-example"),
                input(setClass('search-input'), set::type('search')),
                to::suffix(icon('search')),
                set::suffixWidth('18'),
            ),
        ),
        tree(set(array('items' => $menuTree, 'canEdit' => false, 'canDelete' => false, 'canSplit' => false)))
    );
}

form
(
    setClass('bg-white p-4'),
    set::actionsClass('w-1/2'),
    set::actions
    (
        array
        (
            'submit',
            array('url' => inlink('resetLang', "type={$type}&module={$moduleName}&method={$method}&language={$language}"), 'text' => $lang->restore, 'class' => 'btn reset-btn ajax-submit', 'data-confirm' => $lang->dev->confirmRestore),
        )
    ),
    div
    (
        setClass('title-content flex'),
        $isCurrentLang ? null : div(setClass('title'), $lang->dev->currentLang),
        div(setClass('title'), $lang->dev->defaultValue),
        div(setClass('title title-input'), $lang->dev->modifyValue),
    ),
    div
    (
        setClass("form-item-content form-active-primary"),
        $fnShowCompareItems(),
    ),
);
