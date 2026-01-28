<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

class navbar extends wg
{
    protected static array $defineProps = array(
        'items?: array'
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        #navbar .nav[z-use-sortable] > li:hover {cursor: grab !important;}
        #navbar .nav[z-use-sortable] > li > a:hover {cursor: grab !important;}
        #navbar .nav li.nav-divider.divider {border: none; width: 1px; background: currentColor; margin: 0; padding-left: var(--nav-divider-margin); padding-right: var(--nav-divider-margin); box-sizing: content-box; background-clip: content-box;}
        CSS;
    }

    public static function getPageJS(): ?string
    {
        global $lang, $app;
        $app->loadLang('index');
        jsVar('langData', $lang->index->dock);
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function getExecutionMoreItem($executionID)
    {
        if(commonModel::isTutorialMode()) return;

        global $lang, $app;

        $object = $app->dbh->query('SELECT project,`type` FROM ' . TABLE_EXECUTION . " WHERE `id` = '$executionID'")->fetch();
        if(empty($object)) return;

        $project          = $app->dbh->query('SELECT id,`model` FROM ' . TABLE_PROJECT . " WHERE `id` = '{$object->project}'")->fetch();
        $executionPairs   = array();
        $userCondition    = !$app->user->admin ? " AND `id` " . helper::dbIN($app->user->view->sprints) : '';
        $orderBy          = in_array($project->model, array('waterfall', 'waterfallplus')) ? 'ORDER BY `order` ASC' : 'ORDER BY `id` DESC';
        $executionList    = $app->dbh->query("SELECT id,name,parent,grade FROM " . TABLE_EXECUTION . " WHERE `project` = '{$object->project}' AND `deleted` = '0' $userCondition $orderBy")->fetchAll();
        $parentExecutions = array_flip(array_column($executionList, 'parent'));
        $topExecutions    = array();
        foreach($executionList as $execution)
        {
            if($execution->grade == 1) $topExecutions[$execution->id] = $execution->id;
            if($execution->id == $executionID || isset($parentExecutions[$execution->id])) continue;
            $executionPairs[$execution->id] = $execution->name;
        }

        if(empty($executionPairs)) return;
        if(in_array($project->model, array('waterfall', 'waterfallplus')))
        {
            $allExecutions     = $app->control->dao->select('id,name,path,grade')->from(TABLE_EXECUTION)->where('project')->eq($object->project)->andWhere('deleted')->eq('0')->fetchAll('id');
            $orderedExecutions = $app->control->loadModel('execution')->resetExecutionSorts($executionPairs, $topExecutions);
            $executionPairs    = array();
            foreach($orderedExecutions as $executionID => $executionName)
            {
                $execution     = zget($allExecutions, $executionID, null);
                $paths         = array_slice(explode(',', trim($execution->path, ',')), 1);
                $executionName = array();
                foreach($paths as $path)
                {
                    if(isset($allExecutions[$path])) $executionName[] = $allExecutions[$path]->name;
                }

                $executionPairs[$executionID] = implode('/', $executionName);
            }
        }

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

    protected function getAppBtnItem()
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

    protected function getItems()
    {
        $items = $this->prop('items');
        if(!empty($items)) return $items;

        global $app, $lang, $config;
        if($app->tab == 'admin')
        {
            $groupID = data('groupID') ? data('groupID') : 0;
            $app->control->loadModel('admin')->setMenu($groupID);
            $adminMenuKey = $app->control->loadModel('admin')->getMenuKey();
            jsVar('adminMenuKey', $adminMenuKey);
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

        $tab          = $app->tab;
        $menu         = \customModel::getMainMenu($isHomeMenu);
        $activeMenu   = '';
        $activeMenuID = data('activeMenuID');
        $items        = array();
        $flows        = $config->edition != 'open' ? $app->control->loadModel('my')->getFlowPairs() : array();
        foreach($menu as $menuItem)
        {
            if(isset($menuItem->class) && strpos($menuItem->class, 'automation-menu'))
            {
                if($menuItem->divider) $items[] = array('type' => 'divider');
                $items[] = array
                (
                    'class'   => $menuItem->class,
                    'text'    => $menuItem->text,
                    'type'    => 'text',
                    'tagName' => 'span',
                    'icon'    => isset($menuItem->icon) ? $menuItem->icon : '',
                );
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
                $projectModel = $app->dbh->query("SELECT `model` FROM " . TABLE_PROJECT . " WHERE `id` = '$projectID'")->fetch();
                if($projectModel) jsVar('projectModel', $projectModel->model);
            }

            if($menuItem->link['module'] == 'execution' and $menuItem->link['method'] == 'more')
            {
                $executionID = $menuItem->link['vars'];
                $executionMoreItem = $this->getExecutionMoreItem($executionID);
                if(!empty($executionMoreItem))
                {
                    $items[] = $executionMoreItem;
                }
                elseif(isset(end($items)['type']) && end($items)['type'] == 'divider')
                {
                    array_pop($items); // 最后一个是分割线，则删除
                }
            }
            elseif($menuItem->link['module'] == 'app' and $menuItem->link['method'] == 'serverlink')
            {
                $appBtnItem = $this->getAppBtnItem();
                if(!empty($appBtnItem)) $items[] = $appBtnItem;
            }
            elseif($menuItem->link)
            {
                $alias = isset($menuItem->alias) ? $menuItem->alias : '';
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

                if($module == $currentModule and ($method == $currentMethod or str_contains(",$alias,", ",$currentMethod,")) and !str_contains(",$exclude,", ",$currentMethod,"))
                {
                    $isActive = true;
                }

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
                        if(isset($dropMenuItem->hidden) and $dropMenuItem->hidden) continue;

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

                        $dataApp = !empty($dropMenuItem['data-app']) ? $dropMenuItem['data-app'] : $dataApp;
                        $dropItems[] = array(
                            'active'   => $subActive,
                            'data-id'  => $dropMenuName,
                            'url'      => $subLink,
                            'text'     => $subLabel,
                            'data-app' => $dataApp
                        );
                    }

                    if(empty($dropItems)) continue;
                    $items[] = array
                    (
                        'type'     => 'dropdown',
                        'items'    => $dropItems,
                        'class'    => $class,
                        'active'   => $isActive,
                        'target'   => $target,
                        'text'     => $label,
                        'data-id'  => $menuItem->name,
                        'data-app' => $dataApp,
                        'trigger'  => 'hover'
                    );
                }
                else
                {
                    $items[] = array(
                        'class'    => $class,
                        'icon'     => isset($menuItem->icon) ? $menuItem->icon : '',
                        'text'     => $label,
                        'url'      => commonModel::createMenuLink($menuItem, $tab),
                        'active'   => $isActive,
                        'target'   => $target,
                        'data-id'  => $menuItem->name,
                        'data-app' => $dataApp,
                        'hidden'   => (isset($menuItem->hidden) && $menuItem->hidden && (!isset($menuItem->tutorial) || !$menuItem->tutorial))
                    );
                }
            }
            else
            {
                $items[] = array('class' => $class, 'icon' => isset($menuItem->icon) ? $menuItem->icon : '', 'text' => $menuItem->text, 'active' => $isActive);
            }
        }

        /* Set active menu to global data, make it accessible to other widgets */
        data('activeMenu', $activeMenu);
        jsVar('allNavbarItems', $items);
        jsVar('isTutorialMode', commonModel::isTutorialMode());

        $items = array_filter($items, function($item) { return empty($item['hidden']); });

        return $items;
    }

    /**
     * Build.
     *
     * @access protected
     */
    protected function build()
    {
        $items = $this->getItems();
        return h::nav
        (
            set::id('navbar'),
            new nav
            (
                set::items($items),
                $this->children()
            )
        );
    }
}
