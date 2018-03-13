<?php
/**
 * The model file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: model.php 5154 2013-07-16 05:51:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class taskModel extends model
{
    /**
     * Create a task.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID)
    {
        $taskIdList = array();
        $taskFiles  = array();
        $this->loadModel('file');
        $task = fixer::input('post')
            ->add('project', (int)$projectID)
            ->setDefault('estimate, left, story', 0)
            ->setDefault('status', 'wait')
            ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setDefault('estStarted', '0000-00-00')
            ->setDefault('deadline', '0000-00-00')
            ->setIF(strpos($this->config->task->create->requiredFields, 'estStarted') !== false, 'estStarted', $this->post->estStarted)
            ->setIF(strpos($this->config->task->create->requiredFields, 'deadline') !== false, 'deadline', $this->post->deadline)
            ->setDefault('openedBy',   $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->stripTags($this->config->task->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('after,files,labels,assignedTo,uid,storyEstimate,storyDesc,storyPri,team,teamEstimate,teamMember,multiple,teams')
            ->get();

        foreach($this->post->assignedTo as $assignedTo)
        {
            /* When type is affair and has assigned then ignore none. */
            if($task->type == 'affair' and count($this->post->assignedTo) > 1 and empty($assignedTo)) continue;

            $task->assignedTo = $assignedTo;
            if($assignedTo) $task->assignedDate = helper::now();

            $teams = array();
            if($this->post->multiple)
            {
                $estimate = 0;
                $left     = 0;
                foreach($this->post->team as $row => $account)
                {
                    if(empty($account) or isset($team[$account])) continue;
                    $member = new stdClass();
                    $member->root     = 0;
                    $member->account  = $account;
                    $member->role     = $assignedTo;
                    $member->join     = helper::today();
                    $member->estimate = $this->post->teamEstimate[$row] ? (float)$this->post->teamEstimate[$row] : 0;
                    $member->left     = $member->estimate;
                    $member->order    = $row;
                    $teams[$account]  = $member;

                    $estimate += (float)$member->estimate;
                    $left     += (float)$member->left;
                }

                if(!empty($teams))
                {
                    $firstMember        = reset($teams);
                    $task->assignedTo   = $firstMember->account;
                    $task->assignedDate = helper::now();
                    $task->estimate     = $estimate;
                    $task->left         = $left;
                }
            }

            /* Check duplicate task. */
            if($task->type != 'affair')
            {
                $result = $this->loadModel('common')->removeDuplicate('task', $task, "project=$projectID and story=$task->story");
                if($result['stop'])
                {
                    $taskIdList[$assignedTo] = array('status' => 'exists', 'id' => $result['duplicate']);
                    continue;
                }
            }

            $task = $this->file->processImgURL($task, $this->config->task->editor->create['id'], $this->post->uid);
            $this->dao->insert(TABLE_TASK)->data($task)
                ->autoCheck()
                ->batchCheck($this->config->task->create->requiredFields, 'notempty')
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->checkIF($task->deadline != '0000-00-00', 'deadline', 'ge', $task->estStarted)
                ->exec();

            if(dao::isError()) return false;

            $taskID = $this->dao->lastInsertID();
            if($this->post->story) $this->loadModel('story')->setStage($this->post->story);
            $this->file->updateObjectID($this->post->uid, $taskID, 'task');
            if(!empty($taskFiles))
            {
                foreach($taskFiles as $taskFile)
                {
                    $taskFile->objectID = $taskID;
                    $this->dao->insert(TABLE_FILE)->data($taskFile)->exec();
                }
            }
            else
            {
                $taskFileTitle = $this->file->saveUpload('task', $taskID);
                $taskFiles     = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in(array_keys($taskFileTitle))->fetchAll('id');
                foreach($taskFiles as $fileID => $taskFile) unset($taskFiles[$fileID]->id);
            }

            if(!empty($teams))
            {
                foreach($teams as $team)
                {
                    $team->root = $taskID;
                    $team->type = 'task';
                    $this->dao->insert(TABLE_TEAM)->data($team)->autoCheck()->exec();
                }
            }

            if(!dao::isError()) $this->loadModel('score')->create('task', 'create', $taskID);
            $taskIdList[$assignedTo] = array('status' => 'created', 'id' => $taskID);
        }
        return $taskIdList;
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
        $this->loadModel('action');
        $now      = helper::now();
        $mails    = array();
        $tasks    = fixer::input('post')->get();
        $batchNum = count(reset($tasks));

        $storyIDs  = array();
        $taskNames = array();
        $preStory  = 0;
        foreach($tasks->story as $key => $storyID)
        {
            if(empty($tasks->name[$key])) continue;
            if($tasks->type[$key] == 'affair') continue;
            if($tasks->type[$key] == 'ditto' && isset($tasks->type[$key - 1]) && $tasks->type[$key - 1] == 'affair') continue;

            if($storyID == 'ditto') $storyID = $preStory;
            $preStory = $storyID;

            $inNames = in_array($tasks->name[$key], $taskNames);
            if(!$inNames || ($inNames && !in_array($storyID, $storyIDs)))
            {
                $storyIDs[]  = $storyID;
                $taskNames[] = $tasks->name[$key];
            }
            else
            {
                dao::$errors['message'][] = sprintf($this->lang->duplicate, $this->lang->task->common);
                die(js::error(dao::getError()));
            }
        }

        $result = $this->loadModel('common')->removeDuplicate('task', $tasks, "project=$projectID and story " . helper::dbIN($storyIDs));
        $tasks  = $result['data'];

        /* check estimate. */
        for($i = 0; $i < $batchNum; $i++)
        {
            if(!empty($tasks->name[$i]) and $tasks->estimate[$i] and !preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $tasks->estimate[$i]))
            {
                die(js::alert($this->lang->task->error->estimateNumber));
            }
            if(!empty($tasks->name[$i]) and empty($tasks->type[$i])) die(js::alert(sprintf($this->lang->error->notempty, $this->lang->task->type)));
        }

        $story      = 0;
        $module     = 0;
        $type       = '';
        $assignedTo = '';

        for($i = 0; $i < $batchNum; $i++)
        {
            $story      = !isset($tasks->story[$i]) || $tasks->story[$i]           == 'ditto' ? $story     : $tasks->story[$i];
            $module     = !isset($tasks->module[$i]) || $tasks->module[$i]         == 'ditto' ? $module    : $tasks->module[$i];
            $type       = !isset($tasks->type[$i]) || $tasks->type[$i]             == 'ditto' ? $type      : $tasks->type[$i];
            $assignedTo = !isset($tasks->assignedTo[$i]) || $tasks->assignedTo[$i] == 'ditto' ? $assignedTo: $tasks->assignedTo[$i];

            if(empty($tasks->name[$i])) continue;

            $data[$i]             = new stdclass();
            $data[$i]->story      = (int)$story;
            $data[$i]->type       = $type;
            $data[$i]->module     = (int)$module;
            $data[$i]->assignedTo = $assignedTo;
            $data[$i]->color      = $tasks->color[$i];
            $data[$i]->name       = $tasks->name[$i];
            $data[$i]->desc       = nl2br($tasks->desc[$i]);
            $data[$i]->pri        = $tasks->pri[$i];
            $data[$i]->estimate   = $tasks->estimate[$i];
            $data[$i]->left       = $tasks->estimate[$i];
            $data[$i]->project    = $projectID;
            $data[$i]->estStarted = empty($tasks->estStarted[$i]) ? '0000-00-00' : $tasks->estStarted[$i];
            $data[$i]->deadline   = empty($tasks->deadline[$i]) ? '0000-00-00' : $tasks->deadline[$i];
            $data[$i]->status     = 'wait';
            $data[$i]->openedBy   = $this->app->user->account;
            $data[$i]->openedDate = $now;
            $data[$i]->parent     = $tasks->parent[$i];
            if($story) $data[$i]->storyVersion = $this->loadModel('story')->getVersion($data[$i]->story);
            if($assignedTo) $data[$i]->assignedDate = $now;
            if(strpos($this->config->task->create->requiredFields, 'estStarted') !== false and empty($tasks->estStarted[$i])) $data[$i]->estStarted = '';
            if(strpos($this->config->task->create->requiredFields, 'deadline') !== false and empty($tasks->deadline[$i]))     $data[$i]->deadline   = '';

            $this->dao->insert(TABLE_TASK)->data($data[$i])
                ->autoCheck()
                ->batchCheck($this->config->task->create->requiredFields, 'notempty')
                ->checkIF($data[$i]->estimate != '', 'estimate', 'float')
                ->exec();

            if(dao::isError()) die(js::error(dao::getError()));

            $taskID = $this->dao->lastInsertID();
            if($story) $this->story->setStage($tasks->story[$i]);
            $actionID = $this->action->create('task', $taskID, 'Opened', '');
            if(!dao::isError()) $this->loadModel('score')->create('task', 'create', $taskID);

            $mails[$i] = new stdclass();
            $mails[$i]->taskID   = $taskID;
            $mails[$i]->actionID = $actionID;
        }

        $this->computeWorkingHours($tasks->parent[0]);
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchCreate');
        return $mails;
    }

    /**
     * Compute parent task working hours.
     *
     * @param $taskID
     *
     * @access public
     * @return bool
     */
    public function computeWorkingHours($taskID)
    {
        if(!$taskID) return true;

        $tasks = $this->dao->select('`id`,`estimate`,`consumed`,`left`')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('status')->ne('cancel')->andWhere('deleted')->eq(0)->fetchAll('id');
        if(empty($tasks)) return true;

        $estimate = 0;
        $consumed = 0;
        $left     = 0;
        foreach($tasks as $task)
        {
            $estimate += $task->estimate;
            $consumed += $task->consumed;
            $left     += $task->left;
        }

        $newTask = new stdClass();
        $newTask->estimate = $estimate;
        $newTask->consumed = $consumed;
        $newTask->left     = $left;

        $this->dao->update(TABLE_TASK)->data($newTask)->autoCheck()->where('id')->eq($taskID)->exec();
        return !dao::isError();
    }

    /**
     * Check that all children's status.
     *
     * @param $parentID
     * @param $status
     *
     * @access public
     * @return bool
     */
    public function updateParentStatus($parentID, $status = 'done')
    {
        if(!$parentID) return true;
        $children = $this->dao->select('id,status')->from(TABLE_TASK)->where('parent')->eq($parentID)->fetchPairs('id', 'status');
        $values   = array_values(array_unique($children));
        if((count($values) == 1 && $values[0] == $status) || (count($values) == 2 && in_array('closed', $values) && $status == 'done'))
        {
            $this->dao->update(TABLE_TASK)->set('status')->eq($status)->where('id')->eq($parentID)->exec();
        }
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
        $oldTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq((int)$taskID)->fetch();
        if(!empty($_POST['lastEditedDate']) and $oldTask->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $now  = helper::now();
        $task = fixer::input('post')
            ->setDefault('story, estimate, left, consumed', 0)
            ->setDefault('estStarted', '0000-00-00')
            ->setDefault('deadline', '0000-00-00')
            ->setIF(strpos($this->config->task->edit->requiredFields, 'estStarted') !== false, 'estStarted', $this->post->estStarted)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'deadline') !== false, 'deadline', $this->post->deadline)
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
            ->stripTags($this->config->task->editor->edit['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('comment,files,labels,uid,multiple,team,teamEstimate,teamConsumed,teamLeft')
            ->get();

        $teams = array();
        if($this->post->multiple)
        {
            foreach($this->post->team as $row => $account)
            {
                if(empty($account) or isset($team[$account])) continue;

                $member = new stdClass();
                $member->account  = $account;
                $member->role     = $task->assignedTo;
                $member->join     = helper::today();
                $member->root     = $taskID;
                $member->type     = 'task';
                $member->estimate = $this->post->teamEstimate[$row] ? $this->post->teamEstimate[$row] : 0;
                $member->consumed = $this->post->teamConsumed[$row] ? $this->post->teamConsumed[$row] : 0;
                $member->left     = $member->estimate - $member->consumed;
                $member->order    = $row;
                $teams[$account]  = $member;
            }

            if(!empty($teams) and !isset($task->assignedTo))
            {
                $firstMember      = reset($teams);
                $task->assignedTo = $firstMember->account;
            }
        }

        if($task->consumed < $oldTask->consumed) die(js::error($this->lang->task->error->consumedSmall));

        /* Fix bug#1388, Check children task projectID and moduleID. */
        if($task->project != $oldTask->project)
        {
            $this->dao->update(TABLE_TASK)->set('project')->eq($task->project)->set('module')->eq($task->module)->where('parent')->eq($taskID)->exec();
        }

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheckIF($task->status != 'cancel', $this->config->task->edit->requiredFields, 'notempty')
            ->checkIF($task->deadline != '0000-00-00', 'deadline', 'ge', $task->estStarted)

            ->checkIF($task->estimate != false, 'estimate', 'float')
            ->checkIF($task->left     != false, 'left',     'float')
            ->checkIF($task->consumed != false, 'consumed', 'float')
            ->checkIF($task->status   != 'wait' and $task->left == 0 and $task->status != 'cancel' and $task->status != 'closed', 'status', 'equal', 'done')

            ->batchCheckIF($task->status == 'wait' or $task->status == 'doing', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')

            ->checkIF($task->status == 'done', 'consumed', 'notempty')
            ->checkIF($task->status == 'done' and $task->closedReason, 'closedReason', 'equal', 'done')
            ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')

            ->checkIF($task->status == 'closed', 'closedReason', 'notempty')
            ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
            ->where('id')->eq((int)$taskID)->exec();

        $this->computeWorkingHours($oldTask->parent);

        /* Save team. */
        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($taskID)->andWhere('type')->eq('task')->exec();
        if(!empty($teams))
        {
            foreach($teams as $member) $this->dao->insert(TABLE_TEAM)->data($member)->autoCheck()->exec();
        }

        if($this->post->story != false) $this->loadModel('story')->setStage($this->post->story);
        if(!dao::isError())
        {
            if($task->status == 'done')   $this->loadModel('score')->create('task', 'finish', $taskID);
            if($task->status == 'closed') $this->loadModel('score')->create('task', 'close', $taskID);
            $this->file->updateObjectID($this->post->uid, $taskID, 'task');
            return common::createChanges($oldTask, $task);
        }
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
        $today      = date(DT_DATE1);
        $data       = fixer::input('post')->get();
        $taskIDList = $this->post->taskIDList;

        /* Process data if the value is 'ditto'. */
        foreach($taskIDList as $taskID)
        {
            if(isset($data->modules[$taskID]) and ($data->modules[$taskID] == 'ditto')) $data->modules[$taskID] = isset($prev['module']) ? $prev['module'] : 0;
            if($data->types[$taskID]       == 'ditto') $data->types[$taskID]       = isset($prev['type'])       ? $prev['type']       : '';
            if($data->statuses[$taskID]    == 'ditto') $data->statuses[$taskID]    = isset($prev['status'])     ? $prev['status']     : '';
            if($data->assignedTos[$taskID] == 'ditto') $data->assignedTos[$taskID] = isset($prev['assignedTo']) ? $prev['assignedTo'] : '';
            if($data->pris[$taskID]        == 'ditto') $data->pris[$taskID]        = isset($prev['pri'])        ? $prev['pri']        : 0;
            if($data->finishedBys[$taskID] == 'ditto') $data->finishedBys[$taskID] = isset($prev['finishedBy']) ? $prev['finishedBy'] : '';
            if($data->canceledBys[$taskID] == 'ditto') $data->canceledBys[$taskID] = isset($prev['canceledBy']) ? $prev['canceledBy'] : '';
            if($data->closedBys[$taskID]   == 'ditto') $data->closedBys[$taskID]   = isset($prev['closedBy'])   ? $prev['closedBy']   : '';

            $prev['module']     = $data->modules[$taskID];
            $prev['type']       = $data->types[$taskID];
            $prev['status']     = $data->statuses[$taskID];
            $prev['assignedTo'] = $data->assignedTos[$taskID];
            $prev['pri']        = $data->pris[$taskID];
            $prev['finishedBy'] = $data->finishedBys[$taskID];
            $prev['canceledBy'] = $data->canceledBys[$taskID];
            $prev['closedBy']   = $data->closedBys[$taskID];
        }

        /* Initialize tasks from the post data.*/
        $oldTasks = $taskIDList ? $this->getByList($taskIDList) : array();
        foreach($taskIDList as $taskID)
        {
            $oldTask = $oldTasks[$taskID];

            $task = new stdclass();
            $task->color          = $data->colors[$taskID];
            $task->name           = $data->names[$taskID];
            $task->module         = isset($data->modules[$taskID]) ? $data->modules[$taskID] : 0;
            $task->type           = $data->types[$taskID];
            $task->status         = $data->statuses[$taskID];
            $task->assignedTo     = $task->status == 'closed' ? 'closed' : $data->assignedTos[$taskID];
            $task->pri            = $data->pris[$taskID];
            $task->estimate       = $data->estimates[$taskID];
            $task->left           = $data->lefts[$taskID];
            $task->estStarted     = $data->estStarteds[$taskID];
            $task->deadline       = $data->deadlines[$taskID];
            $task->finishedBy     = $data->finishedBys[$taskID];
            $task->canceledBy     = $data->canceledBys[$taskID];
            $task->closedBy       = $data->closedBys[$taskID];
            $task->closedReason   = $data->closedReasons[$taskID];
            $task->assignedDate   = $oldTask->assignedTo ==$task->assignedTo  ? $oldTask->assignedDate : $now;
            $task->finishedDate   = $oldTask->finishedBy == $task->finishedBy ? $oldTask->finishedDate : $now;
            $task->canceledDate   = $oldTask->canceledBy == $task->canceledBy ? $oldTask->canceledDate : $now;
            $task->closedDate     = $oldTask->closedBy == $task->closedBy ? $oldTask->closedDate : $now;
            $task->lastEditedBy   = $this->app->user->account;
            $task->lastEditedDate = $now;
            $task->consumed       = $oldTask->consumed;

            if($data->consumeds[$taskID])
            {
                if($data->consumeds[$taskID] < 0)
                {
                    echo js::alert(sprintf($this->lang->task->error->consumed, $taskID));
                }
                else
                {
                    $record = new stdclass();
                    $record->account  = $this->app->user->account;
                    $record->task     = $taskID;
                    $record->date     = $today;
                    $record->left     = $task->left;
                    $record->consumed = $data->consumeds[$taskID];
                    $this->addTaskEstimate($record);

                    $task->consumed = $oldTask->consumed + $record->consumed;
                }
            }

            switch($task->status)
            {
                case 'done':
                    $task->left = 0;
                    if(!$task->finishedBy)  $task->finishedBy = $this->app->user->account;
                    if($task->closedReason) $task->closedDate = $now;
                    $task->finishedDate = $oldTask->status == 'done' ?  $oldTask->finishedDate : $now;

                    $task->canceledBy   = '';
                    $task->canceledDate = '';
                    break;
                case 'cancel':
                    $task->assignedTo   = $oldTask->openedBy;
                    $task->assignedDate = $now;

                    if(!$task->canceledBy)   $task->canceledBy   = $this->app->user->account;
                    if(!$task->canceledDate) $task->canceledDate = $now;

                    $task->finishedBy   = '';
                    $task->finishedDate = '';
                    break;
                case 'closed':
                    if(!$task->closedBy)   $task->closedBy   = $this->app->user->account;
                    if(!$task->closedDate) $task->closedDate = $now;
                    break;
                case 'wait':
                    if($task->consumed > 0 and $task->left > 0) $task->status = 'doing';
                    if($task->left == $oldTask->left and $task->consumed == 0) $task->left = $task->estimate;

                    $task->canceledDate = '';
                    $task->finishedDate = '';
                    $task->closedDate   = '';
                    break;
                case 'doing':
                    $task->canceledDate = '';
                    $task->finishedDate = '';
                    $task->closedDate   = '';
                    break;
                case 'pause':
                    $task->finishedDate = '';
            }
            if($task->assignedTo) $task->assignedDate = $now;

            $this->dao->update(TABLE_TASK)->data($task)
                ->autoCheck()
                ->batchCheckIF($task->status != 'cancel', $this->config->task->edit->requiredFields, 'notempty')

                ->checkIF($task->estimate != false, 'estimate', 'float')
                ->checkIF($task->consumed != false, 'consumed', 'float')
                ->checkIF($task->left     != false, 'left',     'float')
                ->checkIF($task->left     == 0 and $task->status != 'cancel' and $task->status != 'closed' and $task->status != 'wait' and $task->consumed != 0, 'status', 'equal', 'done')

                ->batchCheckIF($task->status == 'wait' or $task->status == 'doing', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')

                ->checkIF($task->status == 'done', 'consumed', 'notempty')
                ->checkIF($task->status == 'done' and $task->closedReason, 'closedReason', 'equal', 'done')
                ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')

                ->checkIF($task->status == 'closed', 'closedReason', 'notempty')
                ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
                ->where('id')->eq((int)$taskID)
                ->exec();

            if($task->status == 'done' and $task->closedReason) $this->dao->update(TABLE_TASK)->set('status')->eq('closed')->where('id')->eq($taskID)->exec();

            if($oldTask->story != false) $this->loadModel('story')->setStage($oldTask->story);
            if(!dao::isError())
            {
                $this->computeWorkingHours($oldTask->parent);
                if($task->status == 'done')   $this->loadModel('score')->create('task', 'finish', $taskID);
                if($task->status == 'closed') $this->loadModel('score')->create('task', 'close', $taskID);
                $allChanges[$taskID] = common::createChanges($oldTask, $task);
            }
            else
            {
                die(js::error('task#' . $taskID . dao::getError(true)));
            }
        }
        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchEdit');
        return $allChanges;
    }

    /**
     * Batch change the module of task.
     *
     * @param  array  $taskIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModule($taskIDList, $moduleID)
    {
        $now        = helper::now();
        $allChanges = array();
        $oldTasks   = $this->getByList($taskIDList);
        foreach($taskIDList as $taskID)
        {
            $oldTask = $oldTasks[$taskID];
            if($moduleID == $oldTask->module) continue;

            $task = new stdclass();
            $task->lastEditedBy   = $this->app->user->account;
            $task->lastEditedDate = $now;
            $task->module         = $moduleID;

            $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('id')->eq((int)$taskID)->exec();
            if(!dao::isError()) $allChanges[$taskID] = common::createChanges($oldTask, $task);
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
        $oldTask = $this->getById($taskID);

        $now  = helper::now();
        $task = fixer::input('post')
            ->cleanFloat('left')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate', $now)
            ->remove('comment,showModule')
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
        if($this->post->consumed < $oldTask->consumed) die(js::error($this->lang->task->error->consumedSmall));

        $now  = helper::now();
        $task = fixer::input('post')
            ->setDefault('assignedTo', $this->app->user->account)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setIF($oldTask->assignedTo != $this->app->user->account, 'assignedDate', $now)
            ->remove('comment')->get();

        if($this->post->left == 0)
        {
            $task->status       = 'done';
            $task->finishedBy   = $this->app->user->account;
            $task->finishedDate = helper::now();
            $task->assignedTo   = $oldTask->openedBy; // Fix bug#1341
        }
        else
        {
            $task->status = 'doing';
        }

        /* Record consumed and left. */
        $estimate = fixer::input('post')
            ->setDefault('account', $this->app->user->account)
            ->setDefault('task', $taskID)
            ->setDefault('date', $task->realStarted)
            ->remove('realStarted,comment')
            ->get();
        $estimate->consumed = $estimate->consumed - $oldTask->consumed;
        $this->addTaskEstimate($estimate);

        if(!empty($oldTask->team) && $task->status == 'done')
        {
            $teams = array_keys($oldTask->team);
            if(empty($oldTask->assignedTo)) $oldTask->assignedTo = $teams[0];

            $newTeamInfo = new stdClass();
            $newTeamInfo->consumed = $task->consumed;
            $newTeamInfo->left     = 0;
            $this->dao->update(TABLE_TEAM)->data($newTeamInfo)
                ->where('root')->eq($taskID)
                ->andWhere('account')->eq($oldTask->assignedTo)
                ->andWhere('type')->eq('task')
                ->exec();

            $newTask = new stdClass();
            $newTask->left         = 0;
            $newTask->status       = 'doing';
            $newTask->consumed     = $task->consumed;
            $newTask->assignedTo   = $this->getNextUser($teams, $oldTask->assignedTo);
            $newTask->assignedDate = $now;
            $this->dao->update(TABLE_TASK)->data($newTask)->where('id')->eq((int)$taskID)->exec();

            if($task->assignedTo != $teams[count($teams) - 1]) return common::createChanges($oldTask, $newTask);
        }

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->check('consumed,left', 'float')
            ->where('id')->eq((int)$taskID)->exec();

        if($oldTask->parent)
        {
            if($task->status == 'doing')
            {
                $this->dao->update(TABLE_TASK)->set('status')->eq('doing')->where('id')->eq((int)$oldTask->parent)->exec();
            }
            elseif($task->status == 'done')
            {
                $this->updateParentStatus($oldTask->parent, 'done');
            }
        }

        $this->computeWorkingHours($oldTask->parent);

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Record estimate and left of task.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function recordEstimate($taskID)
    {
        $record       = fixer::input('post')->get();
        $estimates    = array();
        $task         = $this->getById($taskID);
        $oldStatus    = $task->status;
        $earliestTime = '';
        foreach(array_keys($record->id) as $id)
        {
            if($earliestTime == '')
            {
                $earliestTime = $record->dates[$id];
            }
            elseif(!empty($record->dates[$id]) && (strtotime($earliestTime) > strtotime($record->dates[$id])))
            {
                $earliestTime = $record->dates[$id];
            }

            if($record->dates[$id])
            {
                if(!$record->consumed[$id])   die(js::alert($this->lang->task->error->consumedThisTime));
                if($record->left[$id] === '') die(js::alert($this->lang->task->error->left));

                $estimates[$id] = new stdclass();
                $estimates[$id]->date     = $record->dates[$id];
                $estimates[$id]->task     = $taskID;
                $estimates[$id]->consumed = $record->consumed[$id];
                $estimates[$id]->left     = $record->left[$id];
                $estimates[$id]->work     = $record->work[$id];
                $estimates[$id]->account  = $this->app->user->account;
            }
        }

        if(empty($estimates)) return;

        $this->loadModel('action');

        $consumed = 0;
        $left     = $task->left;
        $now      = helper::now();
        $lastDate = $this->dao->select('*')->from(TABLE_TASKESTIMATE)->where('task')->eq($taskID)->orderBy('date_desc')->limit(1)->fetch('date');

        foreach($estimates as $estimate)
        {
            $this->addTaskEstimate($estimate);

            $consumed  += $estimate->consumed;
            $work       = $estimate->work;
            $estimateID = $this->dao->lastInsertID();
            $actionID   = $this->action->create('task', $taskID, 'RecordEstimate', $work, $estimate->consumed);

            if(empty($lastDate) or $lastDate <= $estimate->date)
            {
                $left     = $estimate->left;
                $lastDate = $estimate->date;
            }
        }

        $data = new stdClass();
        $data->consumed       = $task->consumed + $consumed;
        $data->left           = $left;
        $data->status         = $task->status;
        $data->lastEditedBy   = $this->app->user->account;
        $data->lastEditedDate = $now;

        if($left == 0)
        {
            $task->status       = 'done';
            $data->status       = $task->status;
            $data->assignedTo   = $task->openedBy;
            $data->assignedDate = $now;
            $data->finishedBy   = $this->app->user->account;
            $data->finishedDate = $now;
        }
        elseif($task->status == 'wait')
        {
            $task->status       = 'doing';
            $data->status       = $task->status;
            $data->assignedTo   = $this->app->user->account;
            $data->assignedDate = $now;
            $data->realStarted  = $earliestTime;
        }
        elseif($task->status == 'pause')
        {
            $task->status       = 'doing';
            $data->status       = $task->status;
            $data->assignedTo   = $this->app->user->account;
            $data->assignedDate = $now;
        }

        if(!empty($task->team))
        {
            $teams = array_keys($task->team);

            $myConsumed = $this->dao->select('`consumed`')->from(TABLE_TEAM)
                ->where('root')->eq($taskID)
                ->andWhere('account')->eq($task->assignedTo)
                ->andWhere('type')->eq('task')
                ->fetch('consumed');

            $newTeamInfo = new stdClass();
            $newTeamInfo->consumed = $myConsumed + $consumed;
            $newTeamInfo->left     = $left;
            $this->dao->update(TABLE_TEAM)->data($newTeamInfo)
                ->where('root')->eq($taskID)
                ->andWhere('account')->eq($task->assignedTo)
                ->andWhere('type')->eq('task')
                ->exec();

            $teamTime = $this->dao->select("sum(`consumed`) as consumed,sum(`left`) as leftTime")->from(TABLE_TEAM)
                ->where('root')->eq((int)$taskID)
                ->andWhere('account')->in($teams)
                ->andWhere('type')->eq('task')
                ->fetch();
            $data->consumed = $teamTime->consumed;
            $data->left     = $teamTime->leftTime;

            if($task->status == 'done')
            {
                $newTask = new stdClass();
                $newTask->left         = $data->left;
                $newTask->consumed     = $data->consumed;
                $newTask->assignedTo   = $this->getNextUser($teams, $task->assignedTo);
                $newTask->assignedDate = $now;
                $this->dao->update(TABLE_TASK)->data($newTask)->where('id')->eq((int)$taskID)->exec();

                if($task->assignedTo != $teams[count($teams) - 1]) return common::createChanges($task, $newTask);

                $data->assignedTo = $task->openedBy; // Fix bug#1345
            }
        }

        $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($taskID)->exec();

        $oldTask = new stdClass();
        $oldTask->consumed   = $task->consumed;
        $oldTask->left       = $task->left;
        $oldTask->status     = $oldStatus;
        $oldTask->assignedTo = $task->assignedTo;

        $newTask = new stdClass();
        $newTask->left       = $left;
        $newTask->consumed   = $task->consumed + $consumed;
        $newTask->status     = $task->status;
        $newTask->assignedTo = $data->assignedTo;

        $changes = common::createChanges($oldTask, $newTask);
        if(!empty($actionID)) $this->action->logHistory($actionID, $changes);

        if($task->story) $this->loadModel('story')->setStage($task->story);

        if($task->status == 'done')
        {
            $this->updateParentStatus($task->parent, 'done');
            if(!dao::isError()) $this->loadModel('score')->create('task', 'finish', $taskID);
        }
        $this->computeWorkingHours($task->parent);

        return $changes;
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

        if(strpos($this->config->task->finish->requiredFields, 'comment') !== false and !$this->post->comment)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        $task = fixer::input('post')
            ->setDefault('left', 0)
            ->setDefault('assignedTo',   $oldTask->openedBy)
            ->setDefault('assignedDate', $now)
            ->setDefault('status', 'done')
            ->setDefault('finishedBy, lastEditedBy', $this->app->user->account)
            ->setDefault('finishedDate, lastEditedDate', $now)
            ->remove('comment,files,labels')
            ->get();

        if(!is_numeric($task->consumed)) die(js::error($this->lang->task->error->consumedNumber));

        if(!empty($oldTask->team))
        {
            $teams = array_keys($oldTask->team);

            $myConsumed = $this->dao->select("`consumed`")->from(TABLE_TEAM)
                ->where('root')->eq((int)$taskID)
                ->andWhere('account')->eq($oldTask->assignedTo)
                ->andWhere('type')->eq('task')
                ->fetch('consumed');
            if($task->consumed < $myConsumed) die(js::error($this->lang->task->error->consumedSmall));

            $data = new stdClass();
            $data->left     = 0;
            $data->consumed = $task->consumed;
            $this->dao->update(TABLE_TEAM)->data($data)
                ->where('root')->eq((int)$taskID)
                ->andWhere('account')->eq($oldTask->assignedTo)
                ->andWhere('type')->eq('task')
                ->exec();

            $myTime = $this->dao->select("sum(`left`) as leftTime,sum(`consumed`) as consumed")->from(TABLE_TEAM)
                ->where('root')->eq((int)$taskID)
                ->andWhere('account')->in($teams)
                ->andWhere('type')->eq('task')
                ->fetch();

            $newTask = new stdClass();
            $newTask->left         = $myTime->leftTime;
            $newTask->consumed     = $myTime->consumed;
            $newTask->assignedTo   = $task->assignedTo;
            $newTask->assignedDate = $now;
            $this->dao->update(TABLE_TASK)->data($newTask)->where('id')->eq((int)$taskID)->exec();

            if($oldTask->assignedTo != $teams[count($teams) - 1]) return common::createChanges($oldTask, $newTask);
            $task->consumed   = $myTime->consumed;
            $task->assignedTo = $oldTask->openedBy; // Fix bug#1345
        }

        if($task->finishedDate == substr($now, 0, 10)) $task->finishedDate = $now;

        /* Record consumed and left. */
        $consumed = $task->consumed - $oldTask->consumed;
        if($consumed < 0) die(js::error($this->lang->task->error->consumedSmall));

        $estimate = fixer::input('post')
            ->setDefault('account', $this->app->user->account)
            ->setDefault('task', $taskID)
            ->setDefault('date', date(DT_DATE1))
            ->setDefault('left', 0)
            ->remove('finishedDate,comment,assignedTo,files,labels,consumed')
            ->get();
        $estimate->consumed = $consumed;
        if($estimate->consumed) $this->addTaskEstimate($estimate);

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheck($this->config->task->finish->requiredFields, 'notempty')
            ->where('id')->eq((int)$taskID)
            ->exec();

        if($task->status == 'done') $this->updateParentStatus($oldTask->parent, 'done');
        $this->computeWorkingHours($oldTask->parent);

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if($task->status == 'done' && !dao::isError()) $this->loadModel('score')->create('task', 'finish', $taskID);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Pause task
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function pause($taskID)
    {
        $oldTask = $this->getById($taskID);

        $task = fixer::input('post')
            ->setDefault('status', 'pause')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('id')->eq((int)$taskID)->exec();

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
        $oldTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();

        $now  = helper::now();
        $task = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('assignedTo', 'closed')
            ->setDefault('assignedDate', $now)
            ->setDefault('closedBy, lastEditedBy', $this->app->user->account)
            ->setDefault('closedDate, lastEditedDate', $now)
            ->setIF($oldTask->status == 'done',   'closedReason', 'done')
            ->setIF($oldTask->status == 'cancel', 'closedReason', 'cancel')
            ->remove('_recPerPage')
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('id')->eq((int)$taskID)->exec();
        $this->updateParentStatus($oldTask->parent, 'closed');
        $this->computeWorkingHours($oldTask->parent);

        $this->dao->update(TABLE_TASK)->set('status')->eq('closed')->where('parent')->eq($taskID)->exec();

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);

        if(!dao::isError())
        {
            $this->loadModel('score')->create('task', 'close', $taskID);
            return common::createChanges($oldTask, $task);
        }
    }

    /**
     * Cancel a task.
     *
     * @param int $taskID
     *
     * @access public
     * @return array
     */
    public function cancel($taskID)
    {
        $oldTask = $this->getById($taskID);

        $now  = helper::now();
        $task = fixer::input('post')
            ->setDefault('status', 'cancel')
            ->setDefault('assignedTo', $oldTask->openedBy)
            ->setDefault('assignedDate', $now)
            ->setDefault('finishedBy', '')
            ->setDefault('finishedDate', '0000-00-00')
            ->setDefault('canceledBy, lastEditedBy', $this->app->user->account)
            ->setDefault('canceledDate, lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('id')->eq((int)$taskID)->exec();
        $this->computeWorkingHours($oldTask->parent);
        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Activate a task.
     *
     * @param int $taskID
     *
     * @access public
     * @return array
     */
    public function activate($taskID)
    {
        if(strpos($this->config->task->activate->requiredFields, 'comment') !== false and !$this->post->comment)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        $oldTask = $this->getById($taskID);
        $task = fixer::input('post')
            ->setDefault('left', 0)
            ->setDefault('status', 'doing')
            ->setDefault('finishedBy, canceledBy, closedBy, closedReason', '')
            ->setDefault('finishedDate, canceledDate, closedDate', '0000-00-00')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheck($this->config->task->activate->requiredFields, 'notempty')
            ->where('id')->eq((int)$taskID)
            ->exec();

        $this->computeWorkingHours($oldTask->parent);

        $this->dao->update(TABLE_TASK)->set('status')->eq('doing')->where('parent')->eq($taskID)->exec();
        if($oldTask->parent) $this->dao->update(TABLE_TASK)->set('status')->eq('doing')->where('id')->eq((int)$oldTask->parent)->exec();

        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Get task info by Id.
     *
     * @param  int  $taskID
     * @param  bool $setImgSize
     *
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

        $children       = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('deleted')->eq(0)->fetchAll('id');
        $task->children = $children;

        /* Check parent Task. */
        if(!empty($task->parent)) $task->parentName = $this->dao->findById($task->parent)->from(TABLE_TASK)->fetch('name');

        $teams = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($taskID)->andWhere('type')->eq('task')->orderBy('order_desc')->fetchGroup('root', 'account');
        if(!empty($teams))
        {
            foreach($teams as $key => $team) $teams[$key] = array_reverse($team, true);
        }
        $task->team = isset($teams[$taskID]) ? $teams[$taskID] : array();
        foreach($children as $child) $child->team = isset($teams[$child->id]) ? $teams[$child->id] : array();

        $task = $this->loadModel('file')->replaceImgURL($task, 'desc');
        if($setImgSize) $task->desc = $this->file->setImgSize($task->desc);

        if($task->assignedTo == 'closed') $task->assignedToRealName = 'Closed';
        foreach($task as $key => $value)
        {
            if(strpos($key, 'Date') !== false and !(int)substr($value, 0, 4)) $task->$key = '';
        }
        $task->files = $this->loadModel('file')->getByObject('task', $taskID);

        /* Get related test cases. */
        if($task->story) $task->cases = $this->dao->select('id, title')->from(TABLE_CASE)->where('story')->eq($task->story)->andWhere('storyVersion')->eq($task->storyVersion)->andWhere('deleted')->eq('0')->fetchPairs();

        return $this->processTask($task);
    }

    /**
     * Get task list.
     *
     * @param  int|array|string    $taskIDList
     * @access public
     * @return array
     */
    public function getByList($taskIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->beginIF($taskIDList)->andWhere('id')->in($taskIDList)->fi()
            ->fetchAll('id');
    }

    /**
     * Get tasks list of a project.
     *
     * @param  int           $projectID
     * @param  array|string  $moduleIdList
     * @param  string        $status
     * @param  string        $orderBy
     * @param  object        $pager
     * @access public
     * @return array
     */
    public function getTasksByModule($projectID = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null)
    {
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.project')->eq((int)$projectID)
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
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
     * @param int    $projectID
     * @param int    $productID
     * @param string $type
     * @param string $modules
     * @param string $orderBy
     * @param null   $pager
     *
     * @access public
     * @return array|void
     */
    public function getProjectTasks($projectID, $productID = 0, $type = 'all', $modules = 0, $orderBy = 'status_asc, id_desc', $pager = null)
    {
        if(is_string($type)) $type = strtolower($type);
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->leftJoin(TABLE_TEAM)->alias('t4')->on('t4.root = t1.id')
            ->leftJoin(TABLE_MODULE)->alias('t5')->on('t1.module = t5.id')
            ->where('t1.project')->eq((int)$projectID)
            ->beginIF(!in_array($type, array('assignedtome', 'myinvolved')))->andWhere('t1.parent')->eq(0)->fi()
            ->beginIF($type == 'myinvolved')
            ->andWhere("((t4.`account` = '{$this->app->user->account}' AND t4.`type` = 'task') OR t1.`assignedTo` = '{$this->app->user->account}' OR t1.`finishedby` = '{$this->app->user->account}')")
            ->fi()
            ->beginIF($productID)->andWhere("((t5.root=" . (int)$productID . " and t5.type='story') OR t2.product=" . (int)$productID . ")")->fi()
            ->beginIF($type == 'undone')->andWhere("(t1.status = 'wait' or t1.status ='doing')")->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'finishedbyme')->andWhere('t1.finishedby')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.`parent`,' . $orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', ($productID or in_array($type, array('assignedtome', 'myinvolved', 'needconfirm'))) ? false : true);

        if(empty($tasks)) return array();

        $taskList = array_keys($tasks);
        $taskTeam = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in($taskList)->andWhere('type')->eq('task')->fetchGroup('root');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
        }

        /* Select children task. */
        $children = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->leftJoin(TABLE_MODULE)->alias('t4')->on('t1.module = t4.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.parent')->in($taskList)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($productID)->andWhere("((t4.root=" . (int)$productID . " and t4.type='story') OR t2.product=" . (int)$productID . ")")->fi()
            ->beginIF($type == 'undone')->andWhere("(t1.status = 'wait' or t1.status ='doing')")->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'finishedbyme')->andWhere('t1.finishedby')->eq($this->app->user->account)->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->orderBy('t1.`id`,' . $orderBy)
            ->fetchAll('id');

        if(!empty($children))
        {
            foreach($children as $child)
            {
                $tasks[$child->parent]->children[] = $child;
            }
        }

        return $this->processTasks($tasks);
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
        $tasks = array('' => '');
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
    public function getUserTasks($account, $type = 'assignedTo', $limit = 0, $pager = null, $orderBy="id_desc")
    {
        if(!$this->loadModel('common')->checkField(TABLE_TASK, $type)) return array();
        $tasks = $this->dao->select('t1.*, t2.id as projectID, t2.name as projectName, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftjoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($type == 'assignedTo')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all')->andWhere("t1.`$type`")->eq($account)->fi()
            ->orderBy($orderBy)
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
        $stmt = $this->dao->select('t1.id, t1.name, t2.name as project')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->query();

        $tasks = array();
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
    public function getStoryTasks($storyID, $projectID = 0)
    {
        $tasks = $this->dao->select('id, name, assignedTo, pri, status, estimate, consumed, closedReason, `left`')
            ->from(TABLE_TASK)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchAll('id');

        foreach($tasks as $task)
        {
            /* Compute task progress. */
            if($task->consumed == 0 and $task->left == 0)
            {
                $task->progress = 0;
            }
            elseif($task->consumed != 0 and $task->left == 0)
            {
                $task->progress = 100;
            }
            else
            {
                $task->progress = round($task->consumed / ($task->consumed + $task->left), 2) * 100;
            }
        }

        return $tasks;
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
        foreach($stories as $storyID)
        {
            if(!isset($taskCounts[$storyID])) $taskCounts[$storyID] = 0;
        }
        return $taskCounts;
    }

    /**
     * Get task estimate.
     *
     * @param  int    $taskID
     * @access public
     * @return object
     */
    public function getTaskEstimate($taskID)
    {
        return $this->dao->select('*')->from(TABLE_TASKESTIMATE)
          ->where('task')->eq($taskID)
          ->orderBy('date,id')
          ->fetchAll();
    }

    /**
     * Get estimate by id.
     *
     * @param  int    $estimateID
     * @access public
     * @return object.
     */
    public function getEstimateById($estimateID)
    {
        $estimate = $this->dao->select('*')->from(TABLE_TASKESTIMATE)
            ->where('id')->eq($estimateID)
            ->fetch();

        /* If the estimate is the last of its task, status of task will be checked. */
        $lastID = $this->dao->select('id')->from(TABLE_TASKESTIMATE)
            ->where('task')->eq($estimate->task)
            ->andWhere('id')->gt($estimate->id)
            ->fetch('id');
        $estimate->isLast = $lastID ? false : true;
        return $estimate;
    }

    /**
     * Update estimate.
     *
     * @param  int    $estimateID
     * @access public
     * @return void
     */
    public function updateEstimate($estimateID)
    {
        $oldEstimate = $this->getEstimateById($estimateID);
        $estimate    = fixer::input('post')->get();
        $task        = $this->getById($oldEstimate->task);
        $oldStatus   = $task->status;
        $this->dao->update(TABLE_TASKESTIMATE)->data($estimate)
            ->autoCheck()
            ->check('consumed', 'notempty')
            ->where('id')->eq((int)$estimateID)
            ->exec();

        $consumed     = $task->consumed + $estimate->consumed - $oldEstimate->consumed;
        $lastEstimate = $this->dao->select('*')->from(TABLE_TASKESTIMATE)->where('task')->eq($task->id)->orderBy('id desc')->fetch();
        $left         = ($lastEstimate and $estimateID == $lastEstimate->id) ? $estimate->left : $task->left;
        if($left == 0) $task->status = 'done';

        $now  = helper::now();
        $data = new stdClass();
        $data->consumed       = $consumed;
        $data->left           = $left;
        $data->status         = $task->status;
        $data->lastEditedBy   = $this->app->user->account;
        $data->lastEditedDate = $now;
        if(!$left)
        {
            $data->finishedBy   = $this->app->user->account;
            $data->finishedDate = $now;
            $data->assignedTo   = $task->openedBy;
        }

        $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();

        $oldTask = new stdClass();
        $oldTask->consumed = $task->consumed;
        $oldTask->left     = $task->left;
        $oldTask->status   = $oldStatus;

        $newTask = new stdClass();
        $newTask->consumed = $consumed;
        $newTask->left     = $left;
        $newTask->status   = $task->status;
        if(!dao::isError()) return common::createChanges($oldTask, $newTask);
    }

    /**
     * Delete estimate.
     *
     * @param  int    $estimateID
     * @access public
     * @return void
     */
    public function deleteEstimate($estimateID)
    {
        $estimate = $this->getEstimateById($estimateID);
        $task     = $this->getById($estimate->task);
        $this->dao->delete()->from(TABLE_TASKESTIMATE)->where('id')->eq($estimateID)->exec();

        $lastEstimate = $this->dao->select('*')->from(TABLE_TASKESTIMATE)->where('task')->eq($estimate->task)->orderBy('date desc,id desc')->limit(1)->fetch();
        $consumed     = $task->consumed - $estimate->consumed;
        $left         = $lastEstimate->left ? $lastEstimate->left : $estimate->left;
        $oldStatus    = $task->status;
        if($left == 0 and $consumed != 0) $task->status = 'done';
        $this->dao->update(TABLE_TASK)
            ->set("consumed")->eq($consumed)
            ->set('`left`')->eq($left)
            ->set('status')->eq($task->status)
            ->where('id')->eq($estimate->task)
            ->exec();

        $oldTask = new stdClass();
        $oldTask->consumed = $task->consumed;
        $oldTask->left     = $task->left;
        $oldTask->status   = $oldStatus;

        $newTask = new stdClass();
        $newTask->consumed = $consumed;
        $newTask->left     = $left;
        $newTask->status   = $task->status;

        if(!dao::isError()) return common::createChanges($oldTask, $newTask);
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
            if(!empty($task->children))
            {
                foreach($task->children as $child) $task = $this->processTask($child);
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
    public function processTask($task)
    {
        $today = helper::today();

        /* Delayed or not?. */
        if($task->status !== 'done' and $task->status !== 'cancel' and $task->status != 'closed')
        {
            if($task->deadline != '0000-00-00')
            {
                $delay = helper::diffDate($today, $task->deadline);
                if($delay > 0) $task->delay = $delay;
            }
        }

        /* Story changed or not. */
        $task->needConfirm = false;
        if(!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion) $task->needConfirm = true;

        /* Set product type for task. */
        if(isset($task->product))
        {
            $product = $this->loadModel('product')->getById($task->product);
            $task->productType = $product->type;
        }

        /* Set closed realname. */
        if($task->assignedTo == 'closed') $task->assignedToRealName = 'Closed';

        /* Compute task progress. */
        if($task->consumed == 0 and $task->left == 0)
        {
            $task->progress = 0;
        }
        elseif($task->consumed != 0 and $task->left == 0)
        {
            $task->progress = 100;
        }
        else
        {
            $task->progress = round($task->consumed / ($task->consumed + $task->left), 2) * 100;
        }

        return $task;
    }

    /**
     * Check whether need update status of bug.
     *
     * @param  object  $task
     * @access public
     * @return void
     */
    public function needUpdateBugStatus($task)
    {
        /* If task is not from bug, return false. */
        if($task->fromBug == 0) return false;

        /* If bug has been resolved, return false. */
        $bug = $this->loadModel('bug')->getById($task->fromBug);
        if($bug->status == 'resolved') return false;

        return true;
    }

    /**
     * Get story comments.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryComments($storyID)
    {
        return $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->andWhere('objectID')->eq($storyID)
            ->andWhere('comment')->ne('')
            ->fetchAll();
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
        if(!isset($chartOption->type))   $chartOption->type   = $commonOption->type;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        /* merge configuration */
        foreach($commonOption->graph as $key => $value)
        {
            if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;
        }
    }

    /**
     * Get report data of tasks per project
     *
     * @access public
     * @return array
     */
    public function getDataOftasksPerProject()
    {
        $datas = $this->dao->select('project AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->groupBy('project')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        $projects = $this->loadModel('project')->getPairs('all');
        foreach($datas as $projectID => $data)
        {
            $data->name = isset($projects[$projectID]) ? $projects[$projectID] : $this->lang->report->undefined;
        }
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
        $datas = $this->dao->select('module AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->groupBy('module')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas),true,true);
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
        $datas = $this->dao->select('assignedTo AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->groupBy('assignedTo')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data)
        {
            if(isset($this->users[$account])) $data->name = $this->users[$account];
        }
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
        $datas = $this->dao->select('type AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->groupBy('type')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $type => $data)
        {
            if(isset($this->lang->task->typeList[$type])) $data->name = $this->lang->task->typeList[$type];
        }
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
        $priList = $this->dao->select('pri AS name, COUNT(*) AS value')
            ->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->groupBy('pri')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$priList) return array();

        foreach($priList as $index => $pri)
        {
            $priList[$index]->name = $this->lang->task->priList[$pri->name];
        }
        return $priList;
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
            ->andWhere('finishedBy')->ne('')
            ->groupBy('finishedBy')
            ->orderBy('value DESC')
            ->fetchAll('name');

        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data)
        {
            if(isset($this->users[$account])) $data->name = $this->users[$account];
        }
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
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
            ->where($this->reportCondition())
            ->groupBy('status')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $status => $data) $data->name = $this->lang->task->statusList[$status];
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
    public static function isClickable($task, $action)
    {
        $action = strtolower($action);

        if($action == 'start'          and !empty($task->children)) return false;
        if($action == 'recordestimate' and !empty($task->children)) return false;
        if($action == 'finish'         and !empty($task->children)) return false;
        if($action == 'cancel'         and !empty($task->children)) return false;
        if($action == 'pause'          and !empty($task->children)) return false;
        if($action == 'batchcreate'    and !empty($task->team))     return false;
        if($action == 'batchcreate'    and $task->parent)           return false;

        if($action == 'start')    return $task->status == 'wait';
        if($action == 'restart')  return $task->status == 'pause';
        if($action == 'pause')    return $task->status == 'doing';
        if($action == 'assignto') return $task->status != 'closed' and $task->status != 'cancel';
        if($action == 'close')    return $task->status == 'done'   or  $task->status == 'cancel';
        if($action == 'activate') return $task->status == 'done'   or  $task->status == 'closed'  or $task->status  == 'cancel';
        if($action == 'finish')   return $task->status != 'done'   and $task->status != 'closed'  and $task->status != 'cancel';
        if($action == 'cancel')   return $task->status != 'done'   and $task->status != 'closed'  and $task->status != 'cancel';

        return true;
    }

    /**
     * Get report condition from session.
     *
     * @access public
     * @return void
     */
    public function reportCondition()
    {
        if(isset($_SESSION['taskQueryCondition']))
        {
            if(!$this->session->taskOnlyCondition) return 'id in (' . preg_replace('/SELECT .* FROM/', 'SELECT t1.id FROM', $this->session->taskQueryCondition) . ')';
            return $this->session->taskQueryCondition;
        }
        return true;
    }

    /**
     * Add task estimate.
     *
     * @param  object    $data
     * @access public
     * @return void
     */
    public function addTaskEstimate($data)
    {
        $this->dao->insert(TABLE_TASKESTIMATE)->data($data)->autoCheck()->exec();
    }

    /**
     * Print cell data.
     *
     * @param object $col
     * @param object $task
     * @param array  $users
     * @param string $browseType
     * @param array  $branchGroups
     * @param array  $modulePairs
     * @param string $mode
     * @param bool   $child
     *
     * @access public
     * @return void
     */
    public function printCell($col, $task, $users, $browseType, $branchGroups, $modulePairs = array(), $mode = 'datatable', $child = false)
    {
        $canView  = common::hasPriv('task', 'view');
        $taskLink = helper::createLink('task', 'view', "taskID=$task->id");
        $account  = $this->app->user->account;
        $id       = $col->id;
        if($col->show)
        {
            $class = '';
            if($id == 'status') $class .= ' task-' . $task->status;
            if($id == 'id')     $class .= ' cell-id';
            if($id == 'name')   $class .= ' text-left';
            if($id == 'deadline' and isset($task->delay)) $class .= ' delayed';
            if($id == 'assignedTo' && $task->assignedTo == $account) $class .= ' red';

            $title = '';
            if($id == 'name')  $title = " title='{$task->name}'";
            if($id == 'story') $title = " title='{$task->storyTitle}'";

            echo "<td class='" . $class . "'" . $title . ">";
            switch($id)
            {
                case 'id':
                    if($mode == 'table') echo "<input type='checkbox' name='taskIDList[{$task->id}]' value='{$task->id}'/> ";
                    echo $canView ? html::a($taskLink, sprintf('%03d', $task->id)) : sprintf('%03d', $task->id);
                    break;
                case 'pri':
                    echo "<span class='pri" . zget($this->lang->task->priList, $task->pri) . "'>";
                    echo $task->pri == '0' ? '' : zget($this->lang->task->priList, $task->pri);
                    echo "</span>";
                    break;
                case 'name':
                    if(!empty($task->product) && isset($branchGroups[$task->product][$task->branch])) echo "<span class='label label-info label-badge'>" . $branchGroups[$task->product][$task->branch] . '</span> ';
                    if($task->module and isset($modulePairs[$task->module])) echo "<span class='label label-info label-badge'>" . $modulePairs[$task->module] . '</span> ';
                    if($child or !empty($task->parent)) echo '<span class="label">' . $this->lang->task->childrenAB . '</span> ';
                    if(!empty($task->team)) echo '<span class="label">' . $this->lang->task->multipleAB . '</span> ';
                    echo $canView ? html::a($taskLink, $task->name, null, "style='color: $task->color'") : "<span style='color: $task->color'>$task->name</span>";
                    if($task->fromBug) echo html::a(helper::createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '_blank', "class='bug'");
                    if(!empty($task->children)) echo '<span class="task-toggle" data-id="' . $task->id . '">&nbsp;&nbsp;<i class="icon icon-double-angle-up"></i>&nbsp;&nbsp;</span>';
                    break;
                case 'type':
                    echo $this->lang->task->typeList[$task->type];
                    break;
                case 'status':
                    $storyChanged = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion);
                    $storyChanged ? print("<span class='warning'>{$this->lang->story->changed}</span> ") : print($this->lang->task->statusList[$task->status]);
                    break;
                case 'estimate':
                    echo round($task->estimate, 1);
                    break;
                case 'consumed':
                    echo round($task->consumed, 1);
                    break;
                case 'left':
                    echo round($task->left, 1);
                    break;
                case 'progress':
                    echo "{$task->progress}%";
                    break;
                case 'deadline':
                    if(substr($task->deadline, 0, 4) > 0) echo substr($task->deadline, 5, 6);
                    break;
                case 'openedBy':
                    echo zget($users, $task->openedBy);
                    break;
                case 'openedDate':
                    echo substr($task->openedDate, 5, 11);
                    break;
                case 'estStarted':
                    echo $task->estStarted;
                    break;
                case 'realStarted':
                    echo $task->realStarted;
                    break;
                case 'assignedTo':
                    echo zget($users, $task->assignedTo);
                    break;
                case 'assignedDate':
                    echo substr($task->assignedDate, 5, 11);
                    break;
                case 'finishedBy':
                    echo zget($users, $task->finishedBy);
                    break;
                case 'finishedDate':
                    echo substr($task->finishedDate, 5, 11);
                    break;
                case 'canceledBy':
                    echo zget($users, $task->canceledBy);
                    break;
                case 'canceledDate':
                    echo substr($task->canceledDate, 5, 11);
                    break;
                case 'closedBy':
                    echo zget($users, $task->closedBy);
                    break;
                case 'closedDate':
                    echo substr($task->closedDate, 5, 11);
                    break;
                case 'closedReason':
                    echo $this->lang->task->reasonList[$task->closedReason];
                    break;
                case 'story':
                    if(!empty($task->storyID))
                    {
                        if(common::hasPriv('story', 'view'))
                        {
                            echo html::a(helper::createLink('story', 'view', "storyid=$task->storyID", 'html', true), "<i class='icon icon-{$this->lang->icons['story']}'></i>", '', "class='iframe' title='{$task->storyTitle}'");
                        }
                        else
                        {
                            echo "<i class='icon icon-{$this->lang->icons['story']}' title='{$task->storyTitle}'></i>";
                        }
                    }
                    break;
                case 'mailto':
                    $mailto = explode(',', $task->mailto);
                    foreach($mailto as $account)
                    {
                        $account = trim($account);
                        if(empty($account)) continue;
                        echo zget($users, $account) . ' &nbsp;';
                    }
                    break;
                case 'lastEditedBy':
                    echo zget($users, $task->lastEditedBy);
                    break;
                case 'lastEditedDate':
                    echo substr($task->lastEditedDate, 5, 11);
                    break;
                case 'actions':
                    common::printIcon('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                    common::printIcon('task', 'start',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);

                    common::printIcon('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
                    if($browseType == 'needconfirm')
                    {
                        $this->lang->task->confirmStoryChange = $this->lang->confirm;
                        common::printIcon('task', 'confirmStoryChange', "taskid=$task->id", '', 'list', '', 'hiddenwin');
                    }
                    common::printIcon('task', 'finish', "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                    common::printIcon('task', 'close',  "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
                    common::printIcon('task', 'edit',   "taskID=$task->id", $task, 'list');
                    if(empty($task->team) or empty($task->children))
                    {
                        common::printIcon('task', 'batchCreate', "project=$task->project&storyID=$task->story&moduleID=$task->module&taskID=$task->id", $task, 'list', 'plus', '', '', '', '', $this->lang->task->children);
                    }
                    break;
            }
            echo '</td>';
        }
    }

    /**
     * Send mail.
     *
     * @param  int    $taskID
     * @param  int    $actionID
     * @access public
     * @return void
     */
    public function sendmail($taskID, $actionID)
    {
        $this->loadModel('mail');
        $task  = $this->getById($taskID);
        $users = $this->loadModel('user')->getPairs('noletter');

        /* Get action info. */
        $action          = $this->loadModel('action')->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();

        /* Get mail content. */
        $oldcwd     = getcwd();
        $modulePath = $this->app->getModulePath($appName = '', 'task');
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');

        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }

        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();

        chdir($oldcwd);

        $sendUsers = $this->getToAndCcList($task);
        if(!$sendUsers) return;
        list($toList, $ccList) = $sendUsers;
        $subject = $this->getSubject($task);

        /* Send emails. */
        $this->mail->send($toList, $subject, $mailContent, $ccList);
        if($this->mail->isError()) trigger_error(join("\n", $this->mail->getError()));
    }

    /**
     * Get mail subject.
     *
     * @param  object    $task
     * @access public
     * @return string
     */
    public function getSubject($task)
    {
        $projectName = $this->loadModel('project')->getById($task->project)->name;
        return 'TASK#' . $task->id . ' ' . $task->name . ' - ' . $projectName;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $task
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($task)
    {
        /* Set toList and ccList. */
        $toList = $task->assignedTo;
        $ccList = trim($task->mailto, ',');

        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        elseif(strtolower($toList) == 'closed')
        {
            $toList = $task->finishedBy;
        }

        return array($toList, $ccList);
    }

    /**
     * Get next user.
     *
     * @param  string $users
     * @param  string $current
     *
     * @access public
     * @return void
     */
    public function getNextUser($users, $current)
    {
        /* Process user */
        if(!is_array($users)) $users = explode(',', trim($users, ','));
        if(!$current || !in_array($current, $users) || array_search($current, $users) == max(array_keys($users)))
        {
            return reset($users);
        }

        $next = '';
        while(true)
        {
            if(current($users) == $current)
            {
                $next = next($users);
                break;
            }
            else
            {
                next($users);
            }
        }
        return $next;
    }

    /**
     * Get task's team member pairs.
     *
     * @param  object $task
     *
     * @access public
     * @return array
     */
    public function getMemberPairs($task)
    {
        $users   = $this->loadModel('project')->getTeamMemberPairs($task->project, 'nodeleted');
        $members = array('');
        foreach($task->team as $member)
        {
            if(isset($users[$member->account])) $members[$member->account] = $users[$member->account];
        }
        return $members;
    }
}
