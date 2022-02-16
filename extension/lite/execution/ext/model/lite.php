<?php
public function setMenu($executionID, $buildID = 0, $extra = '')
{
    common::setMenuVars('execution', $executionID);

    $execution = $this->getById($executionID);
    if(isset($this->lang->execution->menu->kanban))
    {
        $this->loadModel('project')->setMenu($execution->project);
        $this->lang->kanban->menu->execution['subMenu'] = new stdClass();
        if($this->app->rawModule == 'tree') unset($this->lang->kanban->menu->execution['subMenu']);
    }

    $kanbanList    = $this->getList($execution->project, 'kanban', 'all');
    $currentKanban = zget($kanbanList, $execution->id, '');
    if(empty($currentKanban))
    {
        echo(js::alert($this->lang->execution->accessDenied));
        die(js::locate(helper::createLink('project', 'execution', "status=all&projectID={$execution->project}")));
    }

    $modulePageNav  = "";
    $modulePageNav .= "<div class='btn-group angle-btn active'><div class='btn-group'>";
    $modulePageNav .= "<button data-toggle='dropdown' type='button' class='btn' style='border-radius: 4px;'>{$currentKanban->name} <span class='caret'></span></button>";
    $modulePageNav .= "<ul class='dropdown-menu'>";
    foreach($kanbanList as $kanbanID => $kanban)
    {
        $modulePageNav .=  '<li>' . html::a(helper::createLink('execution', $this->app->rawMethod, "execution=$kanban->id"), $kanban->name) . '</li>';
    }
    $modulePageNav .= "</ul></div></div>";
    if($this->app->rawMethod != 'kanban') $this->lang->TRActions = $this->getTRActions($this->app->rawMethod);

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
        $tmpValue = explode('|', $value['link']);
        $subMenu->$key['name']   = $tmpValue[0];
        $subMenu->$key['module'] = $tmpValue[1];
        $subMenu->$key['method'] = $tmpValue[2];
        $subMenu->$key['vars']   = $tmpValue[3];
    }

    $TRActions  = '';
    $TRActions .= "<div class='dropdown'>";
    $TRActions .= html::a('javascript:;', "<i class='icon icon-".$this->lang->execution->icons[$currentMethod]."'></i> " . $subMenu->$currentMethod['name'] . "<span class='caret'></span>", '', "data-toggle='dropdown' data- class='btn btn-link'");        $TRActions .= "<ul class='dropdown-menu pull-right'>";
    foreach($subMenu as $subKey => $subName)
    {
        $TRActions .=  '<li>' . html::a(helper::createLink('execution', $subName['method'], $subName['vars']), "<i class='icon icon-" . $this->lang->execution->icons[$subName['method']] . "'></i> " . $subName['name']) . '</li>';
    }

    $TRActions .= "</ul></div>";
    return $TRActions;
}
