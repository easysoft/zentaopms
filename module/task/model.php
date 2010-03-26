<?php
/**
 * The model file of task module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class taskModel extends model
{
    const CUSTOM_STATUS_ORDER = 'wait,doing,done,cancel';

    /* 新增一个任务。*/
    public function create($projectID)
    {
        $task = fixer::input('post')
            ->striptags('name')
            ->specialChars('desc')
            ->cleanFloat('estimate')
            ->add('project', (int)$projectID)
            ->setDefault('estimate, left, story', 0)
            ->setDefault('deadline', '0000-00-00')
            ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setDefault('statusCustom', strpos(self::CUSTOM_STATUS_ORDER, $this->post->status) + 1)
            ->remove('after,files,labels')
            ->get();

        $this->dao->insert(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheck($this->config->task->create->requiredFields, 'notempty')
            ->checkIF($task->estimate != '', 'estimate', 'float')
            ->exec();
        if(!dao::isError())
        {
            $taskID = $this->dao->lastInsertID();
            if($this->post->story) $this->loadModel('story')->setStage($this->post->story);
            $this->loadModel('file')->saveUpload('task', $taskID);
            return $taskID;
        }
        return false;
    }

    /* 更新一个任务。*/
    public function update($taskID)
    {
        $oldTask = $this->getById($taskID);
         $task = fixer::input('post')
            ->striptags('name')
            ->specialChars('desc')
            ->cleanFloat('estimate, left, consumed')
            ->setDefault('story, estimate, left, consumed', 0)
            ->setIF($this->post->story != false and $this->post->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setIF($this->post->status == 'done', 'left', 0)
            ->setIF($this->post->consumed > 0 and $this->post->left > 0 and $this->post->status == 'wait', 'status', 'doing')
            ->remove('comment,fiels,labels')
            ->get();
        $task->statusCustom = strpos(self::CUSTOM_STATUS_ORDER, $task->status) + 1;

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheckIF($task->status != 'cancel', $this->config->task->edit->requiredFields, 'notempty')
            ->checkIF($task->estimate != false, 'estimate', 'float')
            ->checkIF($task->left     != false, 'left',     'float')
            ->checkIF($task->consumed != false, 'consumed', 'float')
            ->checkIF($task->status == 'done', 'consumed', 'notempty')
            ->checkIF($task->left == 0 and $task->status != 'cancel', 'status', 'equal', 'done')
            ->where('id')->eq((int)$taskID)->exec();
        if($this->post->story != false) $this->loadModel('story')->setStage($this->post->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }
    
    /* 删除一个任务。*/
    public function delete($taskID)
    {
        $story = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('story');
        $this->dao->delete()->from(TABLE_TASK)->where('id')->eq((int)$taskID)->limit(1)->exec();
        if($story) $this->loadModel('story')->setStage($story);
        return;
    }

    /* 通过id获取一个任务信息。*/
    public function getById($taskID)
    {
        $task = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t3.realname AS ownerRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')
            ->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')
            ->on('t1.owner = t3.account')
            ->where('t1.id')->eq((int)$taskID)
            ->fetch();
        if(!$task) return false;
        $task->files = $this->loadModel('file')->getByObject('task', $taskID);
        return $this->computeDelay($task);
    }
    
    /* 获得某一个项目的任务列表。*/
    public function getProjectTasks($projectID, $orderBy = 'statusasc, iddesc', $pager = null)
    {
        $orderBy = str_replace('status', 'statusCustom', $orderBy);
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t3.realname AS ownerRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')
            ->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')
            ->on('t1.owner = t3.account')
            ->where('t1.project')->eq((int)$projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        if($tasks) return $this->computeDelays($tasks);
        return false;
    }

    /* 获得某一个项目的任务id=>name列表。*/
    public function getProjectTaskPairs($projectID, $orderBy = 'iddesc')
    {
        $tasks = array('' => '');
        $stmt = $this->dao->select('t1.id, t1.name, t2.realname AS ownerRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.owner = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->orderBy($orderBy)
            ->query();
        while($task = $stmt->fetch()) $tasks[$task->id] = "$task->id:$task->ownerRealName:$task->name";
        return $tasks;
    }

    /* 获得用户的任务列表。*/
    public function getUserTasks($account, $status = 'all')
    {
        $tasks = $this->dao->select('t1.*, t2.id as projectID, t2.name as projectName, t3.id as storyID, t3.title as storyTitle')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->leftjoin(TABLE_STORY)->alias('t3')
            ->on('t1.story = t3.id')
            ->where('t1.owner')->eq($account)
            ->onCaseOf($status != 'all')->andWhere('t1.status')->in($status)->endCase()
            ->fetchAll();
        if($tasks) return $this->computeDelays($tasks);
        return array();
    }

    /* 获得用户的任务id=>name列表。*/
    public function getUserTaskPairs($account, $status = 'all')
    {
        $tasks = array();
        $sql = $this->dao->select('t1.id, t1.name, t2.name as project')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.owner')->eq($account);
        if($status != 'all') $sql->andwhere('t1.status')->in($status);
        $stmt = $sql->query();
        while($task = $stmt->fetch())
        {
            $tasks[$task->id] = $task->project . ' / ' . $task->name;
        }
        return $tasks;
    }

    /* 获得story对应的task id=>name列表。*/
    public function getStoryTaskPairs($storyID, $projectID = 0)
    {
        $sql = $this->dao->select('id, name')
            ->from(TABLE_TASK)
            ->where('story')->eq((int)$storyID);
        if($projectID > 0) $sql->andwhere('project')->eq((int)$projectID);
        return $sql->fetchPairs();
    }

    /* 获得story对应的task数量。*/
    public function getStoryTaskCounts($stories, $projectID = 0)
    {
        $sql = $this->dao->select('story, COUNT(*) AS tasks')
            ->from(TABLE_TASK)
            ->where('story')->in($stories);
        if($projectID > 0) $sql->andwhere('project')->eq((int)$projectID);
        $sql->groupBy('story');
        $taskCounts = $sql->fetchPairs();
        foreach($stories as $storyID) if(!isset($taskCounts[$storyID])) $taskCounts[$storyID] = 0;
        return $taskCounts;
    }

    /* 计算延期的天数。*/
    private function computeDelays($tasks)
    {
        $today = helper::today();
        foreach($tasks as $task)
        {
            if($task->deadline != '0000-00-00')
            {
                $delay = helper::diffDate($today, $task->deadline);
                if($delay > 0) $task->delay = $delay;
            }
        }
        return $tasks;
    }

    /* 计算延期的天数。*/
    private function computeDelay($task)
    {
        $today = helper::today();
        if($task->deadline != '0000-00-00')
        {
            $delay = helper::diffDate($today, $task->deadline);
            if($delay > 0) $task->delay = $delay;
        }
        return $task;
    }

}
