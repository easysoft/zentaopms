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
        'settingApp?: string=""',
        'closeLink: string',
        'showDisplay?: bool=true',
        'allText?: string',
        'title?: string',
        'app?: string=""',
        'checkbox?: bool',
        'checkOnClick?: bool|string',
        'onCheck?: function'
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .module-menu {max-height: calc(100vh - 79px); min-height: 32px; --menu-selected-bg: none;}
        .module-menu header a:hover > .icon {color: var(--color-primary-600) !important;}
        .module-menu .tree-item * {white-space: nowrap;}
        .module-menu .tree-item .item-content {color: var(--color-gray-700)}
        .module-menu .tree-item > .selected .item-content {color: var(--color-fore)}
        .has-module-menu-header #mainMenu {padding-left: 180px;}
        .module-menu-header.is-fixed {position: absolute; left: 0; top: -44px; width: 160px; height: 32px; border: 1px solid var(--color-border); justify-content: center; padding: 0 24px; border-right: 0;}
        .module-menu-header.is-fixed::before,
        .module-menu-header.is-fixed::after {content: ''; position: absolute; top: 0; right: -12px; width: 0; height: 0; border-style: solid; border-color: transparent transparent transparent var(--color-border); border-width: 15px 0 15px 12px;}
        .module-menu-header.is-fixed::after {right: -11px; border-color: transparent transparent transparent var(--color-canvas);}
        .has-module-menu-header.is-sidebar-left-collapsed .module-menu-header.is-fixed {left: var(--gutter-width)}
        .module-menu-header.is-fixed .module-title {font-size: var(--font-size-base);}
        .module-menu-header.is-fixed > .btn-close {position: absolute; right: 0; font-weight: normal;}
        .module-menu-header.is-fixed > .btn-close:not(:hover) {opacity: .5;}
        .sidebar.is-collapsed > .module-menu-header.is-fixed {display: flex;}
        .has-module-menu-header .sidebar-left {transition-property: width;}
        .has-module-menu-header .module-menu {max-height: calc(100vh - 105px); }
        .has-module-menu-header .module-menu > .tree {padding-top: 8px; padding-bottom: 8px;}
        CSS;
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

    private function buildActions(): node|null
    {
        $settingLink = $this->prop('settingLink');
        $showDisplay = $this->prop('showDisplay');
        if(!$settingLink && !$showDisplay) return null;

        global $app;
        $lang = $app->loadLang('datatable')->datatable;

        $items = array();
        if($settingLink)
        {
            $tab         = $this->prop('settingApp',  $app->tab);
            $settingText = $this->prop('settingText', $lang->moduleSetting);

            if(empty($this->prop('items')))
            {
                return btn
                (
                    setClass('m-4 mt-0'),
                    set::text($settingText),
                    set::url($settingLink),
                    set::type('primary-pale'),
                    setData('app', $tab),
                );
            }

            $items[] = array
            (
                'text'      => $settingText,
                'url'       => $settingLink,
                'data-app'  => $tab
            );
        }
        if($showDisplay)
        {
            $datatableId   = $app->moduleName . ucfirst($app->methodName);
            $currentModule = $app->rawModule;
            $currentMethod = $app->rawMethod;
            $items[] = array
            (
                'text'        => $lang->displaySetting,
                'url'         => createLink('datatable', 'ajaxDisplay', "datatableId=$datatableId&moduleName=$app->moduleName&methodName=$app->methodName&currentModule=$currentModule&currentMethod=$currentMethod"),
                'data-toggle' => 'modal',
                'data-size'   => 'md'
            );
        }

        if(empty($item)) return null;

        return dropdown
        (
            btn
            (
                setClass('ghost absolute right-1 top-1'),
                set::icon('cog-outline'),
                set::size('sm'),
                set::caret(false)
            ),
            set::items($items),
            set::placement('bottom-end')
        );
    }

    private function buildCloseBtn(): node|null
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
                setClass('module-menu shadow ring rounded bg-canvas col relative'),
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
