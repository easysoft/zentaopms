<?php
declare(strict_types=1);
/**
 * The model file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
class userTao extends userModel
{
    /**
     * 获取某个用户参与的项目和他在项目中的团队信息。
     * Get the projects that the user joined.
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function fetchProjects(string $account, string $status = 'all', string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('t1.role, t1.join, t1.days, t1.hours, t2.*')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.type')->eq('project')
            ->andWhere('t1.account')->eq($account)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->beginIF(strpos('doing|wait|suspended|closed', $status) !== false)->andWhere('t2.status')->eq($status)->fi()
            ->beginIF($status == 'done')->andWhere('t2.status')->in('done,closed')->fi()
            ->beginIF($status == 'undone')->andWhere('t2.status')->notin('done,closed')->fi()
            ->beginIF($status == 'openedbyme')->andWhere('t2.openedBy')->eq($account)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->orderBy("t2.$orderBy")
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取某个用户参与的项目和项目包含的执行数键值对。
     * Get the projects that the user joined and the execution count of the project.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function fetchProjectExecutionCount(array $projectIdList): array
    {
        if(!$projectIdList) return array();

        return $this->dao->select('project, COUNT(1) AS count')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('multiple')->eq('1')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('type')->in('sprint,stage,kanban')
            ->andWhere('project')->in($projectIdList)
            ->groupBy('project')
            ->fetchPairs();
    }

    /**
     * 获取某个用户参与的项目和项目关联的需求规模数键值对。
     * Get the projects that the user joined and the story estimate of the project.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function fetchProjectStoryEstimate(array $projectIdList): array
    {
        if(!$projectIdList) return array();

        return $this->dao->select('t1.project, SUM(t3.estimate) AS estimate')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.project')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t2.story = t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.multiple')->eq('1')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t1.project')->in($projectIdList)
            ->groupBy('t1.project')
            ->fetchPairs();
    }

    /**
     * 获取某个用户参与的执行和他在执行中的团队信息。
     * Get the executions that the user joined.j
     *
     * @param  string $account
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function fetchExecutions(string $account, string $status = 'all', string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('t1.role, t1.join, t1.days, t1.hours, t2.*')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.type')->eq('execution')
            ->andWhere('t1.account')->eq($account)
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.multiple')->eq('1')
            ->andWhere('t2.type')->in('sprint,stage,kanban')
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->beginIF(strpos('doing|wait|suspended|closed', $status) !== false)->andWhere('t2.status')->eq($status)->fi()
            ->beginIF($status == 'done')->andWhere('t2.status')->in('done,closed')->fi()
            ->beginIF($status == 'undone')->andWhere('t2.status')->notin('done,closed')->fi()
            ->beginIF($status == 'openedbyme')->andWhere('t2.openedBy')->eq($account)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->orderBy("t2.$orderBy")
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取某个用户参与的执行和执行中指派给他的任务数键值对。
     * Get the executions that the user joined and the task count of the execution.
     *
     * @param  string $account
     * @param  array  $executionIdList
     * @access public
     * @return array
     */
    public function fetchExecutionTaskCount(string $account, array $executionIdList): array
    {
        return $this->dao->select('execution, COUNT(1) AS count')
            ->from(TABLE_TASK)
            ->where('parent')->lt(1)
            ->andWhere('deleted')->eq('0')
            ->andWhere('assignedTo')->eq($account)
            ->andWhere('execution')->in($executionIdList)
            ->groupBy('execution')
            ->fetchPairs();
    }
}
