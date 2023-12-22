<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sidebar' . DS . 'v1.php';

class moduleMenu extends wg
{
    private static array $filterMap = array();

    protected static array $defineProps = array(
        'modules: array',
        'activeKey?: int|string',
        'settingLink?: string',
        'closeLink: string',
        'showDisplay?: bool=true',
        'allText?: string',
        'title?: string',
        'app?: string=""',
        'checkbox?: bool',
        'checkOnClick?: bool|string',
        'onCheck?: function'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private array $modules = array();

    private function buildMenuTree(int|string $parentID = 0): array
    {
        $children = $this->getChildModule($parentID);
        if(count($children) === 0) return [];

        global $app;
        $activeKey  = $this->prop('activeKey');
        $treeItems  = array();
        $tab        = $this->prop('app') ? $this->prop('app') : $app->tab;
        $titleAttrs = array('data-app' => $tab);
        if(isInModal()) $titleAttrs['data-load'] = 'modal';

        foreach($children as $child)
        {
            $item = array(
                'key'          => $child->id,
                'text'         => $child->name,
                'hint'         => $child->name,
                'url'          => zget($child, 'url', ''),
                'titleAttrs'   => $titleAttrs,
                'contentClass' => 'overflow-x-hidden'
            );
            $items = $this->buildMenuTree($child->id);
            if($items) $item['items'] = $items;
            if($child->id == $activeKey)
            {
                $itemKey = $this->prop('checkbox') ? 'checked' : 'selected';
                $item[$itemKey] = true;
            }
            $treeItems[] = $item;
        }

        return $treeItems;
    }

    private function getChildModule(int|string $id): array
    {
        return array_filter($this->modules, function($module) use($id)
        {
            if(!isset($module->parent)) return false;

            /* Remove the rendered module. */
            if(isset(static::$filterMap["{$module->parent}-{$module->id}"])) return false;

            if($module->parent != $id) return false;

            static::$filterMap["{$module->parent}-{$module->id}"] = true;
            return true;
        });
    }

    private function setMenuTreeProps(): void
    {
        $this->modules = $this->prop('modules');
        $this->setProp('items', $this->buildMenuTree());
    }

    private function getTitle(): string
    {
        if($this->prop('title')) return $this->prop('title');

        global $lang;
        $activeKey = $this->prop('activeKey');

        if(empty($activeKey))
        {
            $allText = $this->prop('allText');
            if(empty($allText)) return $lang->all;
            return $allText;
        }

        foreach($this->modules as $module)
        {
            if($module->id == $activeKey) return $module->name;
        }

        return '';
    }

    private function buildActions(): wg|null
    {
        $settingLink = $this->prop('settingLink');
        $settingText = $this->prop('settingText');
        $showDisplay = $this->prop('showDisplay');
        $tab         = $this->prop('app');
        if(!$settingLink && !$showDisplay) return null;

        global $app;
        $lang = $app->loadLang('datatable')->datatable;
        $currentModule = $app->rawModule;
        $currentMethod = $app->rawMethod;

        if(!$settingText) $settingText = $lang->moduleSetting;

        $datatableId = $app->moduleName . ucfirst($app->methodName);

        return div
        (
            setClass('col gap-2 py-3 px-7'),
            $settingLink ? btn
            (
                set::type('primary-pale'),
                set::url($settingLink),
                set::size('md'),
                setData(array('app' => $tab ? $tab : $app->tab)),
                $settingText
            ) : null,
            $showDisplay ? btn
            (
                toggle::modal(),
                set::size('md'),
                set::type('ghost text-gray'),
                set::url(createLink('datatable', 'ajaxDisplay', "datatableId=$datatableId&moduleName=$app->moduleName&methodName=$app->methodName&currentModule=$currentModule&currentMethod=$currentMethod")),
                $lang->displaySetting
            ) : null
        );
    }

    private function buildCloseBtn(): wg|null
    {
        $closeLink  = $this->prop('closeLink');
        $tab        = $this->prop('app');
        $titleAttrs = array();
        if($tab)        $titleAttrs['app']  = $tab;
        if(isInModal()) $titleAttrs['load'] = 'modal';
        if(!$closeLink) return null;

        $activeKey = $this->prop('activeKey');
        if(empty($activeKey)) return null;

        return btn
        (
            setClass('btn-close rounded-full'),
            set::icon('close'),
            set::url($closeLink),
            set::size('sm'),
            set::type('ghost'),
            $titleAttrs ? setData($titleAttrs) : null
        );
    }

    protected function build(): array
    {
        global $app;
        $this->setMenuTreeProps();

        $title       = $this->getTitle();
        $treeProps   = $this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'checkbox', 'checkOnClick', 'onCheck'));
        $preserve    = $app->getModuleName() . '-' . $app->getMethodName();
        $isInSidebar = $this->parent instanceof sidebar;

        $header = h::header
        (
            setClass('module-menu-header h-10 flex items-center pl-4 flex-none gap-3', $isInSidebar ? 'is-fixed rounded rounded-r-none canvas' : ''),
            span
            (
                setClass('module-title text-lg font-semibold clip'),
                $title
            ),
            $this->buildCloseBtn()
        );

        return array
        (
            $isInSidebar ? $header : null,
            div
            (
                setID('moduleMenu'),
                setClass('shadow-sm rounded bg-canvas col rounded-sm'),
                $isInSidebar ? null : $header,
                zui::tree
                (
                    set::_tag('menu'),
                    set::_class('tree col flex-auto scrollbar-hover scrollbar-thin overflow-y-auto overflow-x-hidden px-4'),
                    set::defaultNestedShow(true),
                    set::hover(true),
                    set::preserve($preserve),
                    set($treeProps)
                ),
                $this->buildActions()
            ),
            $isInSidebar ? h::js("$('#mainContainer').addClass('has-module-menu-header')") : null
       );
    }
}
