<?php
/**
 * The model file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function create($executionID, $bugID)
    {
        if((float)$this->post->estimate < 0)
        {
            dao::$errors[] = $this->lang->task->error->recordMinus;
            return false;
        }

        $executionID    = (int)$executionID;
        $taskIdList     = array();
        $taskFiles      = array();
        $requiredFields = "," . $this->config->task->create->requiredFields . ",";

        if($this->post->selectTestStory)
        {
            /* Check required fields when create test task. */
            foreach($this->post->testStory as $i => $storyID)
            {
                if(empty($storyID)) continue;

                $task = new stdclass();
                $task->pri        = $this->post->testPri[$i];
                $task->estStarted = $this->post->testEstStarted[$i];
                $task->deadline   = $this->post->testDeadline[$i];
                $task->assignedTo = $this->post->testAssignedTo[$i];
                $task->estimate   = $this->post->testEstimate[$i];
                $task->left       = $this->post->testEstimate[$i];

                $this->dao->insert(TABLE_TASK)->data($task)->batchCheck($requiredFields, 'notempty');
                if(dao::isError())
                {
                    foreach(dao::getError() as $field => $error)
                    {
                        dao::$errors[] = $error;
                        return false;
                    }
                }
            }

            $requiredFields = str_replace(",estimate,", ',', "$requiredFields");
            $requiredFields = str_replace(",story,", ',', "$requiredFields");
            $requiredFields = str_replace(",estStarted,", ',', "$requiredFields");
            $requiredFields = str_replace(",deadline,", ',', "$requiredFields");
        }

        $this->loadModel('file');
        $task = fixer::input('post')
            ->setDefault('execution', $executionID)
            ->setDefault('estimate,left,story', 0)
            ->setDefault('status', 'wait')
            ->setIF($this->config->systemMode == 'new', 'project', $this->getProjectID($executionID))
            ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setDefault('estStarted', '0000-00-00')
            ->setDefault('deadline', '0000-00-00')
            ->setIF(strpos($requiredFields, 'estStarted') !== false, 'estStarted', helper::isZeroDate($this->post->estStarted) ? '' : $this->post->estStarted)
            ->setIF(strpos($requiredFields, 'deadline') !== false, 'deadline', helper::isZeroDate($this->post->deadline) ? '' : $this->post->deadline)
            ->setIF(strpos($requiredFields, 'estimate') !== false, 'estimate', $this->post->estimate)
            ->setIF(strpos($requiredFields, 'left') !== false, 'left', $this->post->left)
            ->setIF(strpos($requiredFields, 'story') !== false, 'story', $this->post->story)
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(is_numeric($this->post->left),     'left',     (float)$this->post->left)
            ->setDefault('openedBy',   $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->cleanINT('execution,story,module')
            ->stripTags($this->config->task->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('after,files,labels,assignedTo,uid,storyEstimate,storyDesc,storyPri,team,teamEstimate,teamMember,multiple,teams,contactListMenu,selectTestStory,testStory,testPri,testEstStarted,testDeadline,testAssignedTo,testEstimate,sync,otherLane,region,lane')
            ->add('version', 1)
            ->get();

        if($task->type != 'test') $this->post->set('selectTestStory', 0);

        foreach($this->post->assignedTo as $assignedTo)
        {
            /* When type is affair and has assigned then ignore none. */
            if($task->type == 'affair' and count($this->post->assignedTo) > 1 and empty($assignedTo)) continue;

            $task->assignedTo = $assignedTo;
            if($assignedTo) $task->assignedDate = helper::now();

            /* Check duplicate task. */
            if($task->type != 'affair' and $task->name)
            {
                $result = $this->loadModel('common')->removeDuplicate('task', $task, "execution={$executionID} and story=" . (int)$task->story . (isset($task->feedback) ? " and feedback=" . (int)$task->feedback : ''));
                if($result['stop'])
                {
                    $taskIdList[$assignedTo] = array('status' => 'exists', 'id' => $result['duplicate']);
                    continue;
                }
            }

            $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], $this->post->uid);

            /* Fix Bug #1525 */
            $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch();
            if($execution->lifetime == 'ops' or $execution->attribute == 'request' or $execution->attribute == 'review')
            {
                $requiredFields = str_replace(",story,", ',', "$requiredFields");
                $task->story = 0;
            }

            if(strpos($requiredFields, ',estimate,') !== false)
            {
                if(strlen(trim($task->estimate)) == 0) dao::$errors['estimate'] = sprintf($this->lang->error->notempty, $this->lang->task->estimate);
                $requiredFields = str_replace(',estimate,', ',', $requiredFields);
            }

            $requiredFields = trim($requiredFields, ',');

            /* Fix Bug #2466 */
            if($this->post->multiple) $task->assignedTo = '';
            if(!$this->post->multiple or count(array_filter($this->post->team)) < 1) $task->mode = '';
            $this->dao->insert(TABLE_TASK)->data($task, $skip = 'gitlab,gitlabProject')
                ->autoCheck()
                ->batchCheck($requiredFields, 'notempty')
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)
                ->checkFlow()
                ->exec();

            if(dao::isError()) return false;

            $taskID = $this->dao->lastInsertID();

            if($bugID > 0)
            {
                $this->dao->update(TABLE_TASK)->set('fromBug')->eq($bugID)->where('id')->eq($taskID)->exec();
                $this->loadModel('action')->create('bug', $bugID, 'converttotask', '', $taskID);
            }

            /* Mark design version.*/
            if(isset($task->design) && !empty($task->design))
            {
                $design = $this->loadModel('design')->getByID($task->design);
                $this->dao->update(TABLE_TASK)->set('designVersion')->eq($design->version)->where('id')->eq($taskID)->exec();
            }

            $taskSpec = new stdClass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $task->version;
            $taskSpec->name       = $task->name;
            $taskSpec->estStarted = $task->estStarted;
            $taskSpec->deadline   = $task->deadline;

            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            if(dao::isError()) return false;

            if($this->post->story) $this->loadModel('story')->setStage($this->post->story);
            if($this->post->selectTestStory)
            {
                $testStoryIdList = array();
                $this->loadModel('action');
                if($this->post->testStory)
                {
                    foreach($this->post->testStory as $storyID)
                    {
                        if($storyID) $testStoryIdList[$storyID] = $storyID;
                    }
                    $testStories = $this->dao->select('id,title,version')->from(TABLE_STORY)->where('id')->in($testStoryIdList)->fetchAll('id');
                    foreach($this->post->testStory as $i => $storyID)
                    {
                        if(!isset($testStories[$storyID])) continue;

                        $task->parent       = $taskID;
                        $task->story        = $storyID;
                        $task->storyVersion = $testStories[$storyID]->version;
                        $task->name         = $this->lang->task->lblTestStory . " #{$storyID} " . $testStories[$storyID]->title;
                        $task->pri          = $this->post->testPri[$i];
                        $task->estStarted   = $this->post->testEstStarted[$i];
                        $task->deadline     = $this->post->testDeadline[$i];
                        $task->assignedTo   = $this->post->testAssignedTo[$i];
                        $task->estimate     = $this->post->testEstimate[$i];
                        $task->left         = $this->post->testEstimate[$i];
                        $this->dao->insert(TABLE_TASK)->data($task)->exec();

                        $childTaskID = $this->dao->lastInsertID();
                        $this->action->create('task', $childTaskID, 'Opened');
                    }

                    $this->computeWorkingHours($taskID);
                    $this->computeBeginAndEnd($taskID);
                    $this->dao->update(TABLE_TASK)->set('parent')->eq(-1)->where('id')->eq($taskID)->exec();
                }
            }
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

            $teams = array();
            if($this->post->multiple and count(array_filter($this->post->team)) > 1)
            {
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
                }
            }

            if(!empty($teams))
            {
                foreach($teams as $team)
                {
                    $team->root = $taskID;
                    $team->type = 'task';
                    $this->dao->insert(TABLE_TEAM)->data($team)->autoCheck()->exec();
                }

                $task->id = $taskID;
                $this->computeHours4Multiple($task);
            }

            if(!dao::isError()) $this->loadModel('score')->create('task', 'create', $taskID);
            $taskIdList[$assignedTo] = array('status' => 'created', 'id' => $taskID);
        }
        return $taskIdList;
    }

    /**
     * Create a batch task.
     *
     * @param  int    $executionID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function batchCreate($executionID, $extra = '')
    {
        /* Load module and init vars. */
        $this->loadModel('action');
        $this->loadModel('kanban');
        $now       = helper::now();
        $mails     = array();
        $storyIDs  = array();
        $taskNames = array();
        $preStory  = 0;
        $tasks     = fixer::input('post')->get();

        if($this->config->vision == 'lite')
        {
            $lanes   = $tasks->lane;
            $columns = $tasks->column;
            unset($tasks->lane);
            unset($tasks->column);
        }
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        /* Judge whether the current task is a parent. */
        $parentID = !empty($this->post->parent[1]) ? $this->post->parent[1] : 0;

        foreach($tasks->story as $key => $storyID)
        {
            if(empty($tasks->name[$key])) continue;
            if($tasks->type[$key] == 'affair') continue;
            if($tasks->type[$key] == 'ditto' && isset($tasks->type[$key - 1]) && $tasks->type[$key - 1] == 'affair') continue;

            if($storyID == 'ditto') $storyID = $preStory;
            $preStory = $storyID;

            if(!isset($tasks->story[$key - 1]) and $key > 1 and !empty($tasks->name[$key - 1]))
            {
                $storyIDs[]  = 0;
                $taskNames[] = $tasks->name[$key - 1];
            }

            $inNames = in_array($tasks->name[$key], $taskNames);
            if(!$inNames || ($inNames && !in_array($storyID, $storyIDs)))
            {
                $storyIDs[]  = $storyID;
                $taskNames[] = $tasks->name[$key];
            }
            else
            {
                dao::$errors['message'][] = sprintf($this->lang->duplicate, $this->lang->task->common) . ' ' . $tasks->name[$key];
                return false;
            }
        }

        $result = $this->loadModel('common')->removeDuplicate('task', $tasks, "execution=$executionID and story " . helper::dbIN($storyIDs));
        $tasks  = $result['data'];

        $story      = 0;
        $module     = 0;
        $type       = '';
        $assignedTo = '';
        $estStarted = '0000-00-00';
        $deadline   = '0000-00-00';

        /* Get task data. */
        $extendFields = $this->getFlowExtendFields();
        $projectID    = $this->getProjectID($executionID);
        $data         = array();
        foreach($tasks->name as $i => $name)
        {
            $story      = (!isset($tasks->story[$i]) or $tasks->story[$i] == 'ditto')            ? $story      : $tasks->story[$i];
            $module     = (!isset($tasks->module[$i]) or $tasks->module[$i] == 'ditto')          ? $module     : $tasks->module[$i];
            $type       = (!isset($tasks->type[$i]) or $tasks->type[$i] == 'ditto')              ? $type       : $tasks->type[$i];
            $assignedTo = (!isset($tasks->assignedTo[$i]) or $tasks->assignedTo[$i] == 'ditto')  ? $assignedTo : $tasks->assignedTo[$i];
            $estStarted = (!isset($tasks->estStarted[$i]) or isset($tasks->estStartedDitto[$i])) ? $estStarted : $tasks->estStarted[$i];
            $deadline   = (!isset($tasks->deadline[$i]) or isset($tasks->deadlineDitto[$i]))     ? $deadline   : $tasks->deadline[$i];

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
            $data[$i]->project    = $this->config->systemMode == 'new' ? $projectID : 0;
            $data[$i]->execution  = $executionID;
            $data[$i]->estStarted = $estStarted;
            $data[$i]->deadline   = $deadline;
            $data[$i]->status     = 'wait';
            $data[$i]->openedBy   = $this->app->user->account;
            $data[$i]->openedDate = $now;
            $data[$i]->parent     = $tasks->parent[$i];
            $data[$i]->vision     = isset($tasks->vision[$i]) ? $tasks->vision[$i] : 'rnd';
            if($story) $data[$i]->storyVersion = $this->loadModel('story')->getVersion($data[$i]->story);
            if($assignedTo) $data[$i]->assignedDate = $now;
            if(strpos($this->config->task->create->requiredFields, 'estStarted') !== false and empty($estStarted)) $data[$i]->estStarted = '';
            if(strpos($this->config->task->create->requiredFields, 'deadline') !== false and empty($deadline))     $data[$i]->deadline   = '';
            if(isset($tasks->lanes[$i])) $data[$i]->laneID = $tasks->lanes[$i];

            foreach($extendFields as $extendField)
            {
                $data[$i]->{$extendField->field} = $this->post->{$extendField->field}[$i];
                if(is_array($data[$i]->{$extendField->field})) $data[$i]->{$extendField->field} = join(',', $data[$i]->{$extendField->field});

                $data[$i]->{$extendField->field} = htmlSpecialString($data[$i]->{$extendField->field});
            }
        }

        /* Fix bug #1525*/
        $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch();
        $requiredFields = ',' . $this->config->task->create->requiredFields . ',';
        if($execution->lifetime == 'ops' or $execution->attribute == 'request' or $execution->attribute == 'review') $requiredFields = str_replace(',story,', ',', $requiredFields);
        $requiredFields = trim($requiredFields, ',');

        /* check data. */
        foreach($data as $i => $task)
        {
            if(!helper::isZeroDate($task->deadline) and $task->deadline < $task->estStarted)
            {
                dao::$errors['message'][] = $this->lang->task->error->deadlineSmall;
                return false;
            }

            if($task->estimate and !preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $task->estimate))
            {
                dao::$errors['message'][] = $this->lang->task->error->estimateNumber;
                return false;
            }

            foreach(explode(',', $requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!isset($task->$field)) continue;
                if(!empty($task->$field)) continue;
                if($field == 'estimate' and strlen(trim($task->estimate)) != 0) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
                return false;
            }
            if($task->estimate) $task->estimate = (float)$task->estimate;
        }

        $childTasks = null;

        foreach($data as $i => $task)
        {
            $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
            if(isset($task->laneID))
            {
                $laneID = $task->laneID;
                unset($task->laneID);
            }

            $task->version = 1;
            $this->dao->insert(TABLE_TASK)->data($task)
                ->autoCheck()
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->checkFlow()
                ->exec();

            if(dao::isError()) return false;

            $taskID   = $this->dao->lastInsertID();

            $taskSpec = new stdClass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $task->version;
            $taskSpec->name       = $task->name;
            $taskSpec->estStarted = $task->estStarted;
            $taskSpec->deadline   = $task->deadline;

            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            if(dao::isError()) return false;

            $childTasks .= $taskID . ',';
            if($task->story) $this->story->setStage($task->story);

            $this->executeHooks($taskID);

            if($this->config->vision == 'lite')
            {
                $this->kanban->addKanbanCell($executionID, $lanes[$i], $columns[$i], 'task', $taskID);
            }
            else
            {
                $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'wait');
                if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

                if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'task', $taskID);
            }

            $actionID = $this->action->create('task', $taskID, 'Opened', '');
            if(!dao::isError()) $this->loadModel('score')->create('task', 'create', $taskID);

            $mails[$i] = new stdclass();
            $mails[$i]->taskID   = $taskID;
            $mails[$i]->actionID = $actionID;
        }

        if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchCreate');

        if($parentID > 0 && !empty($taskID))
        {
            $oldParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq((int)$parentID)->fetch();

            /* When common task are child tasks and the common task has consumption, create a child task. */
            if($oldParentTask->parent == 0 and $oldParentTask->consumed > 0)
            {
                $clonedTask = clone $oldParentTask;
                unset($clonedTask->id);
                $clonedTask->parent = $parentID;
                $this->dao->insert(TABLE_TASK)->data($clonedTask)->autoCheck()->exec();

                $clonedTaskID = $this->dao->lastInsertID();

                /* Update the table by judging the beginning of the version number. */
                if(preg_match('/^\d/', $this->config->version))
                {
                    /* ZenTao Pms update TABLE_TASKESTIMATE. */
                    $this->dao->update(TABLE_TASKESTIMATE)->set('task')->eq($clonedTaskID)->where('task')->eq($oldParentTask->id)->exec();
                }
                else
                {
                    /* ZenTao Pro and ZenTao Biz update TABLE_EFFORT. */
                    $this->dao->update(TABLE_EFFORT)->set('objectID')->eq($clonedTaskID)->where('objectID')->eq($oldParentTask->id)->exec();
                }
            }

            $this->updateParentStatus($taskID);
            $this->computeBeginAndEnd($parentID);

            $task = new stdclass();
            $task->parent         = '-1';
            $task->lastEditedBy   = $this->app->user->account;
            $task->lastEditedDate = $now;
            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($parentID)->exec();

            $newParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq((int)$parentID)->fetch();
            $changes       = common::createChanges($oldParentTask, $newParentTask);
            $actionID      = $this->action->create('task', $parentID, 'createChildren', '', trim($childTasks, ','));
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
        }

        if(!isset($output['laneID']) or !isset($output['columnID']) or !isset($lanes)) $this->kanban->updateLane($executionID, 'task');
        return $mails;
    }

    /**
     * Create task from gitlab issue.
     *
     * @param  object    $task
     * @param  int       $executionID
     * @access public
     * @return int
     */
    public function createTaskFromGitlabIssue($task, $executionID)
    {
        $task->version      = 1;
        $task->openedBy     = $this->app->user->account;
        $task->lastEditedBy = $this->app->user->account;
        $task->assignedDate = isset($task->assignedTo) ? helper::now() : 0;
        $task->story        = 0;
        $task->module       = 0;
        $task->estimate     = 0;
        $task->estStarted   = '0000-00-00';
        $task->left         = 0;
        $task->pri          = 3;
        $task->type         = 'devel';
        $task->project      = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch('project');

        $this->dao->insert(TABLE_TASK)->data($task, $skip = 'id,product')
             ->autoCheck()
             ->batchCheck($this->config->task->create->requiredFields, 'notempty')
             ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)
             ->exec();

        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
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

        $tasks = $this->dao->select('`id`,`estimate`,`consumed`,`left`, status')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('status')->ne('cancel')->andWhere('deleted')->eq(0)->fetchAll('id');
        if(empty($tasks)) return true;

        $estimate = 0;
        $consumed = 0;
        $left     = 0;
        foreach($tasks as $task)
        {
            $estimate += $task->estimate;
            $consumed += $task->consumed;
            if($task->status != 'closed') $left += $task->left;
        }

        $newTask = new stdClass();
        $newTask->estimate       = $estimate;
        $newTask->consumed       = $consumed;
        $newTask->left           = $left;
        $newTask->lastEditedBy   = $this->app->user->account;
        $newTask->lastEditedDate = helper::now();

        $this->dao->update(TABLE_TASK)->data($newTask)->autoCheck()->where('id')->eq($taskID)->exec();
        return !dao::isError();
    }

    /**
     * Compute begin and end for parent task.
     *
     * @param  int    $taskID
     * @access public
     * @return bool
     */
    public function computeBeginAndEnd($taskID)
    {
        $tasks = $this->dao->select('estStarted, realStarted, deadline')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('status')->ne('cancel')->andWhere('deleted')->eq(0)->fetchAll();
        if(empty($tasks)) return true;

        foreach($tasks as $task)
        {
            $estStarted  = formatTime($task->estStarted);
            $realStarted = formatTime($task->realStarted);
            $deadline    = formatTime($task->deadline);
            if(!isset($earliestEstStarted) or (!empty($estStarted) and $earliestEstStarted > $estStarted))     $earliestEstStarted  = $estStarted;
            if(!isset($earliestRealStarted) or (!empty($realStarted) and $earliestRealStarted > $realStarted)) $earliestRealStarted = $realStarted;
            if(!isset($latestDeadline) or (!empty($deadline) and $latestDeadline < $deadline))                 $latestDeadline      = $deadline;
        }

        $newTask = new stdClass();
        $newTask->estStarted  = $earliestEstStarted;
        $newTask->realStarted = $earliestRealStarted;
        $newTask->deadline    = $latestDeadline;
        $this->dao->update(TABLE_TASK)->data($newTask)->autoCheck()->where('id')->eq($taskID)->exec();

        return !dao::isError();
    }

    /**
     * Update parent status by taskID.
     *
     * @param $taskID
     *
     * @access public
     * @return bool
     */
    public function updateParentStatus($taskID, $parentID = 0, $createAction = true)
    {
        $childTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        if(empty($parentID)) $parentID = $childTask->parent;
        if($parentID <= 0) return true;

        $oldParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($parentID)->fetch();
        if($oldParentTask->parent != '-1') $this->dao->update(TABLE_TASK)->set('parent')->eq('-1')->where('id')->eq($parentID)->exec();
        $this->computeWorkingHours($parentID);

        $childrenStatus       = $this->dao->select('id,status')->from(TABLE_TASK)->where('parent')->eq($parentID)->andWhere('deleted')->eq('0')->fetchPairs('status', 'status');
        $childrenClosedReason = $this->dao->select('closedReason')->from(TABLE_TASK)->where('parent')->eq($parentID)->andWhere('deleted')->eq('0')->fetchPairs('closedReason');
        if(empty($childrenStatus)) return $this->dao->update(TABLE_TASK)->set('parent')->eq('0')->where('id')->eq($parentID)->exec();

        $status = '';
        if(count($childrenStatus) == 1)
        {
            $status = current($childrenStatus);
        }
        else
        {
            if(isset($childrenStatus['doing']) or isset($childrenStatus['pause']))
            {
                $status = 'doing';
            }
            elseif((isset($childrenStatus['done']) or isset($childrenClosedReason['done'])) && isset($childrenStatus['wait']))
            {
                $status = 'doing';
            }
            elseif(isset($childrenStatus['wait']))
            {
                $status = 'wait';
            }
            elseif(isset($childrenStatus['done']))
            {
                $status = 'done';
            }
            elseif(isset($childrenStatus['closed']))
            {
                $status = 'closed';
            }
            elseif(isset($childrenStatus['cancel']))
            {
                $status = 'cancel';
            }
        }

        $parentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($parentID)->andWhere('deleted')->eq(0)->fetch();
        if(empty($parentTask)) return $this->dao->update(TABLE_TASK)->set('parent')->eq('0')->where('id')->eq($taskID)->exec();

        if($status and $parentTask->status != $status)
        {
            $now  = helper::now();
            $task = new stdclass();
            $task->status = $status;
            if($status == 'done')
            {
                $task->assignedTo   = $parentTask->openedBy;
                $task->assignedDate = $now;
                $task->finishedBy   = $this->app->user->account;
                $task->finishedDate = $now;
            }

            if($status == 'cancel')
            {
                $task->assignedTo   = $parentTask->openedBy;
                $task->assignedDate = $now;
                $task->finishedBy   = '';
                $task->finishedDate = '';
                $task->canceledBy   = $this->app->user->account;
                $task->canceledDate = $now;
            }

            if($status == 'closed')
            {
                $task->assignedTo   = 'closed';
                $task->assignedDate = $now;
                $task->closedBy     = $this->app->user->account;
                $task->closedDate   = $now;
                $task->closedReason = 'done';
            }

            if($status == 'doing' or $status == 'wait')
            {
                if($parentTask->assignedTo == 'closed')
                {
                    $task->assignedTo   = $childTask->assignedTo;
                    $task->assignedDate = $now;
                }
                $task->finishedBy   = '';
                $task->finishedDate = '';
                $task->closedBy     = '';
                $task->closedDate   = '';
                $task->closedReason = '';
            }

            $task->lastEditedBy   = $this->app->user->account;
            $task->lastEditedDate = $now;
            $task->parent         = '-1';
            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($parentID)->exec();
            if(!dao::isError())
            {
                if(!$createAction) return $task;

                if($parentTask->story) $this->loadModel('story')->setStage($parentTask->story);
                $newParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($parentID)->fetch();

                unset($oldParentTask->subStatus);
                unset($newParentTask->subStatus);
                $changes = common::createChanges($oldParentTask, $newParentTask);
                $action  = '';
                if($status == 'done' and $parentTask->status != 'done')     $action = 'Finished';
                if($status == 'closed' and $parentTask->status != 'closed') $action = 'Closed';
                if($status == 'pause' and $parentTask->status != 'paused')  $action = 'Paused';
                if($status == 'cancel' and $parentTask->status != 'cancel') $action = 'Canceled';
                if($status == 'doing' and $parentTask->status == 'wait')    $action = 'Started';
                if($status == 'doing' and $parentTask->status == 'pause')   $action = 'Restarted';
                if($status == 'doing' and $parentTask->status != 'wait' and $parentTask->status != 'pause') $action = 'Activated';
                if($status == 'wait' and $parentTask->status != 'wait')     $action = 'Adjusttasktowait';
                if($action)
                {
                    $actionID = $this->loadModel('action')->create('task', $parentID, $action, '', '', '', false);
                    $this->action->logHistory($actionID, $changes);
                }

                if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $oldParentTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldParentTask->feedback, $newParentTask->status, $oldParentTask->status);
            }
        }
        else
        {
            if(!dao::isError())
            {
                $newParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($parentID)->fetch();
                $changes = common::createChanges($oldParentTask, $newParentTask);
                if($changes)
                {
                    $actionID = $this->loadModel('action')->create('task', $parentID, 'Edited', '', '', '', false);
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }
    }

    /**
     * Compute hours for multiple task.
     *
     * @param  object  $oldTask
     * @param  object  $task
     * @param  bool    $autoStatus
     * @access public
     * @return object|bool
     */
    public function computeHours4Multiple($oldTask, $task = null, $team = array(), $autoStatus = true)
    {
        if(!$oldTask) return false;

        if(empty($team)) $team = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($oldTask->id)->andWhere('type')->eq('task')->orderBy('order')->fetchAll('account');
        if(!empty($team))
        {
            $now         = helper::now();
            $teams       = array_keys($team);
            $currentTask = !empty($task) ? $task : new stdclass();
            if(!isset($currentTask->status)) $currentTask->status = $oldTask->status;

            $currentTask->assignedTo = $oldTask->assignedTo;
            if(!empty($_POST['assignedTo']) and is_string($_POST['assignedTo']))
            {
                $currentTask->assignedTo = $this->post->assignedTo;
            }
            else
            {
                if(!$oldTask->assignedTo)
                {
                    $firstMember = reset($team);
                    $currentTask->assignedTo   = $firstMember->account;
                    $currentTask->assignedDate = $now;
                }
                else
                {
                    if($team[$oldTask->assignedTo]->left == 0 and $team[$oldTask->assignedTo]->consumed != 0 and $this->app->rawMethod != 'deleteestimate')
                    {
                        if($oldTask->assignedTo != $teams[count($teams) - 1])
                        {
                            $currentTask->assignedTo = $this->getNextUser(array_keys($team), $oldTask->assignedTo);
                        }
                        else
                        {
                            $currentTask->assignedTo = $oldTask->openedBy;
                        }
                        $currentTask->assignedDate = $now;
                    }
                }
            }

            $currentTask->estimate = 0;
            $currentTask->consumed = 0;
            $currentTask->left     = 0;
            foreach($team as $member)
            {
                $currentTask->estimate += (float)$member->estimate;
                $currentTask->consumed += (float)$member->consumed;
                $currentTask->left     += (float)$member->left;
            }

            if($this->app->rawMethod == 'edit' and empty($oldTask->team) and isset($oldTask->consumed)) $currentTask->consumed += (float)$oldTask->consumed;

            if(!empty($task))
            {
                if(!$autoStatus) return $currentTask;

                if($currentTask->consumed == 0)
                {
                    if(!isset($task->status)) $currentTask->status = 'wait';
                    $currentTask->finishedBy   = '';
                    $currentTask->finishedDate = '';
                }

                if($currentTask->consumed > 0 && $currentTask->left > 0)
                {
                    $currentTask->status       = 'doing';
                    $currentTask->finishedBy   = '';
                    $currentTask->finishedDate = '';
                }

                if($currentTask->consumed > 0 and $currentTask->left == 0)
                {
                    $finisedUsers = $this->getFinishedUsers($oldTask->id, $teams);
                    if(($oldTask->mode == 'linear' and isset($team[$currentTask->assignedTo]) and $oldTask->assignedTo != $teams[count($teams) - 1]) or ($oldTask->mode == 'multi' and count($finisedUsers) != count($teams)))
                    {
                        $currentTask->status       = 'doing';
                        $currentTask->finishedBy   = '';
                        $currentTask->finishedDate = '';
                    }
                    elseif(($oldTask->mode == 'linear' and $oldTask->assignedTo == $teams[count($teams) - 1]) or $oldTask->mode == 'multi')
                    {
                        $currentTask->status = 'done';
                        $currentTask->finishedBy   = $this->app->user->account;
                        $currentTask->finishedDate = $task->finishedDate;
                    }
                }

                if(($oldTask->assignedTo != $currentTask->assignedTo or $currentTask->status == 'done')
                    and isset($team[$this->app->user->account]) and $team[$this->app->user->account]->left == 0
                    and strpos($oldTask->finishedList, ",{$this->app->user->account},") === false)
                {
                    $currentTask->finishedList = ',' . trim(trim($oldTask->finishedList, ',') . ",{$this->app->user->account}", ',') . ',';
                }
                if(($oldTask->status == 'done' or $oldTask->status == 'closed') and $currentTask->status == 'doing' and $this->post->assignedTo)
                {
                    $currentTask->finishedList = ',' . trim(substr($oldTask->finishedList, 0, strpos($oldTask->finishedList, ",{$this->post->assignedTo},")), ',') . ',';
                }

                return $currentTask;
            }
            $this->dao->update(TABLE_TASK)->data($currentTask)->autoCheck()->where('id')->eq($oldTask->id)->exec();
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
        if($taskID <= 0) return;

        $oldTask = $this->getByID($taskID);
        if($this->post->estimate < 0 or $this->post->left < 0 or $this->post->consumed < 0)
        {
            dao::$errors[] = $this->lang->task->error->recordMinus;
            return false;
        }

        if(!empty($_POST['lastEditedDate']) and $oldTask->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        /* If a multiple task is assigned to a team member who is not the task, assign to the team member instead. */
        if(!$this->post->assignedTo and !empty($_POST['team']) and !in_array($oldTask->assignedTo, $this->post->team))
        {
            $_POST['assignedTo'] = reset($_POST['team']);
        }

        /* When the selected parent task is a common task and has consumption, select other parent tasks. */
        if($this->post->parent > 0)
        {
            $taskConsumed = 0;
            $taskConsumed = $this->dao->select('consumed')->from(TABLE_TASK)->where('id')->eq($this->post->parent)->andWhere('parent')->eq(0)->fetch('consumed');
            if($taskConsumed > 0) return print(js::error($this->lang->task->error->alreadyConsumed));
        }

        $now  = helper::now();
        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('story, estimate, left, consumed', 0)
            ->setDefault('realStarted', '0000-00-00 00:00:00')
            ->setDefault('mailto', '')
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(is_numeric($this->post->left),     'left',     (float)$this->post->left)
            ->setIF($oldTask->parent == 0 && $this->post->parent == '', 'parent', 0)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'estStarted') !== false, 'estStarted', $this->post->estStarted)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'deadline') !== false, 'deadline', $this->post->deadline)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'estimate') !== false, 'estimate', $this->post->estimate)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'left') !== false,     'left',     $this->post->left)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'consumed') !== false, 'consumed', $this->post->consumed)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'story') !== false,    'story',    $this->post->story)
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

            ->setIF($this->post->status == 'wait' and $this->post->left == $oldTask->left and $this->post->consumed == 0 and $this->post->estimate, 'left', $this->post->estimate)
            ->setIF($oldTask->parent > 0 and !$this->post->parent, 'parent', 0)
            ->setIF($oldTask->parent < 0, 'estimate', $oldTask->estimate)
            ->setIF($oldTask->parent < 0, 'left', $oldTask->left)

            ->setIF($oldTask->name != $this->post->name || $oldTask->estStarted != $this->post->estStarted || $oldTask->deadline != $this->post->deadline, 'version', $oldTask->version + 1)

            ->setDefault('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->stripTags($this->config->task->editor->edit['id'], $this->config->allowedTags)
            ->cleanINT('execution,story,module')
            ->join('mailto', ',')
            ->remove('comment,files,labels,uid,multiple,team,teamEstimate,teamConsumed,teamLeft,contactListMenu')
            ->get();

        if($task->consumed < $oldTask->consumed) return print(js::error($this->lang->task->error->consumedSmall));

        /* Fix bug#1388, Check children task executionID and moduleID. */
        if(isset($task->execution) and $task->execution != $oldTask->execution)
        {
            $newExecution  = $this->loadModel('execution')->getByID($task->execution);
            $task->project = $newExecution->project;
            $this->dao->update(TABLE_TASK)->set('execution')->eq($task->execution)->set('module')->eq($task->module)->set('project')->eq($task->project)->where('parent')->eq($taskID)->exec();
        }

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->edit['id'], $this->post->uid);

        $teams = array();
        if($this->post->multiple and count(array_unique(array_filter($this->post->team))) > 1)
        {
            if(strpos(',done,closed,cancel,', ",{$task->status},") === false && $this->post->assignedTo && !in_array($this->post->assignedTo, $this->post->team))
            {
                dao::$errors[] = $this->lang->task->error->assignedTo;
                return false;
            }

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
                $member->left     = $this->post->teamLeft[$row] === '' ? 0 : $this->post->teamLeft[$row];
                $member->order    = $row;
                $teams[$account]  = $member;
                if($task->status == 'done') $member->left = 0;
            }
        }

        /* Save team. */
        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($taskID)->andWhere('type')->eq('task')->exec();
        if(!empty($teams))
        {
            foreach($teams as $member) $this->dao->insert(TABLE_TEAM)->data($member)->autoCheck()->exec();

            /* Assign the left hours to zero who will be skipped. */
            $skipMembers = $this->loadModel('execution')->getTeamSkip($oldTask->team, $oldTask->assignedTo, isset($task->assignedTo) ? $task->assignedTo : $oldTask->assignedTo);
            foreach($skipMembers as $account => $team) $this->dao->update(TABLE_TEAM)->set('left')->eq(0)->where('root')->eq($taskID)->andWhere('type')->eq('task')->andWhere('account')->eq($account)->exec();

            $task = $this->computeHours4Multiple($oldTask, $task, array(), $autoStatus = false);
            if($task->status == 'wait')
            {
                reset($teams);
                $task->assignedTo = key($teams);
            }
        }
        else
        {
            $task->mode = '';
        }

        $execution      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch();
        $requiredFields = "," . $this->config->task->edit->requiredFields . ",";
        if($execution->lifetime == 'ops' or $execution->attribute == 'request' or $execution->attribute == 'review')
        {
            $requiredFields = str_replace(",story,", ',', "$requiredFields");
            $task->story = 0;
        }

        if($task->status != 'cancel' and strpos($requiredFields, ',estimate,') !== false)
        {
            if(strlen(trim($task->estimate)) == 0) dao::$errors['estimate'] = sprintf($this->lang->error->notempty, $this->lang->task->estimate);
            $requiredFields = str_replace(',estimate,', ',', $requiredFields);
        }

        if(strpos(',doing,pause,', $task->status) && empty($task->left))
        {
            dao::$errors[] = sprintf($this->lang->task->error->leftEmptyAB, $this->lang->task->statusList[$task->status]);
            return false;
        }

        $requiredFields = trim($requiredFields, ',');

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheckIF($task->status != 'cancel', $requiredFields, 'notempty')
            ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)

            ->checkIF($task->estimate != false, 'estimate', 'float')
            ->checkIF($task->left     != false, 'left',     'float')
            ->checkIF($task->consumed != false, 'consumed', 'float')

            ->batchCheckIF($task->status == 'wait' or $task->status == 'doing', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')

            ->checkIF($task->status == 'done', 'consumed', 'notempty')
            ->checkIF($task->status == 'done' and $task->closedReason, 'closedReason', 'equal', 'done')
            ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')

            ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
            ->checkFlow()
            ->where('id')->eq((int)$taskID)->exec();

        if(!dao::isError())
        {
            /* Mark design version.*/
            if(isset($task->design) && !empty($task->design))
            {
                $design = $this->loadModel('design')->getByID($task->design);
                $this->dao->update(TABLE_TASK)->set('designVersion')->eq($design->version)->where('id')->eq($taskID)->exec();
            }

            /* Record task version. */
            if(isset($task->version) and $task->version > $oldTask->version)
            {
                $taskSpec = new stdClass();
                $taskSpec->task       = $taskID;
                $taskSpec->version    = $task->version;
                $taskSpec->name       = $task->name;
                $taskSpec->estStarted = $task->estStarted;
                $taskSpec->deadline   = $task->deadline;
                $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            }

            if($this->post->story != $oldTask->story)
            {
                $this->loadModel('story')->setStage($this->post->story);
                $this->story->setStage($oldTask->story);
            }
            if($task->status == 'done')   $this->loadModel('score')->create('task', 'finish', $taskID);
            if($task->status == 'closed') $this->loadModel('score')->create('task', 'close', $taskID);
            if($task->status != $oldTask->status) $this->loadModel('kanban')->updateLane($task->execution, 'task', $taskID);
            $this->loadModel('action');
            $changed = $task->parent != $oldTask->parent;
            if($oldTask->parent > 0)
            {
                $oldParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldTask->parent)->fetch();
                $this->updateParentStatus($taskID, $oldTask->parent, !$changed);
                $this->computeBeginAndEnd($oldTask->parent);

                if($changed)
                {
                    $oldChildCount = $this->dao->select('count(*) as count')->from(TABLE_TASK)->where('parent')->eq($oldTask->parent)->fetch('count');
                    if(!$oldChildCount) $this->dao->update(TABLE_TASK)->set('parent')->eq(0)->where('id')->eq($oldTask->parent)->exec();
                    $this->dao->update(TABLE_TASK)->set('lastEditedBy')->eq($this->app->user->account)->set('lastEditedDate')->eq(helper::now())->where('id')->eq($oldTask->parent)->exec();
                    $this->action->create('task', $taskID, 'unlinkParentTask', '', $oldTask->parent, '', false);

                    $actionID = $this->action->create('task', $oldTask->parent, 'unLinkChildrenTask', '', $taskID, '', false);

                    $newParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($oldTask->parent)->fetch();

                    $changes = common::createChanges($oldParentTask, $newParentTask);
                    if(!empty($changes)) $this->action->logHistory($actionID, $changes);
                }
            }

            if($task->parent > 0)
            {
                $parentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch();
                $this->dao->update(TABLE_TASK)->set('parent')->eq(-1)->where('id')->eq($task->parent)->exec();
                $this->updateParentStatus($taskID, $task->parent, !$changed);
                $this->computeBeginAndEnd($task->parent);

                if($changed)
                {
                    $this->dao->update(TABLE_TASK)->set('lastEditedBy')->eq($this->app->user->account)->set('lastEditedDate')->eq(helper::now())->where('id')->eq($task->parent)->exec();
                    $this->action->create('task', $taskID, 'linkParentTask', '', $task->parent, '', false);
                    $actionID = $this->action->create('task', $task->parent, 'linkChildTask', '', $taskID, '', false);
                    $newParentTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch();
                    $changes = common::createChanges($parentTask, $newParentTask);
                    if(!empty($changes)) $this->action->logHistory($actionID, $changes);
                }
            }
            $this->file->updateObjectID($this->post->uid, $taskID, 'task');

            unset($oldTask->parent);
            unset($task->parent);

            if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);

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
            if($data->pris[$taskID]        == 'ditto') $data->pris[$taskID]        = isset($prev['pri'])        ? $prev['pri']        : 0;
            if($data->finishedBys[$taskID] == 'ditto') $data->finishedBys[$taskID] = isset($prev['finishedBy']) ? $prev['finishedBy'] : '';
            if($data->canceledBys[$taskID] == 'ditto') $data->canceledBys[$taskID] = isset($prev['canceledBy']) ? $prev['canceledBy'] : '';
            if($data->closedBys[$taskID]   == 'ditto') $data->closedBys[$taskID]   = isset($prev['closedBy'])   ? $prev['closedBy']   : '';
            if($data->estStarteds[$taskID] == '0000-00-00') $data->estStarteds[$taskID] = '';
            if($data->deadlines[$taskID]   == '0000-00-00') $data->deadlines[$taskID]   = '';
            if(isset($data->assignedTos[$taskID]) and $data->assignedTos[$taskID] == 'ditto') $data->assignedTos[$taskID] = isset($prev['assignedTo']) ? $prev['assignedTo'] : '';

            $prev['module']     = $data->modules[$taskID];
            $prev['type']       = $data->types[$taskID];
            $prev['pri']        = $data->pris[$taskID];
            $prev['finishedBy'] = $data->finishedBys[$taskID];
            $prev['canceledBy'] = $data->canceledBys[$taskID];
            $prev['closedBy']   = $data->closedBys[$taskID];
            if(isset($data->assignedTos[$taskID])) $prev['assignedTo'] = $data->assignedTos[$taskID];
        }

        /* Initialize tasks from the post data.*/
        $extendFields = $this->getFlowExtendFields();
        $oldTasks     = $taskIDList ? $this->getByList($taskIDList) : array();
        $tasks        = array();
        foreach($taskIDList as $taskID)
        {
            $oldTask = $oldTasks[$taskID];

            $task = new stdclass();
            $task->id             = $taskID;
            $task->color          = $data->colors[$taskID];
            $task->name           = $data->names[$taskID];
            $task->module         = isset($data->modules[$taskID]) ? $data->modules[$taskID] : 0;
            $task->type           = $data->types[$taskID];
            $task->status         = isset($data->statuses[$taskID]) ? $data->statuses[$taskID] : $oldTask->status;
            $task->pri            = $data->pris[$taskID];
            $task->estimate       = isset($data->estimates[$taskID]) ? $data->estimates[$taskID] : $oldTask->estimate;
            $task->left           = isset($data->lefts[$taskID]) ? $data->lefts[$taskID] : $oldTask->left;
            $task->estStarted     = $data->estStarteds[$taskID];
            $task->deadline       = $data->deadlines[$taskID];
            $task->finishedBy     = $data->finishedBys[$taskID];
            $task->canceledBy     = $data->canceledBys[$taskID];
            $task->closedBy       = $data->closedBys[$taskID];
            $task->closedReason   = $data->closedReasons[$taskID];
            $task->finishedDate   = $oldTask->finishedBy == $task->finishedBy ? $oldTask->finishedDate : $now;
            $task->canceledDate   = $oldTask->canceledBy == $task->canceledBy ? $oldTask->canceledDate : $now;
            $task->closedDate     = $oldTask->closedBy == $task->closedBy ? $oldTask->closedDate : $now;
            $task->lastEditedBy   = $this->app->user->account;
            $task->lastEditedDate = $now;
            $task->consumed       = $oldTask->consumed;
            $task->parent         = $oldTask->parent;

            if(isset($data->assignedTos[$taskID])) $task->assignedTo = $data->assignedTos[$taskID];
            if($task->status == 'closed')          $task->assignedTo = 'closed';
            if(isset($task->assignedTo) and $oldTask->assignedTo != $task->assignedTo) $task->assignedDate = $now;

            if(strpos(',doing,pause,', $task->status) and empty($teams) and empty($task->left))
            {
                dao::$errors[] = sprintf($this->lang->task->error->leftEmptyAB, zget($this->lang->task->statusList, $task->status));
                return false;
            }

            if(empty($task->closedReason) and $task->status == 'closed')
            {
                if($oldTask->status == 'done')   $task->closedReason = 'done';
                if($oldTask->status == 'cancel') $task->closedReason = 'cancel';
            }

            if($oldTask->name != $task->name || $oldTask->estStarted != $task->estStarted || $oldTask->deadline != $task->deadline)
            {
                $task->version = $oldTask->version + 1;
            }

            foreach($extendFields as $extendField)
            {
                $task->{$extendField->field} = $this->post->{$extendField->field}[$taskID];
                if(is_array($task->{$extendField->field})) $task->{$extendField->field} = join(',', $task->{$extendField->field});

                $task->{$extendField->field} = htmlSpecialString($task->{$extendField->field});
            }

            if(isset($data->consumeds[$taskID]))
            {
                if($data->consumeds[$taskID] < 0)
                {
                    dao::$errors[] = sprintf($this->lang->task->error->consumed, $taskID);
                    return false;
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
                $task->finishedDate = $oldTask->status == 'done' ? $oldTask->finishedDate : $now;

                $task->canceledBy   = '';
                $task->canceledDate = '';
                break;
            case 'cancel':
                $task->assignedTo   = $oldTask->openedBy;
                $task->assignedDate = $now;

                if(!$task->canceledBy)
                {
                    $task->canceledBy   = $this->app->user->account;
                    $task->canceledDate = $now;
                }

                $task->finishedBy   = '';
                $task->finishedDate = '';
                break;
            case 'closed':
                if(!$task->closedBy)
                {
                    $task->closedBy   = $this->app->user->account;
                    $task->closedDate = $now;
                }
                if($task->closedReason == 'cancel' and helper::isZeroDate($task->finishedDate)) $task->finishedDate = '';
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

            $tasks[$taskID] = $task;
        }

        /* Check field not empty. */
        foreach($tasks as $taskID => $task)
        {
            if($task->status == 'cancel') continue;
            if($task->status == 'done' and $task->consumed == false)
            {
                dao::$errors[] = 'Task#' . $taskID . sprintf($this->lang->error->notempty, $this->lang->task->consumedThisTime);
                return false;
            }

            if(!empty($task->deadline) and $task->estStarted > $task->deadline)
            {
                dao::$errors[] = 'Task#' . $taskID . $this->lang->task->error->deadlineSmall;
                return false;
            }

            if($this->config->systemMode == 'new')
            {
                $project = $this->loadModel('project')->getByID($oldTask->project);
                if($project->model == 'waterfall') $this->config->task->edit->requiredFields .= ',estStarted,deadline';
            }
            foreach(explode(',', $this->config->task->edit->requiredFields) as $field)
            {
                $field = trim($field);
                if(empty($field)) continue;

                if(!isset($task->$field)) continue;
                if(!empty($task->$field)) continue;
                if($field == 'estimate' and strlen(trim($task->estimate)) != 0) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
                return false;
            }
        }

        $isBiz = $this->config->edition == 'biz';
        $isMax = $this->config->edition == 'max';
        foreach($tasks as $taskID => $task)
        {
            if(strpos(',doing,pause,', $task->status) && empty($teams) && $task->parent >= 0 && empty($task->left))
            {
                dao::$errors[] = sprintf($this->lang->task->error->leftEmpty, $taskID, $this->lang->task->statusList[$task->status]);
                return false;
            }

            $oldTask = $oldTasks[$taskID];
            $this->dao->update(TABLE_TASK)->data($task)
                ->autoCheck()

                ->checkIF($task->estimate != false, 'estimate', 'float')
                ->checkIF($task->consumed != false, 'consumed', 'float')
                ->checkIF($task->left     != false, 'left',     'float')

                ->batchCheckIF($task->status == 'wait' or $task->status == 'doing', 'finishedBy, finishedDate,canceledBy, canceledDate, closedBy, closedDate, closedReason', 'empty')

                ->checkIF($task->status == 'done', 'consumed', 'notempty')
                ->checkIF($task->status == 'done' and $task->closedReason, 'closedReason', 'equal', 'done')
                ->batchCheckIF($task->status == 'done', 'canceledBy, canceledDate', 'empty')

                ->batchCheckIF($task->closedReason == 'cancel', 'finishedBy, finishedDate', 'empty')
                ->checkFlow()
                ->where('id')->eq((int)$taskID)
                ->exec();
            if(dao::isError())
            {
                dao::$errors[] = 'Task#' . $taskID . dao::getError(true);
                return false;
            }

            if($task->status == 'done' and $task->closedReason) $this->dao->update(TABLE_TASK)->set('status')->eq('closed')->where('id')->eq($taskID)->exec();

            if($oldTask->story != false) $this->loadModel('story')->setStage($oldTask->story);
            if(!dao::isError())
            {
                /* Record version change history. */
                if($task->version > $oldTask->version)
                {
                    $taskSpec = new stdClass();
                    $taskSpec->task       = $taskID;
                    $taskSpec->version    = $task->version;
                    $taskSpec->name       = $task->name;
                    $taskSpec->estStarted = $task->estStarted;
                    $taskSpec->deadline   = $task->deadline;

                    $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
                }

                if($oldTask->parent > 0)
                {
                    $this->updateParentStatus($oldTask->id);
                    $this->computeBeginAndEnd($oldTask->parent);
                }

                if($task->status == 'done')   $this->loadModel('score')->create('task', 'finish', $taskID);
                if($task->status == 'closed') $this->loadModel('score')->create('task', 'close', $taskID);
                if($task->status != $oldTask->status) $this->loadModel('kanban')->updateLane($oldTask->execution, 'task', $oldTask->id);
                if(($isBiz || $isMax) && $oldTask->feedback && !isset($feedbacks[$oldTask->feedback]))
                {
                    $feedbacks[$oldTask->feedback] = $oldTask->feedback;
                    $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);
                }
                $allChanges[$taskID] = common::createChanges($oldTask, $task);
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
            ->add('id', $taskID)
            ->cleanFloat('left')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate', $now)
            ->stripTags($this->config->task->editor->assignto['id'], $this->config->allowedTags)
            ->remove('comment,showModule')
            ->get();
        if($oldTask->status != 'done' and $oldTask->status != 'closed' and isset($task->left) and $task->left == 0)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->task->left);
            return false;
        }

        if(!empty($oldTask->team))
        {
            $skipMembers = $this->loadModel('execution')->getTeamSkip($oldTask->team, $oldTask->assignedTo, $task->assignedTo);
            foreach($skipMembers as $account => $team) $this->dao->update(TABLE_TEAM)->set('left')->eq(0)->where('root')->eq($taskID)->andWhere('type')->eq('task')->andWhere('account')->eq($account)->exec();

            $this->dao->update(TABLE_TEAM)->set('left')->eq($task->left)
                ->where('root')->eq($taskID)
                ->andWhere('type')->eq('task')
                ->andWhere('account')->eq($task->assignedTo)
                ->exec();

            $task = $this->computeHours4Multiple($oldTask, $task);
        }

        if($oldTask->parent > 0) $this->updateParentStatus($taskID);

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->assignto['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)
            ->data($task)
            ->autoCheck()
            ->check('left', 'float')
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Update a task team.
     *
     * @param int $taskID
     * @access public
     * @return void
     */
    public function updateTeam($taskID)
    {
        $oldTask = $this->getById($taskID);
        if($this->post->estimate < 0 or $this->post->left < 0 or $this->post->consumed < 0)
        {
            dao::$errors[] = $this->lang->task->error->recordMinus;
            return false;
        }

        $now  = helper::now();
        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('estimate, left, consumed', 0)
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(is_numeric($this->post->left),     'left',     (float)$this->post->left)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('assignedDate', $now)
            ->stripTags($this->config->task->editor->assignto['id'], $this->config->allowedTags)
            ->remove('comment,showModule,team,teamEstimate,teamConsumed,teamLeft')
            ->get();

        if($task->consumed < $oldTask->consumed) return print(js::error($this->lang->task->error->consumedSmall));

        $teams = array();
        if(count(array_unique(array_filter($this->post->team))) > 1)
        {
            foreach($this->post->team as $row => $account)
            {
                if(empty($account) or isset($team[$account])) continue;

                $member = new stdClass();
                $member->account  = $account;
                $member->join     = helper::today();
                $member->root     = $taskID;
                $member->type     = 'task';
                $member->estimate = $this->post->teamEstimate[$row] ? $this->post->teamEstimate[$row] : 0;
                $member->consumed = $this->post->teamConsumed[$row] ? $this->post->teamConsumed[$row] : 0;
                $member->left     = $this->post->teamLeft[$row] === '' ? 0 : $this->post->teamLeft[$row];
                $member->order    = $row;
                $teams[$account]  = $member;
                if($oldTask->status == 'done') $member->left = 0;
            }
        }

        /* Save team. */
        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($taskID)->andWhere('type')->eq('task')->exec();
        if(!empty($teams))
        {
            foreach($teams as $member) $this->dao->insert(TABLE_TEAM)->data($member)->autoCheck()->exec();

            /* Assign the left hours to zero who will be skipped. */
            $skipMembers = $this->loadModel('execution')->getTeamSkip($oldTask->team, $oldTask->assignedTo, isset($task->assignedTo) ? $task->assignedTo : $oldTask->assignedTo);
            foreach($skipMembers as $account => $team) $this->dao->update(TABLE_TEAM)->set('left')->eq(0)->where('root')->eq($taskID)->andWhere('type')->eq('task')->andWhere('account')->eq($account)->exec();

            $task = $this->computeHours4Multiple($oldTask, $task, array(), $autoStatus = false);
            if($task->status == 'wait')
            {
                reset($teams);
                $task->assignedTo = key($teams);
            }
        }
        else
        {
            $task->mode = '';
        }

        if($oldTask->parent > 0) $this->updateParentStatus($taskID);

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->assignto['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)
            ->data($task)
            ->autoCheck()
            ->checkIF($task->estimate != false, 'estimate', 'float')
            ->checkIF($task->left     != false, 'left',     'float')
            ->checkIF($task->consumed != false, 'consumed', 'float')
            ->checkFlow()
            ->where('id')->eq($taskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Start a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function start($taskID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $oldTask = $this->getById($taskID);
        if($oldTask->status == 'doing') dao::$errors[] = $this->lang->task->error->alreadyStarted;
        if(!empty($oldTask->team))
        {
            if($this->post->consumed < $oldTask->team[$this->app->user->account]->consumed) dao::$errors['consumed'] = $this->lang->task->error->consumedSmall;
        }
        else
        {
            if($this->post->consumed < $oldTask->consumed) dao::$errors['consumed'] = $this->lang->task->error->consumedSmall;
        }
        if(dao::isError()) return false;

        $editorIdList = $this->config->task->editor->start['id'];
        if($this->app->getMethodName() == 'restart') $editorIdList = $this->config->task->editor->restart['id'];
        $now  = helper::now();
        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('status', 'doing')
            ->setIF($oldTask->assignedTo != $this->app->user->account, 'assignedDate', $now)
            ->stripTags($editorIdList, $this->config->allowedTags)
            ->removeIF(!empty($oldTask->team), 'consumed,left')
            ->remove('comment')->get();

        $task = $this->loadModel('file')->processImgURL($task, $editorIdList, $this->post->uid);
        if($this->post->left == 0)
        {
            if(isset($task->consumed) and $task->consumed == 0)
            {
                dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->task->consumed);
                return false;
            }
            $task->status       = 'done';
            $task->finishedBy   = $this->app->user->account;
            $task->finishedDate = helper::now();
            $task->assignedTo   = $oldTask->openedBy;
        }

        /* Record consumed and left. */
        $estimate = new stdclass();
        $estimate->date     = helper::isZeroDate($task->realStarted) ? helper::today() : substr($task->realStarted, 0, 10);
        $estimate->task     = $taskID;
        $estimate->consumed = zget($_POST, 'consumed', 0);
        $estimate->left     = zget($_POST, 'left', 0);
        $estimate->work     = zget($task, 'work', '');
        $estimate->account  = $this->app->user->account;
        $estimate->consumed = !empty($oldTask->team) ? $estimate->consumed - $oldTask->team[$this->app->user->account]->consumed : $estimate->consumed - $oldTask->consumed;
        if($this->post->comment) $estimate->work = $this->post->comment;
        $this->addTaskEstimate($estimate);

        if(!empty($oldTask->team))
        {
            $teams      = array_keys($oldTask->team);
            $assignedTo = empty($oldTask->assignedTo) ? $teams[0] : $oldTask->assignedTo;

            $data = new stdclass();
            $data->consumed = $this->post->consumed;
            $data->left     = $this->post->left;

            $this->dao->update(TABLE_TEAM)->data($data)
                ->where('root')->eq($taskID)
                ->andWhere('type')->eq('task')
                ->andWhere('account')->eq($assignedTo)
                ->exec();

            $task = $this->computeHours4Multiple($oldTask, $task);
        }

        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->check('consumed,left', 'float')
            ->checkFlow()
            ->where('id')->eq((int)$taskID)->exec();

        if($oldTask->parent > 0)
        {
            $this->updateParentStatus($taskID);
            $this->computeBeginAndEnd($oldTask->parent);
        }
        if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);

        $this->loadModel('kanban');
        if(!isset($output['toColID']) or $task->status == 'done') $this->kanban->updateLane($oldTask->execution, 'task', $taskID);
        if(isset($output['toColID']) and $task->status == 'doing') $this->kanban->moveCard($taskID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);
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
        $record = fixer::input('post')->get();
        $today  = helper::today();

        /* Fix bug#3036. */
        foreach($record->consumed as $id => $item) $record->consumed[$id] = trim($item);
        foreach($record->left     as $id => $item) $record->left[$id]     = trim($item);
        foreach($record->consumed as $id => $item) if(!is_numeric($item) and !empty($item)) dao::$errors[] = 'ID #' . $id . ' ' . $this->lang->task->error->totalNumber;
        foreach($record->left     as $id => $item) if(!is_numeric($item) and !empty($item)) dao::$errors[] = 'ID #' . $id . ' ' . $this->lang->task->error->leftNumber;
        foreach($record->dates    as $id => $item) if($item > $today) dao::$errors[] = 'ID #' . $id . ' ' . $this->lang->task->error->date;
        if(dao::isError()) return false;

        $estimates    = array();
        $task         = $this->getById($taskID);
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

            if(!empty($record->work[$id]) or !empty($record->consumed[$id]))
            {
                if(!$record->consumed[$id])   helper::end(js::alert($this->lang->task->error->consumedThisTime));
                if($record->left[$id] === '') helper::end(js::alert($this->lang->task->error->left));

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
            $actionID   = $this->action->create('task', $taskID, 'RecordEstimate', $work, (float)$estimate->consumed);

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
        if(helper::isZeroDate($task->realStarted)) $data->realStarted = $now;

        if($left == 0 and strpos('done,cancel,closed', $task->status) === false)
        {
            $data->status       = 'done';
            $data->assignedTo   = $task->openedBy;
            $data->assignedDate = $now;
            $data->finishedBy   = $this->app->user->account;
            $data->finishedDate = $now;

            $this->action->create('task', $taskID, 'Finished');
        }
        elseif($task->status == 'wait')
        {
            $data->status       = 'doing';
            $data->assignedTo   = $this->app->user->account;
            $data->assignedDate = $now;
            $data->realStarted  = $earliestTime;
        }
        elseif($left != 0 and strpos('done,cancel,closed,pause', $task->status) !== false)
        {
            $data->status       = 'doing';
            $data->assignedTo   = $this->app->user->account;
            $data->assignedDate = $now;
        }

        if(!empty($task->team))
        {
            $myConsumed = $task->team[$this->app->user->account]->consumed;

            $newTeamInfo = new stdClass();
            $newTeamInfo->consumed = $myConsumed + $consumed;
            $newTeamInfo->left     = $left;
            $this->dao->update(TABLE_TEAM)->data($newTeamInfo)
                ->where('root')->eq($taskID)
                ->andWhere('type')->eq('task')
                ->andWhere('account')->eq($this->app->user->account)
                ->exec();

            $data = $this->computeHours4Multiple($task, $data);
        }

        $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($taskID)->exec();

        $changes = common::createChanges($task, $data);
        if(!empty($actionID)) $this->action->logHistory($actionID, $changes);

        if($task->parent > 0) $this->updateParentStatus($task->id);
        if($task->story)  $this->loadModel('story')->setStage($task->story);
        if(isset($data->status) and $task->status != $data->status) $this->loadModel('kanban')->updateLane($task->execution, 'task', $taskID);
        if($task->status == 'done' and !dao::isError()) $this->loadModel('score')->create('task', 'finish', $taskID);

        return $changes;
    }

    /**
     * Finish a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function finish($taskID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $oldTask = $this->getById($taskID);
        $now     = helper::now();
        $today   = helper::today();

        if($extra != 'DEVOPS' and strpos($this->config->task->finish->requiredFields, 'comment') !== false and !$this->post->comment)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(!$this->post->realStarted and helper::isZeroDate($oldTask->realStarted), 'realStarted', $now)
            ->setDefault('left', 0)
            ->setDefault('assignedTo',   $oldTask->openedBy)
            ->setDefault('assignedDate', $now)
            ->setDefault('status', 'done')
            ->setDefault('finishedBy, lastEditedBy', $this->app->user->account)
            ->setDefault('finishedDate, lastEditedDate', $now)
            ->stripTags($this->config->task->editor->finish['id'], $this->config->allowedTags)
            ->removeIF(!empty($oldTask->team), 'finishedBy,status,left')
            ->remove('comment,files,labels,currentConsumed')
            ->get();

        $currentConsumed = trim($this->post->currentConsumed);
        if(!is_numeric($currentConsumed))
        {
            dao::$errors[] = $this->lang->task->error->consumedNumber;
            return false;
        }

        if(empty($currentConsumed) and $oldTask->consumed == '0')
        {
            dao::$errors[] = $this->lang->task->error->consumedEmpty;
            return false;
        }

        if(!$this->post->realStarted)
        {
            dao::$errors[] = $this->lang->task->error->realStartedEmpty;
            return false;
        }

        if(!$this->post->finishedDate)
        {
            dao::$errors[] = $this->lang->task->error->finishedDateEmpty;
            return false;
        }

        if($this->post->realStarted > $this->post->finishedDate)
        {
            dao::$errors[] = $this->lang->task->error->finishedDateSmall;
            return false;
        }

        /* Record consumed and left. */
        if(empty($oldTask->team))
        {
            $consumed = $task->consumed - $oldTask->consumed;
            if($consumed < 0)
            {
                dao::$errors[] = $this->lang->task->error->consumedSmall;
                return false;
            }
        }
        else
        {
            $consumed = $task->consumed - $oldTask->team[$this->app->user->account]->consumed;
            if($consumed < 0)
            {
                dao::$errors[] = $this->lang->task->error->consumedSmall;
                return false;
            }
        }

        $estimate = new stdclass();
        $estimate->date     = helper::isZeroDate($task->finishedDate) ? helper::today() : substr($task->finishedDate, 0, 10);
        $estimate->task     = $taskID;
        $estimate->left     = 0;
        $estimate->work     = zget($task, 'work', '');
        $estimate->account  = $this->app->user->account;
        $estimate->consumed = $consumed;
        if($this->post->comment) $estimate->work = $this->post->comment;
        if(!empty($oldTask->team))
        {
            foreach($oldTask->team as $teamAccount => $team)
            {
                if($teamAccount == $this->app->user->account) continue;
                $estimate->left += $team->left;
            }
        }
        if($estimate->consumed) $this->addTaskEstimate($estimate);

        if(!empty($oldTask->team))
        {
            $this->dao->update(TABLE_TEAM)->set('left')->eq(0)->set('consumed')->eq($task->consumed)
                ->where('root')->eq((int)$taskID)
                ->andWhere('type')->eq('task')
                ->andWhere('account')->eq($this->app->user->account)->exec();

            $skipMembers = $oldTask->mode == 'linear' ? $this->loadModel('execution')->getTeamSkip($oldTask->team, $oldTask->assignedTo, $task->assignedTo) : $this->getFinishedUsers($oldTask->id, array_keys($oldTask->team));
            foreach($skipMembers as $account => $team) $this->dao->update(TABLE_TEAM)->set('left')->eq(0)->where('root')->eq($taskID)->andWhere('type')->eq('task')->andWhere('account')->eq($account)->exec();

            $task = $this->computeHours4Multiple($oldTask, $task);
            if($oldTask->mode == 'multi' and count($skipMembers) == (count($oldTask->team) - 1) and !isset($skipMembers[$this->app->user->account]))
            {
                $task->status       = 'done';
                $task->finishedBy   = $this->app->user->account;
                $task->finishedDate = $task->finishedDate;
            }
        }

        if($task->finishedDate == substr($now, 0, 10)) $task->finishedDate = $now;

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->finish['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if(!dao::isError())
        {
            if($oldTask->parent > 0) $this->updateParentStatus($taskID);
            if($oldTask->story) $this->loadModel('story')->setStage($oldTask->story);
            if($task->status == 'done')
            {
                $this->loadModel('score')->create('task', 'finish', $taskID);

                $this->loadModel('kanban');
                if(!isset($output['toColID'])) $this->kanban->updateLane($oldTask->execution, 'task', $taskID);
                if(isset($output['toColID'])) $this->kanban->moveCard($taskID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
            }

            if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);

            return common::createChanges($oldTask, $task);
        }

        return false;
    }

    /**
     * Pause task
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return array
     */
    public function pause($taskID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $oldTask = $this->getById($taskID);

        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('status', 'pause')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->stripTags($this->config->task->editor->pause['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->pause['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq((int)$taskID)->exec();

        if($oldTask->parent > 0) $this->updateParentStatus($taskID);

        $this->loadModel('kanban');
        if(!isset($output['toColID'])) $this->kanban->updateLane($oldTask->execution, 'task', $taskID);
        if(isset($output['toColID'])) $this->kanban->moveCard($taskID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Close a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return array
     */
    public function close($taskID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $oldTask = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();

        $now  = helper::now();
        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('status', 'closed')
            ->setDefault('assignedTo', 'closed')
            ->setDefault('assignedDate', $now)
            ->setDefault('closedBy, lastEditedBy', $this->app->user->account)
            ->setDefault('closedDate, lastEditedDate', $now)
            ->stripTags($this->config->task->editor->close['id'], $this->config->allowedTags)
            ->setIF($oldTask->status == 'done',   'closedReason', 'done')
            ->setIF($oldTask->status == 'cancel', 'closedReason', 'cancel')
            ->remove('_recPerPage')
            ->remove('comment')
            ->get();

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq((int)$taskID)->exec();

        if(!dao::isError())
        {
            if($oldTask->parent > 0) $this->updateParentStatus($taskID);
            if($oldTask->story)  $this->loadModel('story')->setStage($oldTask->story);
            $this->loadModel('score')->create('task', 'close', $taskID);

            $this->loadModel('kanban');
            if(!isset($output['toColID'])) $this->kanban->updateLane($oldTask->execution, 'task', $taskID);
            if(isset($output['toColID'])) $this->kanban->moveCard($taskID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);

            if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);

            return common::createChanges($oldTask, $task);
        }
    }

    /**
     * Cancel a task.
     *
     * @param int    $taskID
     * @param string $extra
     *
     * @access public
     * @return array
     */
    public function cancel($taskID, $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $oldTask = $this->getById($taskID);

        $now  = helper::now();
        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setDefault('status', 'cancel')
            ->setDefault('assignedTo', $oldTask->openedBy)
            ->setDefault('assignedDate', $now)
            ->setDefault('finishedBy', '')
            ->setDefault('finishedDate', '0000-00-00')
            ->setDefault('canceledBy, lastEditedBy', $this->app->user->account)
            ->setDefault('canceledDate, lastEditedDate', $now)
            ->stripTags($this->config->task->editor->cancel['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->cancel['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()->where('id')->eq((int)$taskID)->exec();
        if($oldTask->fromBug) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($oldTask->fromBug)->exec();
        if($oldTask->parent > 0) $this->updateParentStatus($taskID);
        if($oldTask->parent == '-1')
        {
            $oldChildrenTasks = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($taskID)->fetchAll('id');
            unset($task->assignedTo);
            unset($task->id);
            $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('parent')->eq((int)$taskID)->exec();
            $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy')->where('parent')->eq((int)$taskID)->exec();
            if(!dao::isError() and count($oldChildrenTasks) > 0)
            {
                $this->loadModel('action');
                foreach($oldChildrenTasks as $oldChildrenTask)
                {
                    $actionID = $this->action->create('task', $oldChildrenTask->id, 'Canceled', $this->post->comment);
                    $this->action->logHistory($actionID, common::createChanges($oldChildrenTask, $task));
                }
            }
        }
        if($oldTask->story)  $this->loadModel('story')->setStage($oldTask->story);
        $this->loadModel('kanban');
        if(!isset($output['toColID'])) $this->kanban->updateLane($oldTask->execution, 'task', $taskID);
        if(isset($output['toColID'])) $this->kanban->moveCard($taskID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);

        if(!dao::isError()) return common::createChanges($oldTask, $task);
    }

    /**
     * Activate a task.
     *
     * @param int    $taskID
     * @param string $extra
     *
     * @access public
     * @return array
     */
    public function activate($taskID, $extra)
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(strpos($this->config->task->activate->requiredFields, 'comment') !== false and !$this->post->comment)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->comment);
            return false;
        }

        $oldTask = $this->getById($taskID);
        if($oldTask->parent == '-1') $this->config->task->activate->requiredFields = '';
        $task = fixer::input('post')
            ->add('id', $taskID)
            ->setIF(is_numeric($this->post->left), 'left', (float)$this->post->left)
            ->setDefault('left', 0)
            ->setDefault('status', 'doing')
            ->setDefault('finishedBy, canceledBy, closedBy, closedReason', '')
            ->setDefault('finishedDate, canceledDate, closedDate', '0000-00-00')
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('assignedDate', helper::now())
            ->setDefault('activatedDate', helper::now())
            ->stripTags($this->config->task->editor->activate['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        if(!is_numeric($task->left))
        {
            dao::$errors[] = $this->lang->task->error->leftNumber;
            return false;
        }

        if(!empty($oldTask->team))
        {
            $this->dao->update(TABLE_TEAM)->set('left')->eq($this->post->left)
                ->where('root')->eq($taskID)
                ->andWhere('type')->eq('task')
                ->andWhere('account')->eq($this->post->assignedTo)
                ->exec();

            $task = $this->computeHours4Multiple($oldTask, $task);
        }

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->activate['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)->data($task)
            ->autoCheck()
            ->batchCheck($this->config->task->activate->requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq((int)$taskID)
            ->exec();

        if($oldTask->parent > 0) $this->updateParentStatus($taskID);
        if($oldTask->parent == '-1')
        {
            $oldChildrenTasks = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($taskID)->fetchAll('id');
            unset($task->left);
            unset($task->id);
            $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->where('parent')->eq((int)$taskID)->exec();
            $this->computeWorkingHours($taskID);
            if(!dao::isError() and count($oldChildrenTasks) > 0)
            {
                $this->loadModel('action');
                foreach($oldChildrenTasks as $oldChildrenTask)
                {
                    $actionID = $this->action->create('task', $oldChildrenTask->id, 'Activated', $this->post->comment);
                    $this->action->logHistory($actionID, common::createChanges($oldChildrenTask, $task));
                }
            }
        }
        if($oldTask->story)  $this->loadModel('story')->setStage($oldTask->story);
        $this->loadModel('kanban');
        if(!isset($output['toColID'])) $this->kanban->updateLane($oldTask->execution, 'task', $taskID);
        if(isset($output['toColID'])) $this->kanban->moveCard($taskID, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);

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
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->fetch();
        if(!$task) return false;

        $children = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('deleted')->eq(0)->fetchAll('id');
        $task->children = $children;

        /* Check parent Task. */
        if($task->parent > 0) $task->parentName = $this->dao->findById($task->parent)->from(TABLE_TASK)->fetch('name');

        $task->team = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($taskID)->andWhere('type')->eq('task')->orderBy('order')->fetchAll('account');
        foreach($children as $child) $child->team = array();

        $task = $this->loadModel('file')->replaceImgURL($task, 'desc');
        if($setImgSize) $task->desc = $this->file->setImgSize($task->desc);

        if($task->assignedTo == 'closed') $task->assignedToRealName = 'Closed';
        foreach($task as $key => $value)
        {
            if((strpos($key, 'Date') !== false or strpos('estStarted|deadline', $key) !== false) and !(int)substr($value, 0, 4)) $task->$key = '';
        }
        $task->files = $this->loadModel('file')->getByObject('task', $taskID);

        /* Get related test cases. */
        if($task->story) $task->cases = $this->dao->select('id, title')->from(TABLE_CASE)->where('story')->eq($task->story)->andWhere('storyVersion')->eq($task->storyVersion)->andWhere('deleted')->eq('0')->fetchPairs();

        return $this->processTask($task);
    }

    /**
     * Get project id.
     *
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function getProjectID($executionID = 0)
    {
        return $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
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
     * Get tasks list of a execution.
     *
     * @param  int           $executionID
     * @param  array|string  $moduleIdList
     * @param  string        $status
     * @param  string        $orderBy
     * @param  object        $pager
     * @access public
     * @return array
     */
    public function getTasksByModule($executionID = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null)
    {
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.execution')->eq((int)$executionID)
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
     * Get tasks of a execution.
     *
     * @param int    $executionID
     * @param int    $productID
     * @param string $type
     * @param string $modules
     * @param string $orderBy
     * @param null   $pager
     *
     * @access public
     * @return array|void
     */
    public function getExecutionTasks($executionID, $productID = 0, $type = 'all', $modules = 0, $orderBy = 'status_asc, id_desc', $pager = null)
    {
        if(is_string($type)) $type = strtolower($type);
        $fields = 'DISTINCT t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName';
        $this->config->edition == 'max' && $fields .= ', t6.name as designName, t6.version as latestDesignVersion';

        $actionIDList = array();
        if($type == 'assignedbyme') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($this->app->user->account)->fetchPairs('objectID', 'objectID');

        $tasks  = $this->dao->select($fields)
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->leftJoin(TABLE_TEAM)->alias('t4')->on('t4.root = t1.id')
            ->leftJoin(TABLE_MODULE)->alias('t5')->on('t1.module = t5.id')
            ->beginIF($this->config->edition == 'max')->leftJoin(TABLE_DESIGN)->alias('t6')->on('t1.design= t6.id')->fi()
            ->where('t1.execution')->eq((int)$executionID)
            ->beginIF($type == 'myinvolved')
            ->andWhere("((t4.`account` = '{$this->app->user->account}' AND t4.`type` = 'task') OR t1.`assignedTo` = '{$this->app->user->account}' OR t1.`finishedby` = '{$this->app->user->account}')")
            ->fi()
            ->beginIF($productID)->andWhere("((t5.root=" . (int)$productID . " and t5.type='story') OR t2.product=" . (int)$productID . ")")->fi()
            ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere("(t1.assignedTo = '{$this->app->user->account}' or (t1.mode = 'multi' and t4.`account` = '{$this->app->user->account}') )")->fi()
            ->beginIF($type == 'finishedbyme')
            ->andWhere('t1.finishedby', 1)->eq($this->app->user->account)
            ->orWhere('t1.finishedList')->like("%,{$this->app->user->account},%")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,assignedbyme,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', ($productID or in_array($type, array('myinvolved', 'needconfirm', 'assignedtome'))) ? false : true);

        if(empty($tasks)) return array();

        $taskList = array_keys($tasks);
        $taskTeam = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in($taskList)->andWhere('type')->eq('task')->fetchGroup('root');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
        }

        $parents = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');

        if($this->config->vision == 'lite') $tasks = $this->appendLane($tasks);
        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                    unset($tasks[$task->id]);
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
        }

        return $this->processTasks($tasks);
    }

    /**
     * Get execution tasks pairs.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getExecutionTaskPairs($executionID, $status = 'all', $orderBy = 'finishedBy, id_desc')
    {
        $tasks = array('' => '');
        $stmt = $this->dao->select('t1.id,t1.name,t1.parent,t2.realname AS finishedByRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.finishedBy = t2.account')
            ->where('t1.execution')->eq((int)$executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->orderBy($orderBy)
            ->query();
        while($task = $stmt->fetch()) $tasks[$task->id] = ($task->parent > 0 ? "[{$this->lang->task->childrenAB}] " : '') . "$task->id:$task->finishedByRealName:$task->name";
        return $tasks;
    }

    /**
     * Get execution parent tasks pairs.
     *
     * @param  int    $executionID
     * @param  string $append
     * @access public
     * @return array
     */
    public function getParentTaskPairs($executionID, $append = '')
    {
        $tasks = $this->dao->select('id, name')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->le(0)
            ->andWhere('status')->notin('cancel,closed')
            ->andWhere('execution')->eq($executionID)
            ->beginIF($append)->orWhere('id')->in($append)->fi()
            ->fetchPairs();

        foreach($tasks as $id => $name)
        {
            $taskTeam = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($id)->andWhere('type')->eq('task')->fetch();
            if(!empty($taskTeam)) unset($tasks[$id]);
        }
        return array('' => '') + $tasks ;
    }

    /**
     * Get tasks of a user.
     *
     * @param  string $account
     * @param  string $type     the query type
     * @param  int    $limit
     * @param  object $pager
     * @param  string $orderBy
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getUserTasks($account, $type = 'assignedTo', $limit = 0, $pager = null, $orderBy = "id_desc", $projectID = 0)
    {
        if(!$this->loadModel('common')->checkField(TABLE_TASK, $type)) return array();
        $tasks = $this->dao->select('t1.*, t2.id as executionID, t2.name as executionName, t2.type as executionType, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on("t1.project = t4.id")
            ->leftJoin(TABLE_TEAM)->alias('t5')->on("t5.root = t1.id and t5.type = 'task' and t5.account = '{$account}'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($type == 'finishedBy')
            ->andWhere('t1.finishedby', 1)->eq($account)
            ->orWhere('t1.finishedList')->like("%,{$account},%")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'assignedTo' and ($this->app->rawModule == 'my' or $this->app->rawModule == 'block'))->andWhere('t2.status', true)->ne('suspended')->orWhere('t4.status')->ne('suspended')->markRight(1)->fi()
            ->beginIF($type != 'all' and $type != 'finishedBy' and $type != 'assignedTo')->andWhere("t1.`$type`")->eq($account)->fi()
            ->beginIF($type == 'assignedTo')->andWhere("(t1.assignedTo = '{$account}' or (t1.mode = 'multi' and t5.`account` = '{$account}') )")->fi()
            ->beginIF($type == 'assignedTo' and $this->app->rawModule == 'my' and $this->app->rawMethod == 'work')->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', false);

        $taskTeam = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in(array_keys($tasks))->andWhere('type')->eq('task')->fetchGroup('root');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
        }

        $projectList = array();
        foreach($tasks as $task) $projectList[$task->project] = $task->project;
        $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectList)->fetchPairs('id');
        foreach($tasks as $task) $task->projectName = zget($projectPairs, $task->project);

        if($tasks) return $this->processTasks($tasks);
        return array();
    }

    /**
     * Get tasks pairs of a user.
     *
     * @param  string    $account
     * @param  string    $status
     * @param  array     $skipExecutionIDList
     * @param  int|array $appendTaskID
     * @access public
     * @return array
     */
    public function getUserTaskPairs($account, $status = 'all', $skipExecutionIDList = array(), $appendTaskID = 0)
    {
        $deletedProjectIDList = $this->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq(1)->fetchPairs('id', 'id');

        $stmt = $this->dao->select('t1.id, t1.name, t2.name as execution')
            ->from(TABLE_TASK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF(!empty($skipExecutionIDList))->andWhere('t1.execution')->notin($skipExecutionIDList)->fi()
            ->beginIF(!empty($appendTaskID))->orWhere('t1.id')->in($appendTaskID)->fi()
            ->beginIF(!empty($deletedProjectIDList))->andWhere('t1.execution')->notin($deletedProjectIDList)->fi()
            ->query();

        $tasks = array();
        while($task = $stmt->fetch())
        {
            $tasks[$task->id] = $task->execution . ' / ' . $task->name;
        }
        return $tasks;
    }

    /**
     * Get suspended tasks of a user.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getUserSuspendedTasks($account)
    {
        $tasks = $this->dao->select('t1.*')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on("t1.project = t3.id")
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('(t2.status')->eq('suspended')
            ->orWhere('t3.status')->eq('suspended')
            ->markRight(1)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->systemMode == 'new')->andWhere('t3.deleted')->eq(0)->fi()
            ->fetchAll('id');
        return $tasks;
    }

    /**
     * Get task pairs of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getStoryTasks($storyID, $executionID = 0)
    {
        $tasks = $this->dao->select('id, parent, name, assignedTo, pri, status, estimate, consumed, closedReason, `left`')
            ->from(TABLE_TASK)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->fetchAll('id');

        $parents = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');

        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                    unset($tasks[$task->id]);
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
        }

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

             if(!empty($task->children))
            {
                foreach($task->children as $child)
                {
                    /* Compute child progress. */
                    if($child->consumed == 0 and $child->left == 0)
                    {
                        $child->progress = 0;
                    }
                    elseif($child->consumed != 0 and $child->left == 0)
                    {
                        $child->progress = 100;
                    }
                    else
                    {
                        $child->progress = round($child->consumed / ($child->consumed + $child->left), 2) * 100;
                    }
                }
            }
        }
        return $tasks;
    }

    /**
     * Get counts of some stories' tasks.
     *
     * @param  array  $stories
     * @param  int    $executionID
     * @access public
     * @return int
     */
    public function getStoryTaskCounts($stories, $executionID = 0)
    {
        if(empty($stories)) return array();
        $taskCounts = $this->dao->select('story, COUNT(*) AS tasks')
            ->from(TABLE_TASK)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
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
        $today       = helper::today();

        if($estimate->date > $today) return dao::$errors[] = $this->lang->task->error->date;

        $task = $this->getById($oldEstimate->task);
        $this->dao->update(TABLE_TASKESTIMATE)->data($estimate)
            ->autoCheck()
            ->check('consumed', 'notempty')
            ->where('id')->eq((int)$estimateID)
            ->exec();

        $consumed     = $task->consumed + $estimate->consumed - $oldEstimate->consumed;
        $lastEstimate = $this->dao->select('*')->from(TABLE_TASKESTIMATE)->where('task')->eq($task->id)->orderBy('id desc')->fetch();
        $left         = ($lastEstimate and $estimateID == $lastEstimate->id) ? $estimate->left : $task->left;

        $now  = helper::now();
        $data = new stdClass();
        $data->consumed       = $consumed;
        $data->left           = $left;
        $data->status         = $left == 0 ? 'done' : $task->status;
        $data->lastEditedBy   = $this->app->user->account;
        $data->lastEditedDate = $now;
        if(!$left)
        {
            $data->finishedBy   = $this->app->user->account;
            $data->finishedDate = $now;
            $data->assignedTo   = $task->openedBy;
        }

        if(!empty($task->team))
        {
            $oldConsumed = $task->team[$oldEstimate->account]->consumed;

            $newTeamInfo = new stdClass();
            $newTeamInfo->consumed = $oldConsumed + $estimate->consumed - $oldEstimate->consumed;
            $newTeamInfo->left     = $left;
            $this->dao->update(TABLE_TEAM)->data($newTeamInfo)
                ->where('root')->eq($oldEstimate->task)
                ->andWhere('type')->eq('task')
                ->andWhere('account')->eq($oldEstimate->account)
                ->exec();

            $data = $this->computeHours4Multiple($task, $data);
        }

        $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();
        if(!dao::isError())
        {
            if($task->parent > 0) $this->updateParentStatus($task->id);
            if($task->story)  $this->loadModel('story')->setStage($task->story);

            $oldTask = new stdClass();
            $oldTask->consumed = $task->consumed;
            $oldTask->left     = $task->left;
            $oldTask->status   = $task->status;

            $newTask = new stdClass();
            $newTask->consumed = $data->consumed;
            $newTask->left     = $data->left;
            $newTask->status   = $data->status;

            return common::createChanges($oldTask, $newTask);
        }
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
        $left         = isset($lastEstimate->left) ? $lastEstimate->left : $estimate->left;

        $data = new stdclass();
        $data->consumed = $consumed;
        $data->left     = $left;
        $data->status   = ($left == 0 && $consumed != 0) ? 'done' : $task->status;
        if($left == 0 and $consumed != 0)
        {
            $data->status = 'done';
        }
        elseif($consumed == 0)
        {
            $data->status = 'wait';
            $data->left   = $task->estimate;
        }
        else
        {
            $data->status = $task->status;
        }
        if(!empty($task->team))
        {
            $oldConsumed = $task->team[$estimate->account]->consumed;

            $newTeamInfo = new stdClass();
            $newTeamInfo->consumed = $oldConsumed - $estimate->consumed;
            $newTeamInfo->left     = $left;
            $this->dao->update(TABLE_TEAM)->data($newTeamInfo)
                ->where('root')->eq($estimate->task)
                ->andWhere('type')->eq('task')
                ->andWhere('account')->eq($estimate->account)
                ->exec();

            $data = $this->computeHours4Multiple($task, $data);
        }

        $this->dao->update(TABLE_TASK)->data($data) ->where('id')->eq($estimate->task)->exec();
        if($task->parent > 0) $this->updateParentStatus($task->id);
        if($task->story)  $this->loadModel('story')->setStage($task->story);

        $oldTask = new stdClass();
        $oldTask->consumed = $task->consumed;
        $oldTask->left     = $task->left;
        $oldTask->status   = $task->status;

        $newTask = new stdClass();
        $newTask->consumed = $data->consumed;
        $newTask->left     = $data->left;
        $newTask->status   = $data->status;

        if(!dao::isError()) return common::createChanges($oldTask, $newTask);
    }

    /**
     * Append lane field to tasks;
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function appendLane($tasks)
    {
        $executionIdList = array();
        foreach($tasks as $task)
        {
            $task->lane = '';
            if(!isset($executionIdList[$task->execution])) $executionIdList[$task->execution] = $task->execution;
        }

        $lanes = $this->dao->select('t1.kanban,t1.lane,t2.name,t1.cards')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANLANE)->alias('t2')->on('t1.lane = t2.id')
            ->where('t1.kanban')->in($executionIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('task')
            ->andWhere("t1.cards")->ne('')
            ->fetchAll();

        if(empty($lanes)) return $tasks;

        foreach($tasks as $task)
        {
            foreach($lanes as $lane)
            {
                if($lane->kanban != $task->execution) continue;
                if(strpos(",{$lane->cards},", ",{$task->id},") !== false)
                {
                    $task->lane = $lane->name;
                    break;
                }
            }
        }

        return $tasks;
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
                foreach($task->children as $child)
                {
                    $tasks[$task->id]->children[$child->id] = $this->processTask($child);
                }
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
            if(!empty($task->deadline) and !helper::isZeroDate($task->deadline))
            {
                $delay = helper::diffDate($today, $task->deadline);
                if($delay > 0) $task->delay = $delay;
            }
        }

        /* Story changed or not. */
        $task->needConfirm = false;
        if(!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion) $task->needConfirm = true;

        /* Set product type for task. */
        if(!empty($task->product))
        {
            $product = $this->loadModel('product')->getById($task->product);
            if($product) $task->productType = $product->type;
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
     * Get report data of tasks per execution
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerExecution()
    {
        $tasks = $this->dao->select('id,execution')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas = $this->processData4Report($tasks, '', 'execution');

        $executions = $this->loadModel('execution')->getPairs(0, 'all', 'all');
        foreach($datas as $executionID => $data)
        {
            $data->name  = isset($executions[$executionID]) ? $executions[$executionID] : $this->lang->report->undefined;
        }
        return $datas;
    }

    /**
     * Get report data of tasks per module
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerModule()
    {
        $tasks = $this->dao->select('id,module')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'module');

        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas), true, true);
        foreach($datas as $moduleID => $data)
        {
            $data->name = isset($modules[$moduleID]) ? $modules[$moduleID] : '/';
        }
        return $datas;
    }

    /**
     * Get report data of tasks per assignedTo
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerAssignedTo()
    {
        $tasks = $this->dao->select('id,assignedTo')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'assignedTo');

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
    public function getDataOfTasksPerType()
    {
        $tasks = $this->dao->select('id,type')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'type');

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
    public function getDataOfTasksPerPri()
    {
        $tasks = $this->dao->select('id,pri')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'pri');

        foreach($datas as $index => $pri) $pri->name = $this->lang->task->priList[$pri->name];
        return $datas;
    }

    /**
     * Get report data of tasks per deadline
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerDeadline()
    {
        $tasks = $this->dao->select('id,deadline')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->orderBy('deadline asc')
            ->fetchAll('id');
        if(!$tasks) return array();

        return $this->processData4Report($tasks, '', 'deadline');
    }

    /**
     * Get report data of tasks per estimate
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerEstimate()
    {
        $tasks = $this->dao->select('id,estimate')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $children = $this->dao->select('id,parent,estimate')->from(TABLE_TASK)->where('parent')->in(array_keys($tasks))->fetchAll('id');
        return $this->processData4Report($tasks, $children, 'estimate');
    }

    /**
     * Get report data of tasks per left
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerLeft()
    {
        $tasks = $this->dao->select('id,`left`')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $children = $this->dao->select('id,parent,`left`')->from(TABLE_TASK)->where('parent')->in(array_keys($tasks))->fetchAll('id');
        return $this->processData4Report($tasks, $children, 'left');
    }

    /**
     * Get report data of tasks per consumed
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerConsumed()
    {
        $tasks = $this->dao->select('id,consumed')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $children = $this->dao->select('id,parent,consumed')->from(TABLE_TASK)->where('parent')->in(array_keys($tasks))->fetchAll('id');
        return $this->processData4Report($tasks, $children, 'consumed');
    }

    /**
     * Get report data of tasks per finishedBy
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerFinishedBy()
    {
        $tasks = $this->dao->select('id,finishedBy')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->andWhere('finishedBy')->ne('')
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'finishedBy');

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
    public function getDataOfTasksPerClosedReason()
    {
        $tasks = $this->dao->select('id,closedReason')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->andWhere('closedReason')->ne('')
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'closedReason');

        foreach($datas as $closedReason => $data)
        {
            if(isset($this->lang->task->reasonList[$closedReason])) $data->name = $this->lang->task->reasonList[$closedReason];
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
        $tasks = $this->dao->select('id, DATE_FORMAT(finishedDate, "%Y-%m-%d") AS date')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->having('date != "0000-00-00"')
            ->orderBy('date asc')
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'date');
        return $datas;
    }

    /**
     * Get report data of status
     *
     * @access public
     * @return array
     */
    public function getDataOfTasksPerStatus()
    {
        $tasks = $this->dao->select('id,status')->from(TABLE_TASK)->alias('t1')
            ->where($this->reportCondition())
            ->fetchAll('id');
        if(!$tasks) return array();

        $datas    = $this->processData4Report($tasks, '', 'status');

        foreach($datas as $status => $data) $data->name = $this->lang->task->statusList[$status];
        return $datas;
    }

    /**
     * Process data for report.
     *
     * @param  array    $tasks
     * @param  array    $children
     * @param  string   $field
     * @access public
     * @return array
     */
    public function processData4Report($tasks, $children, $field)
    {
        if(is_array($children))
        {
            /* Remove the parent task from the tasks. */
            foreach($children as $childTaskID => $childTask) unset($tasks[$childTask->parent]);
        }

        $fields = array();
        $datas  = array();
        foreach($tasks as $taskID => $task)
        {
            if(!isset($fields[$task->$field])) $fields[$task->$field] = 0;
            $fields[$task->$field] ++;
        }
        if($field != 'date' and $field != 'deadline') asort($fields);
        foreach($fields as $field => $count)
        {
            $data = new stdclass();
            $data->name  = $field;
            $data->value = $count;
            $datas[$field] = $data;
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
    public static function isClickable($task, $action)
    {
        $action = strtolower($action);

        if($action == 'start'          and $task->parent < 0) return false;
        if($action == 'finish'         and $task->parent < 0) return false;
        if($action == 'pause'          and $task->parent < 0) return false;
        if($action == 'assignto'       and $task->parent < 0) return false;
        if($action == 'close'          and $task->parent < 0) return false;
        if($action == 'batchcreate'    and !empty($task->team))     return false;
        if($action == 'batchcreate'    and $task->parent > 0)       return false;
        if($action == 'recordestimate' and $task->parent == -1)     return false;
        if($action == 'delete'         and $task->parent < 0)       return false;

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
     * @param bool   $showBranch
     *
     * @access public
     * @return void
     */
    public function printCell($col, $task, $users, $browseType, $branchGroups, $modulePairs = array(), $mode = 'datatable', $child = false, $showBranch = false)
    {
        $canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
        $canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) and strtolower($browseType) != 'closed');
        $canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null);
        $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
        $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);

        $canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchCancel or $canBatchChangeModule or $canBatchAssignTo);
        $storyChanged   = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion and !in_array($task->status, array('cancel', 'closed')));

        $canView  = common::hasPriv('task', 'view');
        $taskLink = helper::createLink('task', 'view', "taskID=$task->id");
        $account  = $this->app->user->account;
        $id       = $col->id;
        if($col->show)
        {
            $class = "c-{$id}";
            if($id == 'status') $class .= ' task-' . $task->status;
            if($id == 'id')     $class .= ' cell-id';
            if($id == 'name')   $class .= ' text-left';
            if($id == 'deadline') $class .= ' text-center';
            if($id == 'deadline' and isset($task->delay)) $class .= ' delayed';
            if($id == 'assignedTo') $class .= ' has-btn text-left';
            if($id == 'lane') $class .= ' text-left';
            if(strpos('progress', $id) !== false) $class .= ' text-right';

            $title = '';
            if($id == 'name')
            {
                $title = " title='{$task->name}'";
                if(!empty($task->children)) $class .= ' has-child';
            }
            if($id == 'story') $title = " title='{$task->storyTitle}'";
            if($id == 'estimate' || $id == 'consumed' || $id == 'left')
            {
                $value = round($task->$id, 1);
                $title = " title='{$value} {$this->lang->execution->workHour}'";
            }
            if($id == 'lane') $title = " title='{$task->lane}'";
            if($id == 'finishedBy') $title  = " title='" . zget($users, $task->finishedBy) . "'";
            if($id == 'openedBy') $title  = " title='" . zget($users, $task->openedBy) . "'";
            if($id == 'lastEditedBy') $title  = " title='" . zget($users, $task->lastEditedBy) . "'";

            echo "<td class='" . $class . "'" . $title . ">";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('task', $task, $id);
            switch($id)
            {
            case 'id':
                if($canBatchAction)
                {
                    echo html::checkbox('taskIDList', array($task->id => '')) . html::a(helper::createLink('task', 'view', "taskID=$task->id"), sprintf('%03d', $task->id));
                }
                else
                {
                    printf('%03d', $task->id);
                }
                break;
            case 'pri':
                echo "<span class='label-pri label-pri-" . $task->pri . "' title='" . zget($this->lang->task->priList, $task->pri, $task->pri) . "'>";
                echo zget($this->lang->task->priList, $task->pri, $task->pri);
                echo "</span>";
                break;
            case 'name':
                if($showBranch) $showBranch = isset($this->config->execution->task->showBranch) ? $this->config->execution->task->showBranch : 1;
                if($task->parent > 0 and isset($task->parentName)) $task->name = "{$task->parentName} / {$task->name}";
                if(!empty($task->product) and isset($branchGroups[$task->product][$task->branch]) and $showBranch) echo "<span class='label label-badge label-outline'>" . $branchGroups[$task->product][$task->branch] . '</span> ';
                if($task->module and isset($modulePairs[$task->module])) echo "<span class='label label-gray label-badge'>" . $modulePairs[$task->module] . '</span> ';
                if($task->parent > 0) echo '<span class="label label-badge label-light" title="' . $this->lang->task->children . '">' . $this->lang->task->childrenAB . '</span> ';
                if(!empty($task->team)) echo '<span class="label label-badge label-light" title="' . $this->lang->task->multiple . '">' . $this->lang->task->multipleAB . '</span> ';
                echo $canView ? html::a($taskLink, $task->name, null, "style='color: $task->color' title='$task->name'") : "<span style='color: $task->color'>$task->name</span>";
                if(!empty($task->children)) echo '<a class="task-toggle" data-id="' . $task->id . '"><i class="icon icon-angle-double-right"></i></a>';
                if($task->fromBug) echo html::a(helper::createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '', "class='bug'");
                break;
            case 'type':
                echo $this->lang->task->typeList[$task->type];
                break;
            case 'status':
                $storyChanged ? print("<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>") : print("<span class='status-task status-{$task->status}' title='{$this->processStatus('task', $task)}'> " . $this->processStatus('task', $task) . "</span>");
                break;
            case 'estimate':
                echo round($task->estimate, 1) . $this->lang->execution->workHourUnit;
                break;
            case 'consumed':
                echo round($task->consumed, 1) . $this->lang->execution->workHourUnit;
                break;
            case 'left':
                echo round($task->left, 1)     . $this->lang->execution->workHourUnit;
                break;
            case 'progress':
                echo round($task->progress, 2) . '%';
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
                echo helper::isZeroDate($task->estStarted) ? '' : substr($task->estStarted, 5, 11);
                break;
            case 'realStarted':
                echo helper::isZeroDate($task->realStarted) ? '' : substr($task->realStarted, 5, 11);
                break;
            case 'assignedTo':
                $this->printAssignedHtml($task, $users);
                break;
            case 'lane':
                echo mb_substr($task->lane, 0, 8);
                break;
            case 'assignedDate':
                echo helper::isZeroDate($task->assignedDate) ? '' : substr($task->assignedDate, 5, 11);
                break;
            case 'finishedBy':
                echo zget($users, $task->finishedBy);
                break;
            case 'finishedDate':
                echo helper::isZeroDate($task->finishedDate) ? '' : substr($task->finishedDate, 5, 11);
                break;
            case 'canceledBy':
                echo zget($users, $task->canceledBy);
                break;
            case 'canceledDate':
                echo helper::isZeroDate($task->canceledDate) ? '' : substr($task->canceledDate, 5, 11);
                break;
            case 'closedBy':
                echo zget($users, $task->closedBy);
                break;
            case 'closedDate':
                echo helper::isZeroDate($task->closedDate) ? '' : substr($task->closedDate, 5, 11);
                break;
            case 'closedReason':
                echo $this->lang->task->reasonList[$task->closedReason];
                break;
            case 'story':
                if(!empty($task->storyID))
                {
                    if(common::hasPriv('story', 'view'))
                    {
                        echo html::a(helper::createLink('story', 'view', "storyid=$task->storyID", 'html', true), "<i class='icon icon-{$this->lang->icons['story']}'></i>", '', "class='iframe' data-width='1050' title='{$task->storyTitle}'");
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
                echo helper::isZeroDate($task->lastEditedDate) ? '' : substr($task->lastEditedDate, 5, 11);
                break;
            case 'activatedDate':
                echo helper::isZeroDate($task->activatedDate) ? '' : substr($task->activatedDate, 5, 11);
                break;
            case 'actions':
                echo $this->buildOperateMenu($task, 'browse');
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Print assigned html
     *
     * @param  object $task
     * @param  array  $users
     * @access public
     * @return void
     */
    public function printAssignedHtml($task, $users)
    {
        $btnTextClass   = '';
        $assignedToText = (!empty($task->team) and $task->mode == 'multi' and $task->status != 'closed') ? $this->lang->task->team : zget($users, $task->assignedTo);

        if(empty($task->assignedTo))
        {
            $btnTextClass   = 'text-primary';
            $assignedToText = $this->lang->task->noAssigned;
        }
        if($task->assignedTo == $this->app->user->account) $btnTextClass = 'text-red';

        $btnClass     = $task->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass     = "iframe btn btn-icon-left btn-sm {$btnClass}";
        $assignToLink = $task->assignedTo == 'closed' ? '#' : helper::createLink('task', 'assignTo', "executionID=$task->execution&taskID=$task->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $task->assignedTo) . "' class='{$btnTextClass}'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('task', 'assignTo', $task) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
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
        $users   = $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $members = array('');
        foreach($task->team as $member)
        {
            if(isset($users[$member->account])) $members[$member->account] = $users[$member->account];
        }
        return $members;
    }

    /**
     * Get the users who finished the multiple task.
     *
     * @param  int          $taskID
     * @param  string|array $team
     * @access public
     * @return array
     */
    public function getFinishedUsers($taskID = 0, $team = array())
    {
        $task = $this->getById($taskID);
        if($task->activatedDate == '') $task->activatedDate = "0000-00-00";
        return $this->dao->select('actor')->from(TABLE_ACTION)
            ->where('objectType')->eq('task')
            ->andWhere('objectID')->eq($taskID)
            ->andWhere('actor')->in($team)
            ->andWhere('action')->eq('finished')
            ->andWhere('date')->ge($task->activatedDate)
            ->fetchPairs('actor');
    }

    /**
     * Build nested list.
     *
     * @param  objecct $execution
     * @param  object  $task
     * @param  bool    $isChild
     * @param  bool    $showmore
     * @access public
     * @return string
     */
    public function buildNestedList($execution, $task, $isChild = false, $showmore = false, $users = array())
    {
        $showmore = $showmore ? 'showmore' : '';
        $trAttrs  = "data-id='t$task->id'";
        if(!$isChild)
        {
            $path     = $execution->grade == 2 ? "$execution->parent,$execution->id,t$task->id," : ",$execution->id,t$task->id,";
            $trAttrs .= " data-parent='$execution->id' data-nest-parent='$execution->id' data-nest-path='$path'";
            if(empty($task->children)) $trAttrs .= " data-nested='false'";
            $trClass  = empty($task->children) ? '' : " has-nest-child";
        }
        else
        {
            $path     = $execution->grade == 2 ? "$execution->parent,$execution->id,$task->parent,t$task->id," : ",$execution->id,$task->parent,t$task->id,";
            $trClass  = 'is-nest-child no-nest';
            $trAttrs .= " data-nested='false' data-parent='t$task->parent' data-nest-parent='t$task->parent' data-nest-path='$path'";
        }

        $list  = "<tr $trAttrs class='$trClass $showmore'>";
        $list .= '<td>';
        if($task->parent > 0) $list .= '<span class="label label-badge label-light" title="' . $this->lang->task->children . '">' . $this->lang->task->childrenAB . '</span> ';
        $list .= html::a(helper::createLink('task', 'view', "id=$task->id"), $task->name, '', "data-app='project'");
        $list .= '</td>';
        $list .= '<td>' . zget($users, $task->assignedTo, '') . '</td>';
        $list .= "<td class='status-{$task->status}'>" . $this->processStatus('task', $task) . '</td>';
        $list .= '<td></td>';
        $list .= '<td>' . $task->estStarted . '</td>';
        $list .= '<td>' . $task->deadline . '</td>';
        $list .= '<td>' . $task->estimate . $this->lang->execution->workHourUnit . '</td>';
        $list .= '<td>' . $task->consumed . $this->lang->execution->workHourUnit . '</td>';
        $list .= '<td>' . $task->left . $this->lang->execution->workHourUnit . '</td>';
        $list .= '<td></td>';
        $list .= '<td class="c-actions">';
        $list .= $this->buildOperateMenu($task, 'browse');
        $list .= '</td></tr>';

        if(!empty($task->children))
        {
            foreach($task->children as $child)
            {
                $showmore = (count($task->children) == 50) && ($child == end($task->children));
                $list .= $this->buildNestedList($execution, $child, true, $showmore, $users);
            }
        }

        return $list;
    }

    /**
     * Build task menu.
     *
     * @param  object $task
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($task, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($task);
    }

    /**
     * Build task view menu.
     *
     * @param  object $task
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($task)
    {
        if($task->deleted) return '';

        $menu   = '';
        $params = "taskID=$task->id";
        if((empty($task->team) || empty($task->children)) && $task->executionList->type != 'kanban')
        {
            $menu .= $this->buildMenu('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", $task, 'view', 'split', '', '', '', "title='{$this->lang->task->children}'", $this->lang->task->children);
        }

        $assignToLang = (!empty($task->team) and $task->mode == 'linear') ? $this->lang->task->transfer : $this->lang->task->assignTo;
        $menu .= $this->buildMenu('task', 'assignTo', "executionID=$task->execution&taskID=$task->id", $task, 'button', '', '', 'iframe', true, '', $assignToLang);

        $menu .= $this->buildMenu('task', 'start',          $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('task', 'restart',        $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('task', 'recordEstimate', $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('task', 'pause',          $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('task', 'finish',         $params, $task, 'view', '', '', 'iframe showinonlybody text-success', true);
        $menu .= $this->buildMenu('task', 'activate',       $params, $task, 'view', '', '', 'iframe showinonlybody text-success', true);
        $menu .= $this->buildMenu('task', 'close',          $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('task', 'cancel',         $params, $task, 'view', '', '', 'iframe showinonlybody', true);

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('task', $task, 'view', 'direct');
        $menu .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('task', 'edit', $params, $task, 'view', '', '', 'showinonlybody');
        $menu .= $this->buildMenu('task', 'create', "projctID={$task->execution}&storyID=0&moduleID=0&taskID=$task->id", $task, 'view', 'copy');
        $menu .= $this->buildMenu('task', 'delete', "executionID=$task->execution&taskID=$task->id", $task, 'view', 'trash', 'hiddenwin', 'showinonlybody', true);
        if($task->parent > 0) $menu .= $this->buildMenu('task', 'view', "taskID=$task->parent", $task, 'view', 'chevron-double-up', '', '', '', '', $this->lang->task->parent);

        return $menu;
    }

    /**
     * Build task browse action menu.
     *
     * @param  object $task
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($task)
    {
        $menu   = '';
        $params = "taskID=$task->id";

        $storyChanged = !empty($task->storyStatus) && $task->storyStatus == 'active' && $task->latestStoryVersion > $task->storyVersion && !in_array($task->status, array('cancel', 'closed'));
        if($storyChanged) return $this->buildMenu('task', 'confirmStoryChange', $params, $task, 'browse', '', 'hiddenwin');

        if($task->status != 'pause') $menu .= $this->buildMenu('task', 'start',   $params, $task, 'browse', '', '', 'iframe', true);
        if($task->status == 'pause') $menu .= $this->buildMenu('task', 'restart', $params, $task, 'browse', '', '', 'iframe', true);
        $menu .= $this->buildMenu('task', 'close',          $params, $task, 'browse', '', '', 'iframe', true);
        $menu .= $this->buildMenu('task', 'finish',         $params, $task, 'browse', '', '', 'iframe', true);
        $menu .= $this->buildMenu('task', 'recordEstimate', $params, $task, 'browse', 'time', '', 'iframe', true);
        $menu .= $this->buildMenu('task', 'edit',           $params, $task, 'browse');
        if($this->config->vision == 'rnd')
        {
            $menu .= $this->buildMenu('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=0", $task, 'browse', 'split', '', '', '', '', $this->lang->task->children);
        }

        return $menu;
    }
}
