<?php
public function setMenu(int $executionID, int $buildID = 0, string $extra = '')
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
    if(empty($currentKanban)) $this->accessDenied();

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
}

public function getTree(int $executionID): array
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
