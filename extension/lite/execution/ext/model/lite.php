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
