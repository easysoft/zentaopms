<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'mainnavbar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'menuviewswitcher' . DS . 'v1.php';

class navbar extends wg
{
    protected static array $defineProps = array(
        'items?: array'
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        #navbar {position: relative;}
        #navbar .nav[z-use-sortable] > li:hover {cursor: grab !important;}
        #navbar .nav[z-use-sortable] > li > a:hover {cursor: grab !important;}
        #navbar .nav li.nav-divider.divider {border: none; width: 1px; background: currentColor; margin: 0; padding-left: var(--nav-divider-margin); padding-right: var(--nav-divider-margin); box-sizing: content-box; background-clip: content-box;}

        #navbarHeading {position: absolute; top: 0; left: 1rem; bottom: 0; display: flex; align-items: center; justify-content: center;}
        @media (min-width: 1400px) {#navbarHeading{left: 0}}
        CSS;
    }

    public static function getPageJS(): ?string
    {
        global $lang, $app;
        $app->loadLang('index');
        jsVar('langData', $lang->index->dock);
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function getOriginItems()
    {
        $items = $this->prop('items');
        if($items !== null) return $items;

        return static::getItems();
    }

    protected function buildWorkspaceNavbar(string $workspace)
    {
        global $app, $config;
        $originItems      = $this->getOriginItems();
        $isTaskViews      = $app->rawModule === 'execution' && data('activeMenu') === 'task';
        $isTaskReport     = $app->tab === 'execution' && $app->rawModule === 'task' && $app->rawMethod === 'report';
        $showViewSwitcher = ($isTaskViews || $isTaskReport) && !empty($config->sanplexVersion) && $config->vision === 'rnd';
        $items            = static::getWorkspaceItems($showViewSwitcher);
        $activeID         = data('activeMenu');
        $menuGroup        = data('mainNavbarGroup');
        $activeItem       = array('data-id' => $activeID);

        foreach($originItems as $item)
        {
            if(empty($item['data-id']) || $item['data-id'] != $activeID) continue;
            $activeItem = $item;
            break;
        }

        return h::nav
        (
            set::id('navbar'),
            div
            (
                setID('navbarHeading'),
                setClass('text-lg'),
                isset($activeItem['text']) ? $activeItem['text'] : null
            ),
            new nav
            (
                setData('workspace', $workspace),
                setData('navbarGroup', $menuGroup),
                setData('mainNavbarGroup', $app->tab . '-' . $activeID),
                on::init()->call('initPageNavbar', $originItems, $workspace, $activeID, "__WORKSPACE_{$workspace}__", empty($config->customMenu->{$menuGroup}) ? [] : json_decode($config->customMenu->{$menuGroup})),
                set::items($items),
                $this->children()
            ),
            $showViewSwitcher ? css('#actionBar>a[href^="' . str_replace('.html', '', createLink('task', 'report')) . '"]{display: none;}') : null
        );
    }

    /**
     * Build.
     *
     * @access protected
     */
    protected function build()
    {
        $workspace = commonModel::getWorkspaceInfo();
        if($workspace['opened']) return $this->buildWorkspaceNavbar($workspace['type']);

        $items    = $this->getOriginItems();
        $navItems = [];

        foreach($items as $item)
        {
            if(!empty($item['hidden'])) continue;
            $navItems[] = array_merge($item, ['icon' => null]);
        }

        global $lang;
        $responsiveNavOptions = [];
        $responsiveNavOptions['container']        = 'parent';
        $responsiveNavOptions['more']             = ['text' => $lang->other, 'caret' => true];
        $responsiveNavOptions['moreDropdown']     = ['trigger' => 'hover'];
        $responsiveNavOptions['watch']            = ['container', '#toolbar', '#heading'];
        $responsiveNavOptions['getContainerSize'] = jsRaw('(container) => ($(container).width() - 20 - (2 * Math.max(($("#heading").outerWidth() || 0), $("#toolbar").outerWidth() || 0)))');

        return h::nav
        (
            set::id('navbar'),
            commonModel::isTutorialMode() ? null : on::contextmenu('.nav-item:not(.nav-dropdown) > a, .nav-divider')->call('handleNavbarContextmenu', jsRaw('event'), jsRaw('this')),
            new nav
            (
                setData('navbarGroup', data('mainNavbarGroup')),
                on::init()->call('initPageNavbar', $items),
                set::items($navItems),
                empty($items) ? null : zui::create('ResponsiveNavHelper', $responsiveNavOptions),
                $this->children()
            )
        );
    }


    protected static function getWorkspaceItems(bool $showViewSwitcher = false)
    {
        if($showViewSwitcher)
        {
            list($items) = menuViewSwitcher::getItems();
            foreach($items as $index => $item)
            {
                if(empty($item['selected'])) continue;
                $items[$index]['active'] = true;
            }
            return $items;
        }

        return mainNavbar::getItems();
    }

    protected static function getExecutionMoreItem($executionID)
    {
        if(commonModel::isTutorialMode()) return;

        global $lang, $app;

        $object = $app->dbh->query('SELECT project,`type` FROM ' . TABLE_EXECUTION . " WHERE `id` = '$executionID'")->fetch();
        if(empty($object)) return;

        $project     = $app->dbh->query('SELECT id,`model` FROM ' . TABLE_PROJECT . " WHERE `id` = '{$object->project}'")->fetch();
        $isWaterfall = in_array($project->model, array('waterfall', 'waterfallplus'));
        $orderBy     = $isWaterfall ? '`order` ASC' : '`id` DESC';

        $executionPairs    = array();
        $executionList     = $app->control->dao->select("id,name,parent,grade,path")->from(TABLE_EXECUTION)->where('project')->eq($object->project)->andWhere('deleted')->eq('0')->orderBy($orderBy)->fetchAll('id');
        $orderedExecutions = $app->control->loadModel('execution')->resetExecutionSorts($executionList);
        foreach(array_keys($orderedExecutions) as $currentID)
        {
            if(!isset($executionList[$currentID])) continue;

            $execution = $executionList[$currentID];
            if(isset($executionPairs[$execution->parent])) unset($executionPairs[$execution->parent]);
            if(!$app->user->admin && strpos(",{$app->user->view->sprints},", ",{$execution->id},") === false) continue;
            if($execution->id == $executionID) continue;

            $executionPairs[$currentID] = $execution->name;
            if($isWaterfall)
            {
                $executionName = array_map(function($executionID) use ($executionList)
                {
                    if(isset($executionList[$executionID])) return $executionList[$executionID]->name;
                }, array_slice(explode(',', trim($execution->path, ',')), 1));

                $executionPairs[$currentID] = implode('/', array_filter($executionName));
            }
        }
        if(empty($executionPairs)) return;

        $dropItems = array();
        foreach($executionPairs as $executionID => $executionName)
        {
            $dropItems[] = array(
                'url' => createLink('execution', 'task', "executionID=$executionID"),
                'text' => $executionName,
                'hint' => $executionName,
                'class' => 'text-ellipsis'
            );

            if(count($dropItems) >= 10) break;
        }

        if(count($executionPairs) > 10)
        {
            $dropItems[] = array(
                'url' => createLink('project', 'execution', "status=all&projectID={$object->project}"),
                'text' => "$lang->preview $lang->more",
                'hint' => $lang->more,
                'data-app' => 'project'
            );
        }

        return array(
            'type'    => 'dropdown',
            'items'   => $dropItems,
            'text'    => $lang->more,
            'trigger' => 'hover',
            'id'      => 'navbarMoreMenu',
            'data-id' => 'more',
            'menu'    => array('style' => array('max-width' => '300px'))
        );
    }

    protected static function getAppBtnItem()
    {
        if(commonModel::isTutorialMode()) return;

        global $app, $config, $lang;

        $condition = '';
        if(!$app->user->admin)
        {
            $types = '';
            foreach($config->pipelineTypeList as $pipelineType)
            {
                if(commonModel::hasPriv($pipelineType, 'browse')) $types .= "'$pipelineType',";
            }
            if(empty($types)) return;
            $condition .= ' AND `type` in (' . trim($types, ',') . ')';
        }
        $pipelineList = $app->dbh->query("SELECT type,name,url FROM " . TABLE_PIPELINE . " WHERE `deleted` = '0' $condition order by type")->fetchAll();
        if(empty($pipelineList)) return;

        $dropItems = array();
        foreach($pipelineList as $pipeline)
        {
            $dropItems[] = array(
                'url' => $pipeline->url,
                'text' => "[{$pipeline->type}] {$pipeline->name}",
                'hint' => $pipeline->name,
                'class' => 'text-ellipsis',
                'target' => '_blank'
            );
        }

        return array(
            'type' => 'dropdown',
            'items' => $dropItems,
            'text' => $lang->app->common,
            'trigger' => 'hover',
            'menu' => array('style' => array('max-width' => '300px'))
        );
    }

    /**
     * Get the items for the navbar.
     *
     * @access public
     * @return array
     */
    public static function getItems()
    {
        $items = data('navbarOriginItems');
        if($items !== null) return array_values($items);

        global $app, $lang, $config;
        $adminMenuKey = '';
        if($app->tab == 'admin')
        {
            $groupID = data('groupID') ? data('groupID') : 0;
            $app->control->loadModel('admin')->setMenu($groupID);
            $adminMenuKey = $app->control->loadModel('admin')->getMenuKey();
        }

        commonModel::replaceMenuLang();
        $isHomeMenu = commonModel::setMainMenu();
        commonModel::checkMenuVarsReplaced();

        jsVar('isHomeMenu', $isHomeMenu);

        $isTutorialMode = commonModel::isTutorialMode();
        $currentModule = $app->rawModule;
        $currentMethod = $app->rawMethod;

        if($isTutorialMode and defined('WIZARD_MODULE')) $currentModule = WIZARD_MODULE;
        if($isTutorialMode and defined('WIZARD_METHOD')) $currentMethod = WIZARD_METHOD;

        $tab              = $app->tab;
        $menu             = \customModel::getMainMenu($isHomeMenu);
        $activeMenu       = '';
        $activeMenuID     = data('activeMenuID');
        $items            = array();
        $flows            = $config->edition != 'open' ? $app->control->loadModel('my')->getFlowPairs() : array();
        $projectModel     = '';
        $navbarInMainMenu = [];
        $activeInMainMenu = '';
        $getMenuIcon      = function($menuItem) use ($lang)
        {
            if(isset($menuItem->icon)) return $menuItem->icon;
            if(!empty($lang->iconMap[$menuItem->name])) return 'icon-' . $lang->iconMap[$menuItem->name];
            return '';
        };

        foreach($menu as $menuItem)
        {
            if(isset($menuItem->class) && strpos($menuItem->class, 'automation-menu'))
            {
                if($menuItem->divider) $items[] = array('type' => 'divider');
                $items[] = array('class' => $menuItem->class, 'text' => $menuItem->text, 'type' => 'text', 'tagName' => 'span', 'icon' => $getMenuIcon($menuItem));
                continue;

            }
            if(empty($menuItem->link)) continue;

            if($menuItem->divider && empty($menuItem->hidden)) $items[] = array('type' => 'divider');

            /* Init the these vars. */
            $subModule = isset($menuItem->subModule) ? explode(',', $menuItem->subModule) : array();
            $class     = isset($menuItem->class) ? $menuItem->class : '';
            $exclude   = isset($menuItem->exclude) ? $menuItem->exclude : '';
            $isActive  = false;

            if($menuItem->name == $currentModule and !str_contains(",$exclude,", ",$currentModule-$currentMethod,"))
            {
                $isActive = true;
            }
            elseif($subModule and in_array($currentModule, $subModule) and !str_contains(",$exclude,", ",$currentModule-$currentMethod,"))
            {
                $isActive = true;
            }

            if($menuItem->link['module'] == 'project' and $menuItem->link['method'] == 'index')
            {
                $projectID    = str_replace('project=', '', $menuItem->link['vars']);
                $projectInfo  = $app->dbh->query("SELECT `model` FROM " . TABLE_PROJECT . " WHERE `id` = '$projectID'")->fetch();
                if($projectInfo && isset($projectInfo->model)) $projectModel = $projectInfo->model;
            }

            $newItem = null;
            if($menuItem->link['module'] == 'execution' and $menuItem->link['method'] == 'more')
            {
                $executionID = $menuItem->link['vars'];
                $executionMoreItem = static::getExecutionMoreItem($executionID);
                if(!empty($executionMoreItem)) $newItem = $executionMoreItem;
                elseif(isset(end($items)['type']) && end($items)['type'] == 'divider') array_pop($items); // 最后一个是分割线，则删除
            }
            elseif($menuItem->link['module'] == 'app' and $menuItem->link['method'] == 'serverlink')
            {
                $appBtnItem = static::getAppBtnItem();
                if(!empty($appBtnItem)) $newItem = $appBtnItem;
            }
            elseif($menuItem->link)
            {
                $alias  = isset($menuItem->alias) ? $menuItem->alias : '';
                $target = '';
                $module = '';
                $method = '';
                $label  = $menuItem->text;

                if(is_array($menuItem->link))
                {
                    if(isset($menuItem->link['target'])) $target = $menuItem->link['target'];
                    if(isset($menuItem->link['module'])) $module = $menuItem->link['module'];
                    if(isset($menuItem->link['method'])) $method = $menuItem->link['method'];
                }

                if($module == $currentModule and ($method == $currentMethod or str_contains(",$alias,", ",$currentMethod,")) and !str_contains(",$exclude,", ",$currentMethod,")) $isActive = true;

                $dataApp = (isset($lang->navGroup->$module) && $tab != $lang->navGroup->$module) || isset($flows[$module]) ? $tab : null;
                if($isActive && $activeMenuID) $isActive = $menuItem->name == $activeMenuID;
                if($isActive && empty($activeMenu)) $activeMenu = $menuItem->name;
                else $isActive = false;

                /* Print drop menus. */
                if(isset($menuItem->dropMenu))
                {
                    $dropItems = array();
                    foreach($menuItem->dropMenu as $dropMenuName => $dropMenuItem)
                    {
                        if(empty($dropMenuItem)) continue;
                        if(isset($dropMenuItem['hidden']) and $dropMenuItem['hidden']) continue;

                        /* Parse drop menu link. */
                        if(!empty($dropMenuItem['links']))
                        {
                            $dropMenuLink = common::getHasPrivLink($dropMenuItem);
                            if(empty($dropMenuLink)) continue;

                            list($subLabel, $subModule, $subMethod, $subParams) = $dropMenuLink;
                        }
                        else
                        {
                            $dropMenuLink = zget($dropMenuItem, 'link', $dropMenuItem);
                            list($subLabel, $subModule, $subMethod, $subParams) = explode('|', $dropMenuLink);
                            if(!common::hasPriv($subModule, $subMethod)) continue;
                        }

                        $subLink        = createLink($subModule, $subMethod, $subParams);
                        $subActive      = false;
                        $activeMainMenu = false;
                        if($currentModule == strtolower($subModule) and $currentMethod == strtolower($subMethod))
                        {
                            $activeMainMenu = true;
                        }
                        else
                        {
                            $subModule = isset($dropMenuItem['subModule']) ? explode(',', $dropMenuItem['subModule']) : array();
                            $subAlias  = zget($dropMenuItem, 'alias', '');
                            if($subModule and in_array($currentModule, $subModule) and !str_contains(",$exclude,", ",$currentModule-$currentMethod,")) $activeMainMenu = true;
                            if(str_contains(",$subAlias,", ",$currentModule-$currentMethod,")) $activeMainMenu = true;
                        }

                        if($activeMenuID) $activeMainMenu = $dropMenuName == $activeMenuID;
                        if($activeMainMenu)
                        {
                            $activeMenu = $dropMenuName;
                            $isActive   = true;
                            $subActive  = true;
                            $label      = $subLabel;
                        }

                        $dropItemIcon = isset($dropMenuItem->icon) ? $dropMenuItem->icon : '';
                        if(empty($dropItemIcon)) $dropItemIcon = isset($lang->iconMap[$dropMenuName]) ? 'icon-' . $lang->iconMap[$dropMenuName] : '';
                        $dataApp = !empty($dropMenuItem['data-app']) ? $dropMenuItem['data-app'] : $dataApp;
                        $dropItems[] = array('active' => $subActive, 'data-id' => $dropMenuName, 'url' => $subLink, 'text' => $subLabel, 'data-app' => $dataApp, 'zui-key' => $dropMenuName, 'icon' => $dropItemIcon);
                    }

                    if(empty($dropItems)) continue;

                    if($menuItem->name === 'other')
                    {
                        foreach($dropItems as $dropItem)
                        {
                            $dropItem['outerClass'] = 'is-rsh-more';
                            $items['other-' . $dropItem['data-id']] = $dropItem;
                        }
                        continue;
                    }

                    $newItem = array('type' => 'dropdown', 'items' => $dropItems, 'class' => $class, 'active' => $isActive, 'target' => $target, 'text' => $label, 'data-id' => $menuItem->name, 'data-app' => $dataApp, 'trigger' => 'hover', 'icon' => 'icon-more-circle');
                }
                else
                {
                    $hidden  = (isset($menuItem->hidden) && $menuItem->hidden && (!isset($menuItem->tutorial) || !$menuItem->tutorial));
                    $newItem = array('class' => $class, 'icon' => $getMenuIcon($menuItem), 'text' => $label, 'url' => commonModel::createMenuLink($menuItem, $tab), 'active' => $isActive, 'target' => $target, 'data-id' => $menuItem->name, 'data-app' => $dataApp, 'hidden' => $hidden);
                }
            }
            else
            {
                $newItem = array('class' => $class, 'icon' => $getMenuIcon($menuItem), 'text' => $menuItem->text, 'active' => $isActive);
            }

            if(!$newItem) continue;
            if(isset($newItem['data-id']))
            {
                $newItem['zui-key'] = $newItem['data-id'];
                if($newItem['data-id'] === 'settings' && strpos(',execution,project,product,', ",{$app->tab},") !== false) $newItem['outerClass'] = 'is-rsh-fixed';
            }

            $showInMainMenu = isset($menuItem->showInMainMenu) ? $menuItem->showInMainMenu : false;
            if($showInMainMenu)
            {
                if($isActive) $activeInMainMenu = $showInMainMenu;
                $navbarInMainMenu[] = $newItem;
            }
            if(!$showInMainMenu || $showInMainMenu === $menuItem->name) $items[$menuItem->name] = $newItem;
        }

        data('actualActiveMenu', $activeMenu);

        $isExecutionView = ($activeMenu === 'view' || $activeMenu === 'task') && $currentModule === 'execution';
        $isTaskReport    = $currentMethod === 'report' && $currentModule === 'task';
        $isKanbanView    = $currentModule === 'execution' && $currentMethod === 'taskkanban';
        $isBurnView      = $currentModule === 'execution' && $currentMethod === 'burn';
        if(!$isExecutionView && !$isTaskReport && !$isKanbanView && !$isBurnView) $navbarInMainMenu = null;

        if(empty($items[$activeMenu]) && !empty($activeInMainMenu) && !empty($items[$activeInMainMenu]))
        {
            $items[$activeInMainMenu]['active'] = true;
            $activeMenu = $activeInMainMenu;
        }

        /* Set active menu to global data, make it accessible to other widgets */
        data('activeMenu', $activeMenu);
        data('navbarInMainMenu', $navbarInMainMenu);

        $menuGroup = $tab;
        if(!empty($projectModel)) $menuGroup = "project-$projectModel";
        elseif($isHomeMenu)       $menuGroup = "{$tab}-home";
        elseif($tab == 'admin')   $menuGroup = "admin-$adminMenuKey";
        data('mainNavbarGroup', $menuGroup);
        data('navbarOriginItems', $items);

        return array_values($items);
    }
}
