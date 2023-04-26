<?php
declare(strict_types=1);
/**
 * The tao file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easysoft.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */

class taskTao extends taskModel
{
    /**
     * Compute progress of a task.
     *
     * @param  object   $task
     * @access private
     * @return int
     */
    protected function computeTaskProgress(object $task): float
    {
        if($task->consumed == 0 and $task->left == 0)
        {
            $progress = 0;
        }
        elseif($task->consumed != 0 and $task->left == 0)
        {
            $progress = 100;
        }
        else
        {
            $progress = round($task->consumed / ($task->consumed + $task->left), 2) * 100;
        }

        return $progress;
    }

    /**
     * Compute progress of task list, include its' children.
     *
     * @param  array     $tasks
     * @access private
     * @return object[]
     */
    protected function computeTasksProgress(array $tasks): array
    {
        foreach($tasks as $task)
        {
            $task->progress = $this->getTaskProgress($task);

            if(empty($task->children)) continue;
            foreach($task->children as $child)
            {
                $child->progress = $this->getTaskProgress($child);
            }
        }

        return $tasks;
    }

    /**
     * Fetch tasks under execution by executionID,
     * 获取执行下的任务。
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  string|array $type        all|assignedbyme|myinvolved|undone|needconfirm|assignedtome|finishedbyme|delayed|review|wait|doing|done|pause|cancel|closed|array('wait','doing','done','pause','cancel','closed')
     * @param  array        $modules
     * @param  string       $orderBy
     * @param  object       $pager
     * @access protected
     * @return array
     */
    protected function fetchExecutionTasks(int $executionID, int $productID = 0, string|array $type = 'all', array $modules = array(), string $orderBy = 'status_asc, id_desc', object $pager = null): array
    {
        if(is_string($type)) $type = strtolower($type);
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $fields  = "DISTINCT t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder";
        if($this->config->edition == 'max') $fields .= ', t6.name as designName, t6.version as latestDesignVersion';

        $currentAccount = $this->app->user->account;

        $actionIDList = array();
        if($type == 'assignedbyme') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($currentAccount)->fetchPairs('objectID', 'objectID');

        $tasks  = $this->dao->select($fields)
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->leftJoin(TABLE_TASKTEAM)->alias('t4')->on('t4.task = t1.id')
            ->leftJoin(TABLE_MODULE)->alias('t5')->on('t1.module = t5.id')
            ->beginIF($this->config->edition == 'max')->leftJoin(TABLE_DESIGN)->alias('t6')->on('t1.design= t6.id')->fi()
            ->where('t1.execution')->eq($executionID)
            ->beginIF($type == 'myinvolved')->andWhere("((t4.`account` = '{$currentAccount}') OR t1.`assignedTo` = '{$currentAccount}' OR t1.`finishedby` = '{$currentAccount}')")->fi()
            ->beginIF($productID)->andWhere("((t5.root=" . $productID . " and t5.type='story') OR t2.product=" . $productID . ")")->fi()
            ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere("(t1.assignedTo = '{$currentAccount}' or (t1.mode = 'multi' and t4.`account` = '{$currentAccount}' and t1.status != 'closed' and t4.status != 'done') )")->fi()
            ->beginIF($type == 'finishedbyme')
            ->andWhere('t1.finishedby', 1)->eq($currentAccount)
            ->orWhere('t4.status')->eq("done")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,assignedbyme,review,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type == 'review')
            ->andWhere("FIND_IN_SET('{$currentAccount}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', ($productID or in_array($type, array('myinvolved', 'needconfirm', 'assignedtome'))) ? false : true);

        return $tasks;
    }

    /**
     * Get task team by id list.
     * 通过任务ID列表查询任务团队信息。
     *
     * @param  array      $taskIdList
     * @access protected
     * @return array
     */
    protected function getTeamByIdList(array $taskIdList): array
    {
        return $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task');
    }
}
