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
        if($program->type == 'project') $this->loadModel('project');

        $actionsMap         = array();
        $canStartProgram    = common::hasPriv($program->type, 'start');
        $canSuspendProgram  = common::hasPriv($program->type, 'suspend');
        $canCloseProgram    = common::hasPriv($program->type, 'close');
        $canActivateProgram = common::hasPriv($program->type, 'activate');
        $normalActions      = array('start', 'close', 'activate');
        foreach($normalActions as $action)
        {
            if($action == 'close' && (!$canCloseProgram || $program->status != 'doing')) continue;
            if($action == 'activate' && (!$canActivateProgram || $program->status != 'closed')) continue;
            if($action == 'start' && (!$canStartProgram || ($program->status != 'wait' && $program->status != 'suspended'))) continue;

            $item = new stdclass();
            $item->name   = $action;
            $item->url    = helper::createLink($program->type, $action, "programID={$program->id}");
            $actionsMap[] = $item;
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
                if(!common::hasPriv($program->type, $action)) continue;
                if($action == 'close' && $program->status == 'doing') continue;

                $item = new stdclass();
                $item->name     = $action;
                $item->url      = helper::createLink($program->type, $action, "programID={$program->id}");
                $item->disabled = !static::isClickable($program, $action);

                if($action == 'close' && $program->status == 'closed')      $item->hint = $this->lang->{$program->type}->tip->closed;
                if($action == 'activate' && $program->status == 'doing')    $item->hint = $this->lang->{$program->type}->tip->actived;
                if($action == 'suspend' && $program->status == 'suspended') $item->hint = $this->lang->{$program->type}->tip->suspended;
                if($action == 'suspend' && $program->status == 'closed')    $item->hint = $this->lang->{$program->type}->tip->notSuspend;
                $other->items[] = $item;
            }

            $actionsMap[] = $other;
        }
        return array_merge($actionsMap, $this->getNormalActions($program));
    }

    /**
     * 获取基础操作的按钮数据。
     * Get normal actions.
     *
     * @param  object $program
     * @access protected
     * @return array
     */
    protected function getNormalActions(object $program): array
    {
        $actionsMap    = array();
        $normalActions = $program->type == 'project' ? array('edit') : array('edit', 'create', 'delete');
        foreach($normalActions as $action)
        {
            if(!common::hasPriv($program->type, $action)) continue;

            $item = new stdclass();
            $item->name = $action;
            if($action != 'delete') $item->url  = helper::createLink($program->type, $action, "programID={$program->id}");
            if($action == 'delete') $item->url = "javascript:confirmDelete({$program->id}, 'program', '{$program->name}')";
            if($action == 'create' && $program->status == 'closed')
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
        $actionsMap = array();
        if(common::hasPriv('project', 'team'))
        {
            $item = new stdclass();
            $item->name   = 'team';
            $item->url    = helper::createLink('project', 'team', "projectID={$project->id}");
            $actionsMap[] = $item;
        }

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
                if($action != 'delete') $item->url = helper::createLink('project', $action, "projectID={$project->id}");
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

    /**
     * 如果修改了父项目集，修改关联的产品。
     * If change parent, then fix linked product.
     *
     * @param  int       $programID
     * @param  int       $parent
     * @param  int       $oldParent
     * @param  string    $oldPath
     * @access protected
     * @return void
     */
    protected function fixLinkedProduct(int $programID, int $parent, int $oldParent, string $oldPath): void
    {
        if($parent == $oldParent) return;

        /* Move product to new top program. */
        $oldTopProgram = $this->getTopByPath($oldPath);
        $newTopProgram = $this->getTopByID($programID);
        if($oldTopProgram == $newTopProgram) return;

        if($oldParent == 0)
        {
            $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopProgram)->where('program')->eq($oldTopProgram)->exec();
            return;
        }

        /* Get the shadow products that produced by the program's no product projects. */
        $shadowProducts = $this->dao->select('t1.id')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.product')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
            ->where('t3.path')->like("%,$programID,%")
            ->andWhere('t3.type')->eq('project')
            ->andWhere('t3.hasProduct')->eq('0')
            ->andWhere('t1.shadow')->eq('1')
            ->fetchPairs();
        if($shadowProducts) $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopProgram)->where('id')->in($shadowProducts)->exec();
    }
}
