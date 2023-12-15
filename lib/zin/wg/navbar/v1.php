<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'nav' . DS . 'v1.php';

class navbar extends wg
{
    protected static array $defineProps = array(
        'items?: array'
    );

    protected function getExecutionMoreItem($executionID)
    {
        if(defined('TUTORIAL')) return;

        global $lang, $app;

        $object = $app->dbh->query('SELECT project,type FROM ' . TABLE_EXECUTION . " WHERE `id` = '$executionID'")->fetch();
        if(empty($object)) return;

        $executionPairs = array();
        $userCondition  = !$app->user->admin ? " AND `id` " . helper::dbIN($app->user->view->sprints) : '';
        $orderBy        = $object->type == 'stage' ? 'ORDER BY `id` ASC' : 'ORDER BY `id` DESC';
        $executionList  = $app->dbh->query("SELECT id,name,parent FROM " . TABLE_EXECUTION . " WHERE `project` = '{$object->project}' AND `deleted` = '0' $userCondition $orderBy")->fetchAll();
        foreach($executionList as $execution)
        {
            if(isset($executionPairs[$execution->parent])) unset($executionPairs[$execution->parent]);
            if($execution->id == $executionID) continue;
            $executionPairs[$execution->id] = $execution->name;
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
            'type' => 'dropdown',
            'items' => $dropItems,
            'text' => $lang->more,
            'trigger' => 'hover',
            'menu' => array('style' => array('max-width' => '300px'))
        );
    }

    protected function getAppBtnItem()
    {
        if(defined('TUTORIAL')) return;
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

        commonModel::setMainMenu();
        commonModel::checkMenuVarsReplaced();

        global $app, $lang;
        $isTutorialMode = commonModel::isTutorialMode();
        $currentModule = $app->rawModule;
        $currentMethod = $app->rawMethod;

        if($isTutorialMode and defined('WIZARD_MODULE')) $currentModule = WIZARD_MODULE;
        if($isTutorialMode and defined('WIZARD_METHOD')) $currentMethod = WIZARD_METHOD;

        $menu         = \customModel::getMainMenu();
        $tab          = $app->tab;
        $activeMenu   = '';
        $activeMenuID = data('activeMenuID');
        $items        = array();
        foreach ($menu as $menuItem)
        {
            if(isset($menuItem->hidden) and $menuItem->hidden and (!isset($menuItem->tutorial) or !$menuItem->tutorial)) continue;
            if(isset($menuItem->class) && strpos($menuItem->class, 'automation-menu'))
            {
                if($menuItem->divider) $items[] = array('type' => 'divider');
                $items[] = array
                    (
                        'class'   => $menuItem->class,
                        'text'    => $menuItem->text,
                        'type'    => 'text',
                        'tagName' => 'span',
                    );
                continue;

            }
            if(empty($menuItem->link)) continue;

            if($menuItem->divider) $items[] = array('type' => 'divider');

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

            if($menuItem->link['module'] == 'execution' and $menuItem->link['method'] == 'more')
            {
                $executionID = $menuItem->link['vars'];
                $executionMoreItem = $this->getExecutionMoreItem($executionID);
                if(!empty($executionMoreItem))
                {
                    $items[] = array('type' => 'divider');
                    $items[] = $executionMoreItem;
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

                $dataApp = (isset($lang->navGroup->$module) and $tab != $lang->navGroup->$module) ? $tab : null;
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
                        $dropMenuLink = zget($dropMenuItem, 'link', $dropMenuItem);

                        list($subLabel, $subModule, $subMethod, $subParams) = explode('|', $dropMenuLink);
                        if(!common::hasPriv($subModule, $subMethod)) continue;

                        $subLink = createLink($subModule, $subMethod, $subParams);

                        $subActive = false;
                        $activeMainMenu = false;
                        if($currentModule == strtolower($subModule) and $currentMethod == strtolower($subMethod))
                        {
                            $activeMainMenu = true;
                            if($activeMenuID) $activeMainMenu = $dropMenuName == $activeMenuID;
                        }
                        else
                        {
                            $subModule = isset($dropMenuItem['subModule']) ? explode(',', $dropMenuItem['subModule']) : array();
                            if($subModule and in_array($currentModule, $subModule) and !str_contains(",$exclude,", ",$currentModule-$currentMethod,")) $activeMainMenu = true;
                        }

                        if($activeMainMenu)
                        {
                            $activeMenu = $dropMenuName;
                            $isActive   = true;
                            $subActive  = true;
                            $label      = $subLabel;
                        }

                        $dropItems[] = array(
                            'active'   => $subActive,
                            'data-id'  => $dropMenuName,
                            'url'      => $subLink,
                            'text'     => $subLabel,
                            'data-app' => $dataApp
                        );
                    }

                    if(empty($dropItems)) continue;
                    $items[] = array(
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
                        'text'     => $label,
                        'url'      => commonModel::createMenuLink($menuItem, $tab),
                        'active'   => $isActive,
                        'target'   => $target,
                        'data-id'  => $menuItem->name,
                        'data-app' => $dataApp
                    );
                }
            }
            else
            {
                $items[] = array('class' => $class, 'text' => $menuItem->text, 'active' => $isActive);
            }
        }

        /* Set active menu to global data, make it accessible to other widgets */
        data('activeMenu', $activeMenu);

        return $items;
    }

    /**
     * Build.
     *
     * @access protected
     */
    protected function build(): wg
    {
        return h::nav
        (
            set::id('navbar'),
            new nav
            (
                on::click(<<<'FUNC'
                    const $target = $(e.target);
                    if(!$target.closest('.nav-divider').length && $target.closest('.nav-item').length)
                    {
                        const $navbar = $target.closest('#navbar');
                        $navbar.find('.active').removeClass('active');
                        $target.closest('.nav-item').find('a').addClass('active');
                    }
                FUNC),
                set::items($this->getItems()),
                $this->children()
            )
        );
    }
}
