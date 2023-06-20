<?php
declare(strict_types=1);
/**
 * The tao file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao<chentao@easycorp.ltd>
 * @package     program
 * @link        http://www.zentao.net
 */

class programTao extends programModel
{
    /**
     * 通过项目集ID列表批量获取项目集基本数据。
     * Get program base data with program ID array.
     *
     * @param  array     $programIdList
     * @access protected
     * @return array
     */
    protected function getBaseDataList(array $programIdList): array
    {
        return $this->dao->select('id,name,PM,path,parent,type')
            ->from(TABLE_PROGRAM)
            ->where('id')->in($programIdList)
            ->andWhere('deleted')->eq('0')
            ->andWhere('type')->eq('program')
            ->fetchAll('id');
    }

    /**
     *获取所有根项目集基本数据。
     * Get base data of all root programs.
     *
     * @access protected
     * @return array
     */
    protected function getRootProgramList(): array
    {
        return $this->dao->select('id,name,PM,path,parent,type')
            ->from(TABLE_PROGRAM)
            ->where('parent')->eq('0')
            ->andWhere('deleted')->eq('0')
            ->andWhere('type')->eq('program')
            ->orderBy('order_asc')
            ->fetchAll();
    }

    /**
     * 构建项目视图的项目集的操作数据。
     * Build actions map for program.
     *
     * @param  object    $program
     * @access protected
     * @return array
     */
    protected function buildProgramActionsMap(object $program): array
    {
        if($program->type == 'program' && !str_contains(",{$this->app->user->view->programs},", ",$program->id,")) return array();

        $actionsMap         = array();
        $canStartProgram    = common::hasPriv('program', 'start');
        $canSuspendProgram  = common::hasPriv('program', 'suspend');
        $canCloseProgram    = common::hasPriv('program', 'close');
        $canActivateProgram = common::hasPriv('program', 'activate');
        $normalActions      = array('start', 'close', 'activate');
        foreach($normalActions as $action)
        {
            if($action == 'start' && (!$canStartProgram || ($program->status != 'wait' && $program->status != 'suspended'))) continue;
            if($action == 'close' && (!$canCloseProgram || $program->status != 'doing')) continue;
            if($action == 'activate' && (!$canActivateProgram || $program->status != 'closed')) continue;

            $actionsMap[] = $action;
            break;
        }

        if($canSuspendProgram || ($canCloseProgram && $program->status != 'doing') || ($canActivateProgram && $program->status != 'closed'))
        {
            $other = new stdclass();
            $other->name  = 'other';
            $other->items = array();

            $otherActions = array('suspend', 'close', 'activate');
            foreach($otherActions as $action)
            {
                if($action == 'close' && $program->status == 'doing') continue;
                if(!common::hasPriv('program', $action)) continue;

                $item = new stdclass();
                $item->name = $action;
                if(!static::isClickable($program, $action)) $item->disabled = true;
                if($action == 'close' && $program->status == 'closed')      $item->hint = $this->lang->program->tip->closed;
                if($action == 'suspend' && $program->status == 'closed')    $item->hint = $this->lang->program->tip->notSuspend;
                if($action == 'suspend' && $program->status == 'suspended') $item->hint = $this->lang->program->tip->suspended;
                if($action == 'activate' && $program->status == 'doing')    $item->hint = $this->lang->program->tip->actived;

                $other->items[] = $item;
            }

            $actionsMap[] = $other;
        }

        $normalActions = array('edit', 'create', 'delete');
        foreach($normalActions as $action)
        {
            if(!common::hasPriv('program', $action)) continue;
            $item = new stdclass();
            $item->name = $action;
            if($action == 'delete') $item->url = "javascript:confirmDelete({$program->id}, 'program', '{$program->name}')";
            if($action == 'create' and $program->status == 'closed')
            {
                $item->disabled = true;
                $item->hint     = $this->lang->program->tip->notCreate;
            }

            $actionsMap[] = $item;
        }

        return $actionsMap;
    }

    /**
     * 构建项目视角中项目的操作数据。
     * Build actions map for project.
     *
     * @param  object    $project
     * @access protected
     * @return array
     */
    protected function buildProjectActionsMap(object $project): array
    {
        $this->loadModel('project');
        $actionsMap         = array();
        $canStartProject    = common::hasPriv('project', 'start');
        $canSuspendProject  = common::hasPriv('project', 'suspend');
        $canCloseProject    = common::hasPriv('project', 'close');
        $canActivateProject = common::hasPriv('project', 'activate');
        $normalActions      = array('start', 'close', 'activate');
        foreach($normalActions as $action)
        {
            if($action == 'start' && (!$canStartProject || ($project->status != 'wait' && $project->status != 'suspended'))) continue;
            if($action == 'close' && (!$canCloseProject || $project->status != 'doing')) continue;
            if($action == 'activate' && (!$canActivateProject || $project->status != 'closed')) continue;

            $actionsMap[] = $action;
            break;
        }

        if($canSuspendProject || ($canCloseProject && $project->status != 'doing') || ($canActivateProject && $project->status != 'closed'))
        {
            $other = new stdclass();
            $other->name  = 'other';
            $other->items = array();

            $otherActions = array('suspend', 'close', 'activate');
            foreach($otherActions as $action)
            {
                if($action == 'close' and $project->status == 'doing') continue;
                if(!common::hasPriv('project', $action)) continue;

                $item = new stdclass();
                $item->name = $action;
                if(!projectModel::isClickable($project, $action)) $item->disabled = true;
                if($action == 'close' && $project->status == 'closed')      $item->hint = $this->lang->project->tip->closed;
                if($action == 'suspend' && $project->status == 'closed')    $item->hint = $this->lang->project->tip->notSuspend;
                if($action == 'suspend' && $project->status == 'suspended') $item->hint = $this->lang->project->tip->suspended;
                if($action == 'activate' && $project->status == 'doing')    $item->hint = $this->lang->project->tip->actived;

                $other->items[] = $item;
            }

            $actionsMap[] = $other;
        }
        if(common::hasPriv('project', 'edit')) $actionsMap[] = 'edit';
        if(common::hasPriv('project', 'team')) $actionsMap[] = 'team';
        if(common::hasPriv('project', 'manageProducts') || common::hasPriv('project', 'whitelist') || common::hasPriv('project', 'delete'))
        {
            $more = new stdclass();
            $more->name  = 'more';
            $more->items = array();
            $moreActions = array('group', 'manageProducts', 'whitelist', 'delete');
            foreach($moreActions as $action)
            {
                if(!common::hasPriv('project', $action)) continue;

                $item = new stdclass();
                $item->name = $action == 'manageProducts' ? 'link' : $action;
                if($action == 'delete') $item->url = "javascript:confirmDelete({$project->id}, 'project', '{$project->name}')";
                if($action == 'whitelist' and $project->acl == 'open')
                {
                    $item->disabled = true;
                    $item->hint     = $this->lang->project->tip->whitelist;
                }
                if($action == 'group' && $project->model == 'kanban')
                {
                    $item->disabled = true;
                    $item->hint     = $this->lang->project->tip->group;
                }

                $more->items[] = $item;
            }

            $actionsMap[] = $more;
        }
        return $actionsMap;
    }
}

