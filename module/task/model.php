<?php
/**
 * The model file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
    const CUSTOM_STATUS_ORDER = 'wait,doing,done,cancel,closed';

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
        $taskFile = '';
        foreach($this->post->assignedTo as $assignedTo)
        {
            $task = fixer::input('post')
                ->striptags('name')
                ->add('project', (int)$projectID)
                ->setDefault('estimate, left, story', 0)
                ->setDefault('estStarted', '0000-00-00')
                ->setDefault('deadline', '0000-00-00')
                ->setDefault('status', 'wait')
                ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
                ->setForce('assignedTo', $assignedTo)
                ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
                ->setDefault('openedBy',   $this->app->user->account)
                ->setDefault('openedDate', helper::now())
                ->remove('after,files,labels')
                ->get();

            if($assignedTo) $task->assignedDate = helper::now();

            $this->setStatus($task);

            $this->dao->insert(TABLE_TASK)->data($task)
                ->autoCheck()
                ->batchCheck($this->config->task->create->requiredFields, 'notempty')
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->checkIF($task->deadline != '0000-00-00', 'deadline', 'ge', $task->estStarted)
                ->exec();

            if(!dao::isError())
            {
                $taskID = $this->dao->lastInsertID();
                if($this->post->story) $this->loadModel('story')->setStage($this->post->story);
                if(!empty($taskFile))
                {
                    $taskFile->objectID = $taskID;
                    $this->dao->insert(TABLE_FILE)->data($taskFile)->exec();
                }
                else
                {
                    $taskFileTitle = $this->loadModel('file')->saveUpload('task', $taskID);
                    $taskFile = $this->dao->select('*')->from(TABLE_FILE)->where('id')->eq(key($taskFileTitle))->fetch();
                    unset($taskFile->id);
                }
                $tasksID[$assignedTo] = $taskID;
            }
            else
            {
                return false;
            }
        }
        return $tasksID;
    }

    /**
     * Create a batch task.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function batchCreate($projectID)
    {
        $now   = helper::now();
        $tasks = fixer::input('post')->get();
        $mails = array();
        for($i = 0; $i < $this->config->task->batchCreate; $i++)
        {
            if($tasks->type[$i] != '' and $tasks->name[$i] != '' and $tasks->pri[$i] != 0)
            {
                $data[$i]->story        = $tasks->story[$i] != 'ditto' ? $tasks->story[$i] : ($i == 0 ? 0 : $data[$i-1]->story);
                $data[$i]->type         = $tasks->type[$i] == 'ditto' ? ($i == 0 ? '' : $data[$i-1]->type) : $tasks->type[$i];
                $data[$i]->name         = $tasks->name[$i];
                $data[$i]->desc         = $tasks->desc[$i];
                $data[$i]->assignedTo   = $tasks->assignedTo[$i];
                $data[$i]->pri          = $tasks->pri[$i];
                $data[$i]->estimate     = $tasks->estimate[$i];
                $data[$i]->left         = $tasks->estimate[$i];
                $data[$i]->project      = $projectID;
                $data[$i]->deadline     = '0000-00-00';
                $data[$i]->status       = 'wait';
                $data[$i]->openedBy     = $this->app->user->account;
                $data[$i]->openedDate   = $now;
                $data[$i]->statusCustom = strpos(self::CUSTOM_STATUS_ORDER, 'wait') + 1;
                if($tasks->story[$i] != '') $data[$i]->storyVersion = $this->loadModel('story')->getVersion($data[$i]->story);
                if($tasks->assignedTo[$i] != '') $data[$i]->assignedDate = $now;

                $this->dao->insert(TABLE_TASK)->data($data[$i])
                    ->autoCheck()
                    ->batchCheck($this->config->task->create->requiredFields, 'notempty')
                    ->checkIF($data[$i]->estimate != '', 'estimate', 'float')
                    ->exec();

                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }

                $taskID = $this->dao->lastInsertID();
                if($tasks->story[$i] != false) $this->story->setStage($tasks->story[$i]);
                $actionID = $this->loadModel('action')->create('task', $taskID, 'Opened', '');
                $mails[$i]->taskID  = $taskID;
                $mails[$i]->actionID = $actionID;
            }
            else
            {
                unset($tasks->story[$i]);
                unset($tasks->type[$i]);
                unset($tasks->name[$i]);
                unset($tasks->desc[$i]);
                unset($tasks->assignedTo[$i]);
                unset($tasks->pri[$i]);
                unset($tasks->estimate[$i]);
            }
        }
        return $mails;
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
        $now     = helper::now();
        $task    = fixer::input('post')
            ->striptags('name')
            ->setDefault('story, estimate, left, consumed', 0)
            ->setDefault('deadline', '0000-00-00')
            ->setIF($this->post->story != false and $this->post->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))

            ->setIF($this->post->status == 'done', 'left', 0)
            ->setIF($this->post->status == 'done'   and !$this->post->finishedBy,   'finishedBy',   $this->app->user->account)
            ->setIF($this->post->status == 'done'   and !$this->post->finishedDate, 'finishedDate', $now)

            ->setIF($this->post->status == 'cancel' and !$this->post->canceledBy,   'canceledBy',   $this->app->user->account)
            ->setIF($this->post->status == 'cancel' and !$this->post->canceledDate, 'canceledDate', $now)
            ->setIF($this->post->status == 'cancel', 'assignedTo',   $oldTask->openedBy)
            ->setIF($this->post->status == 'cancel', 'assignedDate', $now)

            ->setIF($this->post->status == 'closed' and !$this->post->closedBy,     'closedBy',     $this->app->user->account)
            ->setIF($this->post->status == 'closed' and !$this->post->closedDate,   'closedDate',   $now)
            ->setIF($this->post->consumed > 0 and $this->post->left > 0 and $this->post->status == 'wait', 'status', 'doing')

            ->setIF($this->post->assignedTo != $oldTask->assignedTo, 'assignedDate', $now)

            ->setIF($this->post->status == 'wait' and $this->post->left == $oldTask->left and $this->post->consumed == 0, 'left', $this->post->estimate)

            ->add('lastEditedBy',   $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->remove('comment,files,labels')
            ->get();
        $task->statusCustom = strpos(self::CUSTOM_STATUS_ORDER, $task->status) + 1;

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheckIF($task->status != 'cancel', $this->config->task->edit->requiredFields, 'notempty')

            ->checkIF($task->estimate != false, 'estimate', 'float')
            ->checkIF($task->left     != false, 'left',     'float')
            ->checkIF($task->consumed != false, 'consumed', 'float')
            ->checkIF($task->status != 'wait' and $task->left == 0 and $task->status != 'cancel' and $task->status != 'closed', 'status', 'equal', 'done')

            ->batchCheckIF($task->status == 'wait' or $task->status == 'doing', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')

            ->checkIF($task->status == 'done', 'consumed', 'notempty')
            ->checkIF($task->status == 'done' and $task->closedReason, 'closedReason', 'equal', 'done')
            ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')

            ->checkIF($task->status == 'closed', 'closedReason', 'notempty')
            ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
            ->where('id')->eq((int)$taskID)->exec();

        if($this->post->story != false) $this->loadModel('story')->setStage($this->post->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Batch update task.
     * 
     * @access public
     * @return void
     */
    public function batchUpdate()
    {
        $tasks      = array();
        $allChanges = array();
        $now        = helper::now();
        $taskIDList = $this->post->taskIDList ? $this->post->taskIDList : array();

        /* Adjust whether the post data is complete, if not, remove the last element of $taskIDList. */
        if($this->session->showSuhosinInfo) array_pop($taskIDList);

        /* Initialize tasks from the post data.*/
        if(!empty($taskIDList))
        {
            foreach($taskIDList as $taskID)
            {
                $oldTask = $this->getById($taskID);

                $task->name           = htmlspecialchars($this->post->names[$taskID]);
                $task->module         = $this->post->modules[$taskID];
                $task->assignedTo     = $this->post->assignedTos[$taskID];
                $task->type           = $this->post->types[$taskID];
                $task->status         = $this->post->statuses[$taskID];
                $task->pri            = $this->post->pris[$taskID];
                $task->estimate       = $this->post->estimates[$taskID];
                $task->consumed       = $this->post->consumeds[$taskID];
                $task->left           = $this->post->lefts[$taskID];
                $task->finishedBy     = $this->post->finishedBys[$taskID];
                $task->canceledBy     = $this->post->canceledBys[$taskID];
                $task->closedBy       = $this->post->closedBys[$taskID];
                $task->closedReason   = $this->post->closedReasons[$taskID];
                $task->finishedDate   = "";
                $task->canceledDate   = "";
                $task->closedDate     = "";
                $task->lastEditedBy   = $this->app->user->account;
                $task->lastEditedDate = $now;
                if(isset($this->post->assignedTos[$taskID])) 
                {
                    $task->assignedDate = $this->post->assignedTos[$taskID] == $oldTask->assignedTo ? $oldTask->assignedDate : $now;
                }

                switch($task->status)
                {
                    case 'done':
                    {
                        $task->left = 0;
                        if(!$task->finishedBy)   $task->finishedBy = $this->app->user->account;
                        if(!$task->finishedDate) $task->finishedDate = $now;
                    }
                    break;
                    case 'cancel':
                    {
                        $task->assignedTo   = $oldTask->openedBy;
                        $task->assignedDate = $now;

                        if(!$task->canceledBy)   $task->canceledBy   = $this->app->user->account;
                        if(!$task->canceledDate) $task->canceledDate = $now;
                    }
                    break;
                    case 'closed':
                    {
                        if(!$task->closedBy)   $task->closedBy   = $this->app->user->account;
                        if(!$task->closedDate) $task->closedDate = $now;
                    }
                    break;
                    case 'wait':
                    {
                        if($task->consumed > 0 and $task->left > 0) $task->status = 'doing';
                        if($task->left == $oldTask->left and $task->consumed == 0) $task->left = $task->estimate;
                    }
                    default:break;
                }
                if($task->assignedTo) $task->assignedDate = $now;

                $tasks[$taskID] = $task;
                unset($task);
            }

            /* Update task data. */
            foreach($tasks as $taskID => $task)
            {
                $oldTask = $this->getById($taskID);
                $this->dao->update(TABLE_TASK)->data($task)
                    ->autoCheck()
                    ->batchCheckIF($task->status != 'cancel', $this->config->task->edit->requiredFields, 'notempty')

                    ->checkIF($task->estimate != false, 'estimate', 'float')
                    ->checkIF($task->consumed != false, 'consumed', 'float')
                    ->checkIF($task->left     != false, 'left',     'float')
                    ->checkIF($task->left == 0 and $task->status != 'cancel' and $task->status != 'closed' and $task->consumed != 0, 'status', 'equal', 'done')

                    ->batchCheckIF($task->status == 'wait' or $task->status == 'doing', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')

                    ->checkIF($task->status == 'done', 'consumed', 'notempty')
                    ->checkIF($task->status == 'done' and $task->closedReason, 'closedReason', 'equal', 'done')
                    ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')

                    ->checkIF($task->status == 'closed', 'closedReason', 'notempty')
                    ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
                    ->where('id')->eq((int)$taskID)
                    ->exec();

                if($oldTask->story != false) $this->loadModel('story')->setStage($oldTask->story);
                if(!dao::isError()) 
                {
                    $allChanges[$taskID] = common::createChanges($oldTask, $task);
                }
                else
                {
                    die(js::error('task#' . $taskID . dao::getError(true)));
                }
            }
        }

        return $allChanges;
    }

    /**
     * Assign a task to a user again.
     * 
     * @param  int    $taskID 
     * @access public
     * @return void
     */
    public function assign($taskID)
    {
        $now = helper::now();
        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')
            ->cleanFloat('left')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_TASK)
            ->data($task)
            ->autoCheck()
            ->check('left', 'float')
            ->where('id')->eq($taskID)->exec(); 

        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Start a task.
     * 
     * @param  int      $taskID 
     * @access public
     * @return void
     */
    public function start($taskID)
    {
        $oldTask = $this->getById($taskID);
        $now     = helper::now();
        $task = fixer::input('post')
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now) 
            ->remove('comment')->get();
        $this->setStatus($task);

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->check('consumed,left', 'float')
            ->where('id')->eq((int)$taskID)->exec();

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Finish a task.
     * 
     * @param  int      $taskID 
     * @access public
     * @return void
     */
    public function finish($taskID)
    {
        $oldTask = $this->getById($taskID);
        $now     = helper::now();
        $task = fixer::input('post')
            ->setDefault('left', 0)
            ->setDefault('assignedTo',   $oldTask->openedBy)
            ->setDefault('assignedDate', $now)
            ->setDefault('status', 'done')
            ->setDefault('finishedBy, lastEditedBy', $this->app->user->account)
            ->setDefault('finishedDate, lastEditedDate', $now) 
            ->remove('comment')->get();
        $this->setStatus($task);

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->check('consumed', 'notempty')
            ->where('id')->eq((int)$taskID)->exec();

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }
    
    /**
     * Close a task.
     * 
     * @param  int      $taskID 
     * @access public
     * @return void
     */
    public function close($taskID)
    {
        $oldTask = $this->getById($taskID);
        $now     = helper::now();
        $task = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('assignedTo', 'closed')
            ->setDefault('assignedDate', $now)
            ->setDefault('closedBy, lastEditedBy', $this->app->user->account)
            ->setDefault('closedDate, lastEditedDate', $now) 
            ->setIF($oldTask->status == 'done',   'closedReason', 'done') 
            ->setIF($oldTask->status == 'cancel', 'closedReason', 'cancel') 
            ->remove('_recPerPage')
            ->remove('comment')->get();
        $this->setStatus($task);

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('id')->eq((int)$taskID)->exec();

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Cancel a task.
     * 
     * @param  int      $taskID 
     * @access public
     * @return void
     */
    public function cancel($taskID)
    {
        $oldTask = $this->getById($taskID);
        $now     = helper::now();
        $task = fixer::input('post')
            ->setDefault('status', 'cancel')
            ->setDefault('assignedTo', $oldTask->openedBy)
            ->setDefault('assignedDate', $now)
            ->setDefault('finishedBy', '')
            ->setDefault('finishedDate', '0000-00-00')
            ->setDefault('canceledBy, lastEditedBy', $this->app->user->account)
            ->setDefault('canceledDate, lastEditedDate', $now) 
            ->remove('comment')->get();
        $this->setStatus($task);

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('id')->eq((int)$taskID)->exec();

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Activate a task.
     * 
     * @param  int      $taskID 
     * @access public
     * @return void
     */
    public function activate($taskID)
    {
        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')
            ->setDefault('left', 0)
            ->setDefault('status', 'doing')
            ->setDefault('finishedBy, canceledBy, closedBy, closedReason', '')
            ->setDefault('finishedDate, canceledDate, closedDate', '0000-00-00')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->remove('comment')->get();
        $this->setStatus($task);

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->check('left', 'notempty')
            ->where('id')->eq((int)$taskID)->exec();

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);

    }

    /**
     * Get task info by Id.
     * 
     * @param  int    $taskID 
     * @param  bool   $setImgSize
     * @access public
     * @return object|bool
     */
    public function getById($taskID, $setImgSize = false)
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
        if($setImgSize) $task->desc = $this->loadModel('file')->setImgSize($task->desc);
        if($task->assignedTo == 'closed') $task->assignedToRealName = 'Closed';
        foreach($task as $key => $value) if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $task->$key = '';
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
     * Get tasks list of a project.
     * 
     * @param  int           $projectID 
     * @param  array|string  $moduleIds 
     * @param  string        $status 
     * @param  string        $orderBy 
     * @param  object        $pager 
     * @access public
     * @return array
     */
    public function getTasksByModule($projectID = 0, $moduleIds = 0, $orderBy = 'id_desc', $pager = null)
    {
        $orderBy = str_replace('status', 'statusCustom', $orderBy);
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.project')->eq((int)$projectID)
            ->beginIF(!empty($moduleIds))->andWhere('t1.module')->in($moduleIds)->fi() 
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task');

        if($tasks) return $this->processTasks($tasks);
        return array();
    }
    
    /**
     * Get tasks of a project.
     * 
     * @param  int    $projectID 
     * @param  string $status       all|needConfirm|wait|doing|done|cancel
     * @param  string $type 
     * @param  object $pager 
     * @access public
     * @return array
     */
    public function getProjectTasks($projectID, $type = 'all', $orderBy = 'status_asc, id_desc', $pager = null)
    {
        $orderBy = str_replace('status', 'statusCustom', $orderBy);
        $type    = strtolower($type);
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'finishedbyme')->andWhere('t1.finishedby')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'delayed')->andWhere('deadline')->between('1970-1-1', helper::now())->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(strpos('all|needconfirm|assignedtome|delayed|finishedbyme', $type) === false)->andWhere('t1.status')->in($type)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task');

        if($tasks) return $this->processTasks($tasks);
        return array();
    }

    /**
     * Get project tasks pairs.
     * 
     * @param  int    $projectID 
     * @param  string $status
     * @param  string $orderBy 
     * @access public
     * @return array
     */
    public function getProjectTaskPairs($projectID, $status = 'all', $orderBy = 'finishedBy, id_desc')
    {
        $tasks = array('' => '&nbsp;');
        $stmt = $this->dao->select('t1.id, t1.name, t2.realname AS finishedByRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.finishedBy = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->orderBy($orderBy)
            ->query();
        while($task = $stmt->fetch()) $tasks[$task->id] = "$task->id:$task->finishedByRealName:$task->name";
        return $tasks;
    }

    /**
     * Get tasks of a user.
     * 
     * @param  string $account 
     * @param  string $type     the query type 
     * @param  int    $limit   
     * @param  object $pager   
     * @access public
     * @return array
     */
    public function getUserTasks($account, $type = 'assignedto', $limit = 0, $pager = null)
    {
        $type = strtolower($type);
        $tasks = $this->dao->select('t1.*, t2.id as projectID, t2.name as projectName, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->leftjoin(TABLE_STORY)->alias('t3')
            ->on('t1.story = t3.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($type == 'openedby')->andWhere('t1.openedBy')->eq($account)->fi()
            ->beginIF($type == 'assignedto')->andWhere('t1.assignedto')->eq($account)->fi()
            ->beginIF($type == 'finishedby')->andWhere('t1.finishedby')->eq($account)->fi()
            ->beginIF($type == 'closedby')->andWhere('t1.closedby')->eq($account)->fi()
            ->beginIF($type == 'canceledby')->andWhere('t1.canceledby')->eq($account)->fi()
            ->orderBy('id desc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task');

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
     * Get counts of some stories' tasks.
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
     * Batch process tasks.
     * 
     * @param  int    $tasks 
     * @access private
     * @return void
     */
    public function processTasks($tasks)
    {
        foreach($tasks as $task)
        {
            $task = $this->processTask($task);
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
    public function processTask($task)
    {
        $today = helper::today();
       
        /* Delayed or not?. */
        if($task->status !== 'done' and $task->status !== 'cancel')
        {
            if($task->deadline != '0000-00-00')
            {
                if($task->status == 'closed' && $task->closedReason == 'done')
                {
                    if($task->finishedDate == '') $delay = helper::diffDate(substr($task->closedDate, 0, 10), $task->deadline);
                    if($task->finishedDate != '') $delay = helper::diffDate(substr($task->finishedDate, 0, 10), $task->deadline);
                }
                else
                {
                    $delay = helper::diffDate($today, $task->deadline);
                }
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

    /**
     * Set the status field of a task.
     * 
     * @param  object $task 
     * @access private
     * @return void
     */
    public function setStatus($task)
    {
        $task->statusCustom = strpos(self::CUSTOM_STATUS_ORDER, $task->status) + 1;
    }
    
    /**
     * Merge the default chart settings and the settings of current chart.
     * 
     * @param  string    $chartType 
     * @access public
     * @return void
     */
    public function mergeChartOption($chartType)
    {
        $chartOption  = $this->lang->task->report->$chartType;
        $commonOption = $this->lang->task->report->options;

        $chartOption->graph->caption = $this->lang->task->report->charts[$chartType];
        if(!isset($chartOption->swf))    $chartOption->swf    = $commonOption->swf;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        /* merge configuration */
        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
    }
    
    /**
     * Get report data of tasks per project 
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerProject()
    {
        $datas = $this->dao->select('project as name, count(*) as value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('project')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        $projects = $this->loadModel('project')->getPairs('all');
        foreach($datas as $projectID => $data) $data->name = isset($projects[$projectID]) ? $projects[$projectID] : $this->lang->report->undefined;
        return $datas;
    }

    /**
     * Get report data of tasks per module 
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerModule()
    {
        $datas = $this->dao->select('module as name, count(*) as value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('module')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        $modules = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(array_keys($datas))->fetchPairs();
        foreach($datas as $moduleID => $data) $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';
        return $datas;
    }

    /**
     * Get report data of tasks per assignedTo 
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerAssignedTo()
    {
        $datas = $this->dao->select('assignedTo AS name, count(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('assignedTo')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];
        return $datas;
    }

    /**
     * Get report data of tasks per type 
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerType()
    {
        $datas = $this->dao->select('type AS  name, count(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('type')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();
        foreach($datas as $type => $data) if(isset($this->lang->task->typeList[$type])) $data->name = $this->lang->task->typeList[$type];
        return $datas;
    }

    /**
     * Get report data of tasks per priority
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerPri()
    {
        return $this->dao->select('pri AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('pri')
            ->orderBy('value DESC')
            ->fetchAll('name');
    }
   
    /**
     * Get report data of tasks per deadline 
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerDeadline()
    {
        return $this->dao->select('deadline AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('deadline')
            ->orderBy('value DESC')
            ->fetchAll('name');
    }

    /**
     * Get report data of tasks per estimate 
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerEstimate()
    {
        return $this->dao->select('estimate AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('estimate')
            ->orderBy('value DESC')
            ->fetchAll('name');
    }

    /**
     * Get report data of tasks per left
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerLeft()
    {
        return $this->dao->select('`left` AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('`left`')
            ->orderBy('value DESC')
            ->fetchAll('name');
    }

    /**
     * Get report data of tasks per consumed
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerConsumed()
    {
        return $this->dao->select('consumed AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('consumed')
            ->orderBy('value DESC')
            ->fetchAll('name');
    }

    /**
     * Get report data of tasks per finishedBy 
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerFinishedBy()
    {
        $datas = $this->dao->select('finishedBy AS name, COUNT(finishedBy) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->andWhere('finishedBy')->ne('')
            ->groupBy('finishedBy')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) if(isset($this->users[$account])) $data->name = $this->users[$account];

        return $datas;
    }

    /**
     * Get report data of tasks per closed reason  
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerClosedReason()
    {
        $datas = $this->dao->select('closedReason AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('closedReason')
            ->orderBy('value DESC')
            ->fetchAll('name');

        foreach($datas as $closedReason => $data)
        {
            if(isset($this->lang->task->reasonList[$closedReason]))
            {
                $data->name = $this->lang->task->reasonList[$closedReason];
            }
        }
        return $datas;
    }

    /**
     * Get report data of finished tasks per day
     * 
     * @access public
     * @return array
     */
    public function getDataOffinishedTasksPerDay()
    {
        $datas= $this->dao->select('DATE_FORMAT(finishedDate, "%Y-%m-%d") AS date, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('date')
            ->having('date != "0000-00-00"')
            ->orderBy('finishedDate')
            ->fetchAll();

        /* Change data to name, because the task table has name field, conflicts. */
        foreach($datas as $data)
        {
            $data->name = $data->date;
            unset($data->date);
        }
        return $datas;
    }

    /**
     * Get report data of status
     * 
     * @access public
     * @return array
     */
    public function getDataOftasksPerStatus()
    {
        $datas = $this->dao->select('status AS name, COUNT(status) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->session->taskQueryCondition)
            ->groupBy('status')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas)return array();
        foreach($datas as $status => $data)
        {
            $data->name = $this->lang->task->statusList[$status];
        }
        return $datas;
    }

    /**
     * Judge an action is clickable or not.
     * 
     * @param  object    $task 
     * @param  string    $action 
     * @access public
     * @return bool
     */
    public function isClickable($task, $action)
    {
        $action = strtolower($action);

        if($action == 'assignto') return $task->status != 'closed' and $task->status != 'cancel';
        if($action == 'start')    return $task->status != 'doing'  and $task->status != 'closed' and $task->status != 'cancel';
        if($action == 'finish')   return $task->status != 'done'   and $task->status != 'closed' and $task->status != 'cancel';
        if($action == 'close')    return $task->status == 'done'   or  $task->status == 'cancel';
        if($action == 'activate') return $task->status == 'done'   or  $task->status == 'closed'  or $task->status == 'cancel' ;
        if($action == 'cancel')   return $task->status != 'done  ' and $task->status != 'closed' and $task->status != 'cancel';

        return true;
    }
}
