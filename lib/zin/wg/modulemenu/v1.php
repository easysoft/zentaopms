<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sidebar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'dropdown' . DS . 'v1.php';

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
        'titleShow?: bool=true',
        'app?: string=""',
        'checkbox?: bool',
        'preserve?: string|bool',
        'tree?: array',
        'checkOnClick?: bool|string',
        'appendSettingItems?: array',
        'onCheck?: function',
        'toggleSidebar?: bool=true',
        'isInModal?: bool=false',
        'onClickItem?: function'
    );

    protected static array $defineBlocks = array
    (
        'header' => array(),
        'footer' => array()
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .module-menu {max-height: calc(100vh - 79px); display: flex; flex-direction: column; min-height: 32px; --menu-selected-bg: none;}
        .module-menu header a:hover > .icon {color: var(--color-primary-600) !important;}
        .module-menu .tree-item * {white-space: nowrap;}
        .module-menu .tree-item .item-content {color: var(--color-gray-700)}
        .module-menu .tree-item > .selected .item-content {color: var(--color-fore)}
        .module-menu > .tree.has-nested-items {padding-left: calc(2 * var(--space))}
        .has-module-menu-header #mainMenu {padding-left: 180px;}
        .module-menu-header.is-fixed {position: absolute; left: 0; top: -44px; width: 160px; height: 32px; border: 1px solid var(--color-border); justify-content: center; padding: 0 24px; border-right: 0;}
        .module-menu-header.is-fixed::before,
        .module-menu-header.is-fixed::after {content: ''; position: absolute; top: 0; right: -12px; width: 0; height: 0; border-style: solid; border-color: transparent transparent transparent var(--color-border); border-width: 15px 0 15px 12px;}
        .module-menu-header.is-fixed::after {right: -11px; border-color: transparent transparent transparent var(--color-canvas);}
        .has-module-menu-header.is-sidebar-left-collapsed .module-menu-header.is-fixed {left: var(--gutter-width)}
        .module-menu-header.is-fixed .module-title {font-size: var(--font-size-base);}
        .module-menu-header.is-fixed > .btn-close {position: absolute; right: 0; font-weight: normal;}
        .module-menu-header.is-fixed > .btn-close:not(:hover) {opacity: .5;}
        .sidebar > .module-menu-header.is-fixed {display: flex!important;}
        .sidebar-left > .module-menu {margin-right: -8px}
        .sidebar-left.is-expanded > .module-menu ~ .sidebar-gutter {margin-left: 4px}
        .sidebar-right.is-expanded > .module-menu ~ .sidebar-gutter {margin-right: 4px}
        .is-expanded > .module-menu ~ .sidebar-gutter > .gutter-toggle {opacity: 0}
        .has-module-menu-header .sidebar-left {transition-property: width;}
        .has-module-menu-header .module-menu {max-height: calc(100vh - 105px); }
        .has-module-menu-header .module-menu > .tree {padding-top: 8px; padding-bottom: 8px;}
        CSS;
    }

    private array $modules = array();

    private function buildMenuTree(int|string $parentID = 0): array
    {
        $children = zget($this->modules, $parentID, []);
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
                'hint'         => is_array($child->name) ? (isset($child->name['text']) ? $child->name['text'] : current($child->name)) : $child->name,
                'url'          => zget($child, 'url', ''),
                'titleAttrs'   => $titleAttrs,
                'contentClass' => 'overflow-x-hidden'
            );
            if(!empty($child->actions)) $item['actions'] = $child->actions;

            $items = $this->buildMenuTree($child->id);
            if($items) $item['items'] = $items;
            if($child->id === $activeKey || $child->id === 'product-' . $activeKey)
            {
                $itemKey = $this->prop('checkbox') ? 'checked' : 'selected';
                $item[$itemKey] = true;
            }
            $treeItems[] = $item;
        }

        return $treeItems;
    }

    private function setMenuTreeProps(): void
    {
        $modules = $this->prop('modules');
        if($modules)
        {
            foreach($modules as $module) $this->modules[$module->parent][] = $module;
        }

        $this->setProp('items', $this->buildMenuTree());
    }

    private function getTitle(): string
    {
        if($this->prop('title')) return $this->prop('title');

        global $lang, $app;
        $activeKey = $this->prop('activeKey');

        if(empty($activeKey))
        {
            $allText = $this->prop('allText');
            if(empty($allText)) return $lang->all;
            return $allText;
        }

        $modules    = $this->prop('modules');
        $moduleName = '';
        if($modules) array_map(function($module) use(&$moduleName, $activeKey) { if($module->id == $activeKey || $module->id == 'product-' . $activeKey) $moduleName = $module->name; }, $modules);
        if(!empty($modules) && empty($moduleName))
        {
            $module = $app->control->loadModel('tree')->getByID($activeKey);
            if($module) $moduleName = $module->name;
        }

        return $moduleName;
    }

    private function buildActions(): node|array|null
    {
        $settingLink        = $this->prop('settingLink');
        $showDisplay        = $this->prop('showDisplay');
        $appendSettingItems = $this->prop('appendSettingItems');
        $isInModal          = $this->prop('isInModal');
        if(!$settingLink && !$showDisplay && !$appendSettingItems) return null;

        global $app;
        $lang = $app->loadLang('datatable')->datatable;

        $items = array();
        if($settingLink && common::hasPriv('tree', 'browse'))
        {
            $tab         = $this->prop('settingApp',  $app->tab);
            $settingText = $this->prop('settingText', $lang->moduleSetting);

            if(empty($this->prop('items')))
            {
                $items[] = btn
                (
                    setClass('m-4 mt-0'),
                    set::text($settingText),
                    set::url($settingLink),
                    set::type('primary-pale'),
                    setData('app', $tab),
                    $isInModal ? setData(array('toggle' => 'modal', 'size' => 'md')) : null
                );
            }
            else
            {
                $items[] = array
                (
                    'text'        => $settingText,
                    'url'         => $settingLink,
                    'data-app'    => $tab,
                    'data-toggle' => $isInModal ? 'modal' : '',
                    'data-size'   => $isInModal ? 'md'    : ''
                );
            }
        }
        if($showDisplay)
        {
            $datatableId   = $app->moduleName . ucfirst($app->methodName);
            $currentModule = $app->rawModule;
            $currentMethod = $app->rawMethod;

            if(empty($this->prop('items')))
            {
                $items[] = btn
                (
                    setClass('m-4 mt-0'),
                    set::text($lang->displaySetting),
                    set::url(createLink('datatable', 'ajaxDisplay', "datatableId=$datatableId&moduleName=$app->moduleName&methodName=$app->methodName&currentModule=$currentModule&currentMethod=$currentMethod")),
                    set::type('primary-pale'),
                    setData(array('toggle' => 'modal', 'size' => 'md'))
                );
            }
            else
            {
                $items[] = array
                (
                    'text'        => $lang->displaySetting,
                    'url'         => createLink('datatable', 'ajaxDisplay', "datatableId=$datatableId&moduleName=$app->moduleName&methodName=$app->methodName&currentModule=$currentModule&currentMethod=$currentMethod"),
                    'data-toggle' => 'modal',
                    'data-size'   => 'md'
                );
            }
        }
        if($appendSettingItems)
        {
            if(empty($this->prop('items')))
            {
                foreach($appendSettingItems as $item)
                {
                    $items[] = btn(setClass('m-4 mt-0'), set::type('primary-pale'), set($item));
                }
            }
            else
            {
                $items = array_merge($items, $appendSettingItems);
            }
        }

        if(empty($items)) return null;
        if(empty($this->prop('items'))) return $items;

        return new dropdown
        (
            new btn
            (
                set::type('ghost'),
                set::icon('cog-outline'),
                set::size('sm'),
                set::caret(false)
            ),
            set::items($items),
            set::placement('top-end')
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

        $title         = $this->getTitle();
        $userTreeProps = $this->prop('tree');
        $treeProps     = $this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'checkbox', 'checkOnClick', 'onCheck'));
        $preserve      = $this->prop('preserve', $app->rawModule . '-' . $app->rawMethod);
        $isInSidebar   = $this->parent instanceof sidebar;
        $titleShow     = $this->prop('titleShow');
        if(!is_null($this->prop('filterMap'))) static::$filterMap = $this->prop('filterMap');

        $header = $titleShow ? h::header
        (
            setClass('module-menu-header h-10 flex items-center pl-4 flex-none gap-3', $isInSidebar ? 'is-fixed rounded rounded-r-none canvas' : ''),
            span
            (
                setClass('module-title text-lg font-semibold clip'),
                $title
            ),
            $this->buildCloseBtn()
        ) : null;

        $actions           = $this->buildActions();
        $hasActionDropdown = $actions && $actions instanceof dropdown;
        $hasToggleBtn      = $this->prop('toggleSidebar');

        return array
        (
            $isInSidebar ? $header : null,
            div
            (
                setID('moduleMenu'),
                setClass('module-menu shadow ring rounded bg-canvas col relative'),
                $this->block('header'),
                $isInSidebar ? null : $header,
                zui::tree
                (
                    set::_tag('menu'),
                    set::_class('tree tree-lines col flex-auto scrollbar-hover overflow-y-auto overflow-x-hidden pr-2 pl-4'),
                    set::defaultNestedShow(true),
                    set::hover(true),
                    set::lines(true),
                    set::preserve($preserve),
                    set::itemActions($this->prop('moduleActions')),
                    set($treeProps),
                    set($userTreeProps)
                ),
                $hasActionDropdown ? null : $actions,
                $this->block('footer'),
                ($hasActionDropdown || $hasToggleBtn) ? row
                (
                    setClass('justify-end p-1 flex-none'),
                    $this->prop('createModuleLink') ? btn
                    (
                        set::type('ghost'),
                        set::icon('plus'),
                        set::size('sm'),
                        set::caret(false),
                        set::url($this->prop('createModuleLink')),
                        set::hint($this->prop('createModuleHint')),
                        setData(array('toggle' => 'modal', 'size' => 'sm'))
                    ) : null,
                    $hasActionDropdown ? $actions : div(),
                    $hasToggleBtn ? btn
                    (
                        set::type('ghost'),
                        set::size('sm'),
                        set::icon('menu-arrow-left text-gray'),
                        set::hint($app->lang->collapse),
                        on::click()->do('$this.closest(".sidebar").sidebar("toggle");')
                    ) : null
                ) : null,
                $isInSidebar && !empty($header) ? on::init()->do('$("#mainContainer").addClass("has-module-menu-header")') : null
            ),
       );
    }
}
