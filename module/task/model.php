<?php
/**
 * The model file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class taskModel extends model
{
    const CUSTOM_STATUS_ORDER = 'wait,doing,done,cancel';

    /**
     * Create a task.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function create($projectID)
    {
        $tasksID = array();
        foreach($this->post->assignedTo as $assignedTo)
        {
            $task = fixer::input('post')
                ->striptags('name')
                ->add('project', (int)$projectID)
                ->setDefault('estimate, left, story', 0)
                ->setDefault('deadline', '0000-00-00')
                ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
                ->setForce('assignedTo', $assignedTo)
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
                $tasksID[$assignedTo] = $taskID;
            }
            else return false;
        }
        return $tasksID;
    }

    /**
     * Update a task.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function update($taskID)
    {
        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')
            ->striptags('name')
            ->setDefault('story, estimate, left, consumed', 0)
            ->setIF($this->post->story != false and $this->post->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setIF($this->post->status == 'done', 'left', 0)
            ->setIF($this->post->consumed > 0 and $this->post->left > 0 and $this->post->status == 'wait', 'status', 'doing')
            ->remove('comment,files,labels')
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
    
    /**
     * Change status of a task and return the diff info.
     * 
     * @param  int    $taskID 
     * @access public
     * @return array the diff info.
     */
    public function changeStatus($taskID)
    {
        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')
            ->setDefault('estimate, left, consumed', 0)
            ->setIF($this->post->consumed > 0 and $this->post->left > 0 and $this->post->status == 'wait', 'status', 'doing')
            ->remove('comment')
            ->get();
        $task->statusCustom = strpos(self::CUSTOM_STATUS_ORDER, $task->status) + 1;

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheckIF($task->status != 'cancel', $this->config->task->start->requiredFields, 'notempty')
            ->checkIF($task->estimate != false, 'estimate', 'float')
            ->checkIF($task->left     != false, 'left',     'float')
            ->checkIF($task->consumed != false, 'consumed', 'float')
            ->checkIF($task->status == 'done', 'consumed', 'notempty')
            ->checkIF($task->left == 0 and $task->status != 'cancel', 'status', 'equal', 'done')
            ->where('id')->eq((int)$taskID)->exec();
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Get task info by Id.
     * 
     * @param  int    $taskID 
     * @access public
     * @return object|bool
     */
    public function getById($taskID)
    {
        $task = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')
            ->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')
            ->on('t1.assignedTo = t3.account')
            ->where('t1.id')->eq((int)$taskID)
            ->fetch();
        if(!$task) return false;
        if($task->mailto)
        {
            $task->mailto = ltrim(trim($task->mailto), ',');  // remove the first ,
            $task->mailto = str_replace(' ', '', $task->mailto);
            $task->mailto = rtrim($task->mailto, ',') . ',';
            $task->mailto = str_replace(',', ', ', $task->mailto);
        }
        $task->files = $this->loadModel('file')->getByObject('task', $taskID);
        return $this->processTask($task);
    }
    
    /**
     * Get tasks of a project.
     * 
     * @param  int    $projectID 
     * @param  string $status       all|needConfirm|wait|doing|done|cancel
     * @param  string $orderBy 
     * @param  object $pager 
     * @access public
     * @return array|bool
     */
    public function getProjectTasks($projectID, $status = 'all', $orderBy = 'status_asc, id_desc', $pager = null)
    {
        $orderBy = str_replace('status', 'statusCustom', $orderBy);
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status == 'needConfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($status != 'all' and $status != 'needConfirm')->andWhere('t1.status')->in($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        if($tasks) return $this->processTasks($tasks);
        return false;
    }

    /**
     * Get project taskes paris.1
     * 
     * @param  int    $projectID 
     * @param  string $status
     * @param  string $orderBy 
     * @access public
     * @return array
     */
    public function getProjectTaskPairs($projectID, $status = 'all', $orderBy = 'id_desc')
    {
        $tasks = array('' => '');
        $stmt = $this->dao->select('t1.id, t1.name, t2.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.assignedTo = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->orderBy($orderBy)
            ->query();
        while($task = $stmt->fetch()) $tasks[$task->id] = "$task->id:$task->assignedToRealName:$task->name";
        return $tasks;
    }

    /**
     * Get tasks of a user.
     * 
     * @param  string $account 
     * @param  string $status 
     * @access public
     * @return array
     */
    public function getUserTasks($account, $status = 'all')
    {
        $tasks = $this->dao->select('t1.*, t2.id as projectID, t2.name as projectName, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->leftjoin(TABLE_STORY)->alias('t3')
            ->on('t1.story = t3.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->fetchAll();
        if($tasks) return $this->processTasks($tasks);
        return array();
    }

    /**
     * Get tasks pairs of a user.
     * 
     * @param  string $account 
     * @param  string $status 
     * @access public
     * @return array
     */
    public function getUserTaskPairs($account, $status = 'all')
    {
        $tasks = array();
        $sql = $this->dao->select('t1.id, t1.name, t2.name as project')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0);
        if($status != 'all') $sql->andwhere('t1.status')->in($status);
        $stmt = $sql->query();
        while($task = $stmt->fetch())
        {
            $tasks[$task->id] = $task->project . ' / ' . $task->name;
        }
        return $tasks;
    }

    /**
     * Get task pairs of a story.
     * 
     * @param  int    $storyID 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getStoryTaskPairs($storyID, $projectID = 0)
    {
         return $this->dao->select('id, name')
            ->from(TABLE_TASK)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchPairs();
    }

    /**
     * Get counts of some stories's taskes.
     * 
     * @param  array  $stories 
     * @param  int    $projectID 
     * @access public
     * @return int
     */
    public function getStoryTaskCounts($stories, $projectID = 0)
    {
        $taskCounts = $this->dao->select('story, COUNT(*) AS tasks')
            ->from(TABLE_TASK)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->groupBy('story')
            ->fetchPairs();
        foreach($stories as $storyID) if(!isset($taskCounts[$storyID])) $taskCounts[$storyID] = 0;
        return $taskCounts;
    }

    /**
     * Batch process taskes.
     * 
     * @param  int    $tasks 
     * @access private
     * @return void
     */
    private function processTasks($tasks)
    {
        $today = helper::today();
        foreach($tasks as $task)
        {
            /* Delayed or not. */
            if($task->status !== 'done' and $task->status !== 'cancel')
            {   
                if($task->deadline != '0000-00-00')
                {
                    $delay = helper::diffDate($today, $task->deadline);
                    if($delay > 0) $task->delay = $delay;
                }
            }    
	    
            /* Story changed or not. */
            $task->needConfirm = false;
            if($task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion)
            {
                $task->needConfirm = true;
            }
        }
        return $tasks;
    }

    /**
     * Process a task, judge it's status.
     * 
     * @param  object    $task 
     * @access private
     * @return object
     */
    private function processTask($task)
    {
        $today = helper::today();
       
        /* Delayed or not?. */
        if($task->status !== 'done' and $task->status !== 'cancel')
        {
            if($task->deadline != '0000-00-00')
            {
                $delay = helper::diffDate($today, $task->deadline);
            	if($delay > 0) $task->delay = $delay;            
	        } 
	    }
	    
        /* Story changed or not. */
        $task->needConfirm = false;
        if($task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion)
        {
            $task->needConfirm = true;
        }
        return $task;
    }
}
