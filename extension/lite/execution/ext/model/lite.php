<?php
public function setMenu($executionID, $buildID = 0, $extra = '')
{
    common::setMenuVars('execution', $executionID);

    $execution = $this->getById($executionID);
    if(isset($this->lang->execution->menu->kanban))
    {
        $this->loadModel('project')->setMenu($execution->project);
        $this->lang->kanbanProject->menu->execution['subMenu'] = new stdClass();
        if($this->app->rawModule == 'tree') unset($this->lang->kanbanProject->menu->execution['subMenu']);
    }

    $kanbanList    = $this->getList($execution->project, 'kanban', 'all');
    $currentKanban = zget($kanbanList, $execution->id, '');
    if(empty($currentKanban))
    {
        echo(js::alert($this->lang->execution->accessDenied));
        die(js::locate(helper::createLink('project', 'execution', "status=all&projectID={$execution->project}")));
    }

    $lowerModule = strtolower($this->app->rawModule);
    $lowerMethod = strtolower($this->app->rawMethod);

    $modulePageNav  = "";
    $modulePageNav .= "<div class='btn-group angle-btn active'><div class='btn-group'>";
    $modulePageNav .= "<button data-toggle='dropdown' type='button' class='btn' style='border-radius: 4px;'>{$currentKanban->name} <span class='caret'></span></button>";
    $modulePageNav .= "<ul class='dropdown-menu'>";
    foreach($kanbanList as $kanbanID => $kanban)
    {
        if($this->session->kanbanview && strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$this->session->kanbanview}|") !== false)
        {
            $method = $this->session->kanbanview;
        }
        elseif($this->cookie->kanbanview && strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$this->cookie->kanbanview}|") !== false)
        {
            $method = $this->cookie->kanbanview;
        }
        else
        {
            $method = 'kanban';
        }

        $module = 'execution';

        if($lowerModule == 'task' and $lowerMethod == 'create')
        {
            $module = 'task';
            $method = 'create';
        }

        $modulePageNav .=  '<li>' . html::a(helper::createLink($module, $method, "execution=$kanban->id"), $kanban->name) . '</li>';
    }
    $modulePageNav .= "</ul></div></div>";

    if($lowerModule == 'execution' and strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$lowerMethod}|") !== false)
    {
        $this->session->set('kanbanview', $lowerMethod);
        setcookie('kanbanview', $lowerMethod, $this->config->cookieLife, $this->config->webRoot, '', false, true);
    }

    if(strpos('|task|calendar|gantt|tree|grouptask|', "|{$lowerMethod}|") !== false) $this->lang->TRActions = $this->getTRActions($lowerMethod);
    if(strpos('|relation|maintainrelation|', "|{$lowerMethod}|") !== false) $this->lang->TRActions = $this->getTRActions('gantt');
    if($lowerModule == 'task' or ($lowerModule == 'execution' and strpos('|kanban|task|calendar|gantt|tree|grouptask|', "|{$lowerMethod}|") === false))
    {
        if($this->session->kanbanview)
        {
            $this->lang->TRActions = $this->getTRActions($this->session->kanbanview);
        }
        elseif($this->cookie->kanbanview)
        {
            $this->lang->TRActions = $this->getTRActions($this->cookie->kanbanview);
        }
    }

    $this->lang->modulePageNav = $modulePageNav;
}

public function getTree($executionID)
{
    $fullTrees = $this->loadModel('tree')->getTaskStructure($executionID, 0);

    array_unshift($fullTrees, array('id' => 0, 'name' => '/', 'type' => 'task', 'actions' => false, 'root' => $executionID));

    foreach($fullTrees as $i => $tree)
    {
        $tree = (object)$tree;

        if($tree->type == 'product') array_unshift($tree->children, array('id' => 0, 'name' => '/', 'type' => 'story', 'actions' => false, 'root' => $tree->root));
        $fullTree = $this->fillTasksInTree($tree, $executionID);

        if(empty($fullTree->children))
        {
            unset($fullTrees[$i]);
        }
        else
        {
            $fullTrees[$i] = $fullTree;
        }
    }

    if(isset($fullTrees[0]) and empty($fullTrees[0]->children)) array_shift($fullTrees);

    $newTrees = array();

    foreach($fullTrees as $i => $tree)
    {
        if($tree->type == 'product')
        {
            foreach($tree->children as $value)
            {
                $newTrees[] = $value;
            }
        }
        else
        {
            $newTrees[] = $tree;
        }
    }

    return array_values($newTrees);
}

public function getTRActions($currentMethod)
{
    $subMenu = $this->lang->execution->menu;

    foreach($subMenu as $key => $value)
    {
        if(common::hasPriv('execution', $key))
        {
            $tmpValue = explode('|', $value['link']);
            $subMenu->{$key}['name']   = $tmpValue[0];
            $subMenu->{$key}['module'] = $tmpValue[1];
            $subMenu->{$key}['method'] = $tmpValue[2];
            $subMenu->{$key}['vars']   = $tmpValue[3];
        }
        else
        {
            unset($subMenu->$key);
        }
    }

    $TRActions  = '';
    $TRActions .= "<div class='btn-group dropdown'>";
    $TRActions .= html::a("javascript:;", "<i class='icon icon-" . $this->lang->execution->icons[$currentMethod]."'> </i>" . $subMenu->{$currentMethod}['name'] . " <span class='caret'></span>", '', "class='btn btn-link' data-toggle='dropdown'");
    $TRActions .= "<ul class='dropdown-menu pull-right'>";
    foreach($subMenu as $subKey => $subName)
    {
        $active = $this->session->kanbanview == $subKey ? "class='active'" : '';
        $TRActions .=  "<li $active>" . html::a(helper::createLink('execution', $subName['method'], $subName['vars']), "<i class='icon icon-" . $this->lang->execution->icons[$subName['method']] . "'></i> " . $subName['name']) . '</li>';
    }

    $TRActions .= "</ul></div>";
    return $TRActions;
}
