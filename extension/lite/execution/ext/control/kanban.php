<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function kanban($executionID, $browseType = 'all', $orderBy = 'id_asc', $groupBy = 'default')
    {
        $this->app->loadLang('kanban');
        common::setMenuVars('execution', $executionID);

        $currentMethod = $this->app->methodName;
        $execution     = $this->execution->getById($executionID);
        $this->loadModel('project')->setMenu($execution->project);
        $this->lang->kanbanProject->menu->execution['subMenu'] = new stdClass();

        $this->session->set('kanbanview', $currentMethod);
        setcookie('kanbanview', $currentMethod, $this->config->cookieLife, $this->config->webRoot, '', false, true);

        /* change subMenu to sub select menu */
        $TRActions  = $this->execution->getTRActions($currentMethod);

        $TRActions .= "<div class='dropdown'>";
        $TRActions .= html::a('javascript:;', $this->lang->execution->kanbanGroup[$groupBy] . " <span class='caret'></span>", '', "data-toggle='dropdown' data- class='btn btn-link'");
        $TRActions .= "<ul class='dropdown-menu pull-right course-groupBy'>";
        foreach($this->lang->execution->kanbanGroup as $groupKey => $groupName)
        {
            $attr       = $groupBy == $groupKey ? '<i class="icon icon-check"></i>' : '';
            $TRActions .=  '<li>' . html::a(helper::createLink('execution', 'kanban', "execution=$execution->id&browseType=task&orderBy=$orderBy&groupBy=$groupKey"), $groupName . $attr) . '</li>';
        }
        $TRActions .= "</ul></div>";
        $TRActions .= html::a('javascript:fullScreen()', "<i class='icon-fullscreen muted'></i> " . $this->lang->kanban->fullScreen, '', "class='btn btn-link'");

        $printSettingBtn  = (common::hasPriv('kanban', 'createRegion') or (common::hasPriv('kanban', 'setLaneHeight')) or common::hasPriv('execution', 'edit') or common::hasPriv('execution', 'close') or common::hasPriv('execution', 'delete') or !empty($executionActions));
        $executionActions = array();
        foreach($this->config->execution->statusActions as $action)
        {
            if($this->execution->isClickable($execution, $action)) $executionActions[] = $action;
        }
        if($this->execution->isClickable($execution, 'delete')) $executionActions[] = 'delete';

        if($printSettingBtn)
        {
            $TRActions .= "<div class='dropdown'>";
            $TRActions .= html::a('javascript:;', "<i class='icon icon-cog-outline'></i>" . $this->lang->kanban->setting, '', "data-toggle='dropdown' data- class='btn btn-link'");
            $TRActions .= "<ul id='kanbanActionMenu' class='dropdown-menu pull-right'>";
            $width    = $this->app->getClientLang() == 'en' ? '750' : '650';
            if(common::hasPriv('kanban', 'createRegion')) $TRActions .= '<li>' . html::a(helper::createLink('kanban', 'createRegion', "kanbanID=$execution->id&from=execution", '', true), '<i class="icon icon-plus"></i>' . $this->lang->kanban->createRegion, '', "class='iframe btn btn-link text-left'") . '</li>';
            if(common::hasPriv('execution', 'setKanban')) $TRActions .= '<li>' . html::a(helper::createLink('execution', 'setKanban', "kanbanID=$execution->id&from=execution", '', true), '<i class="icon icon-size-height"></i>' . $this->lang->kanban->laneHeight, '', "class='iframe btn btn-link text-left' data-width=$width") . '</li>';
            $kanbanActions = '';
            if(common::hasPriv('execution', 'edit')) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'edit', "executionID=$execution->id", '', true), '<i class="icon icon-edit"></i>' . $this->lang->kanban->edit, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
            if(in_array('start', $executionActions)) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'start', "executionID=$execution->id", '', true), '<i class="icon icon-play"></i>' . $this->lang->execution->start, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
            if(in_array('putoff', $executionActions)) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'putoff', "executionID=$execution->id", '', true), '<i class="icon icon-calendar"></i>' . $this->lang->execution->putoff, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
            if(in_array('suspend', $executionActions)) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'suspend', "executionID=$execution->id", '', true), '<i class="icon icon-pause"></i>' . $this->lang->execution->suspend, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
            if(in_array('close', $executionActions)) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'close', "executionID=$execution->id", '', true), '<i class="icon icon-off"></i>' . $this->lang->execution->close, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
            if(in_array('activate', $executionActions)) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'activate', "executionID=$execution->id", '', true), '<i class="icon icon-magic"></i>' . $this->lang->execution->activate, '', "class='iframe btn btn-link text-left' data-width='75%'") . '</li>';
            if(common::hasPriv('execution', 'delete')) $kanbanActions .= '<li>' . html::a(helper::createLink('execution', 'delete', "executionID=$execution->id"), '<i class="icon icon-trash"></i>' . $this->lang->delete, 'hiddenwin', "class='btn btn-link text-left'") . '</li>';
            if($kanbanActions)
            {
                $TRActions .= ((common::hasPriv('kanban', 'createRegion') or common::hasPriv('kanban', 'setLaneHeight')) and (common::hasPriv('execution', 'edit') or common::hasPriv('execution', 'delete') or !empty($executionActions))) ? "<div class='divider'></div>" . $kanbanActions : $kanbanActions;
            }
            $TRActions .= "</ul></div>";
        }

        $canCreateTask      = common::hasPriv('task', 'create');
        $canBatchCreateTask = common::hasPriv('task', 'batchCreate');
        if($canCreateTask or $canBatchCreateTask)
        {
            $TRActions .= "<div class='dropdown' id='createDropdown'>";
            $TRActions .= "<button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> {$this->lang->create} <span class='caret'></span></button>";
            $TRActions .= "<ul class='dropdown-menu pull-right'>";
            if($canCreateTask) $TRActions .=  '<li>' . html::a(helper::createLink('task', 'create', "execution=$execution->id", '', true), $this->lang->task->create, '', "class='iframe'") . '</li>';
            if($canBatchCreateTask) $TRActions .= '<li>' . html::a(helper::createLink('task', 'batchCreate', "execution=$execution->id", '', true), $this->lang->execution->batchCreateTask, '', "class='iframe'") . '</li>';
            $TRActions .= "</ul></div>";
        }
        $this->lang->TRActions = $TRActions;

        $browseType = 'task';
        parent::kanban($executionID, $browseType, $orderBy, $groupBy);
    }
}
