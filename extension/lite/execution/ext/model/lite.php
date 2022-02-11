<?php
public function setMenu($executionID, $buildID = 0, $extra = '')
{
    common::setMenuVars('execution', $executionID);

    $execution = $this->getById($executionID);
    if(isset($this->lang->execution->menu->kanban))
    {
        $this->loadModel('project')->setMenu($execution->project);
        $this->lang->kanban->menu->execution['subMenu'] = $this->lang->execution->menu;
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
    if($this->app->rawMethod == 'task') $this->lang->TRActions = '';

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
