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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class taskModel extends model
{
    /* 新增一个任务。*/
    public function create($projectID)
    {
        $task = fixer::input('post')
            ->striptags('name')
            ->specialChars('desc')
            ->cleanFloat('estimate')
            ->add('project', (int)$projectID)
            ->setIF($this->post->estimate == '', 'estimate', 0)
            ->setIF($this->post->story    == '', 'story', 0)
            ->get();
        $task->left = $task->estimate;

        $this->dao->insert(TABLE_TASK)->data($task)
            ->autoCheck()
            ->check('name', 'notempty')
            ->checkIF($task->estimate != '', 'estimate', 'float')
            ->exec();
        if(!dao::isError())
        {
            //$this->dao->update(TABLE_STORY)->set('status')->eq('doing')->where('id')->eq()
            return $this->dao->lastInsertID();
        }
    }

    /* 更新一个任务。*/
    public function update($taskID)
    {
        $oldTask = $this->findByID($taskID);
         $task = fixer::input('post')
            ->striptags('name')
            ->specialChars('desc')
            ->cleanFloat('estimate, left, consumed')
            ->setIF($this->post->story    == '', 'story', 0)
            ->setIF($this->post->estimate == '', 'estimate', 0)
            ->setIF($this->post->left     == '', 'left', 0)
            ->setIF($this->post->consumed == '', 'consumed', 0)
            ->remove('comment')
            ->get();
        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->check('name', 'notempty')
            ->checkIF($task->estimate != '', 'estimate', 'float')
            ->checkIF($task->left     != '', 'left',     'float')
            ->checkIF($task->consumed != '', 'consumed', 'float')
            ->where('id')->eq((int)$taskID)->exec();
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }
    
    /* 删除一个任务。*/
    public function delete($taskID)
    {
        return $this->dao->delete()->from(TABLE_TASK)->where('id')->eq((int)$taskID)->limit(1)->exec();
    }

    /* 通过id获取一个任务信息。*/
    public function findByID($taskID)
    {
        return $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t3.realname AS ownerRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')
            ->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')
            ->on('t1.owner = t3.account')
            ->where('t1.id')->eq((int)$taskID)
            ->fetch();
    }
    
    /* 获得某一个项目的任务列表。*/
    public function getProjectTasks($projectID, $orderBy = 'status|desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t3.realname AS ownerRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')
            ->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')
            ->on('t1.owner = t3.account')
            ->where('t1.project')->eq((int)$projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /* 获得某一个项目的任务id=>name列表。*/
    public function getProjectTaskPairs($projectID, $orderBy = 'id|desc')
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
        $sql = $this->dao->select('t1.*, t2.id as projectID, t2.name as projectName, t3.id as storyID, t3.title as storyTitle')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->leftjoin(TABLE_STORY)->alias('t3')
            ->on('t1.story = t3.id')
            ->where('t1.owner')->eq($account);
        if($status != 'all') $sql->andwhere('t1.status')->in($status);
        return $sql->fetchAll();
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
}
