<?php
/**
 * The model file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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

        if(!empty($this->config->limitTaskDate))
        {
            $this->checkEstStartedAndDeadline($executionID, $this->post->estStarted, $this->post->deadline);
            if(dao::isError()) return false;
        }

        $executionID    = (int)$executionID;
        $estStarted     = null;
        $deadline       = null;
        $assignedTo     = '';
        $taskIdList     = array();
        $taskDatas      = array();
        $taskFiles      = array();
        $requiredFields = "," . $this->config->task->create->requiredFields . ",";

        if($this->post->selectTestStory)
        {
            foreach($this->post->testStory as $i => $storyID)
            {
                if(empty($storyID)) continue;
            }

            /* Check required fields when create test task. */
            foreach($this->post->testStory as $i => $storyID)
            {
                if(empty($storyID)) continue;
                $estStarted = (!isset($this->post->testEstStarted[$i]) or (isset($this->post->estStartedDitto[$i]) and $this->post->estStartedDitto[$i] == 'on')) ? $estStarted : $this->post->testEstStarted[$i];
                $deadline   = (!isset($this->post->testDeadline[$i]) or (isset($this->post->deadlineDitto[$i]) and $this->post->deadlineDitto[$i] == 'on'))     ? $deadline : $this->post->testDeadline[$i];
                $assignedTo = (!isset($this->post->testAssignedTo[$i]) or $this->post->testAssignedTo[$i] == 'ditto') ? $assignedTo : $this->post->testAssignedTo[$i];

                if(!empty($this->config->limitTaskDate))
                {
                    $this->checkEstStartedAndDeadline($executionID, $estStarted, $deadline);
                    if(dao::isError())
                    {
                        foreach(dao::getError() as $field => $error)
                        {
                            dao::$errors[] = $error;
                            return false;
                        }
                    }
                }

                if($estStarted > $deadline)
                {
                    dao::$errors[] = "ID: $storyID {$this->lang->task->error->deadlineSmall}";
                    return false;
                }

                $task = new stdclass();
                $task->pri        = $this->post->testPri[$i];
                $task->estStarted = $estStarted;
                $task->deadline   = $deadline;
                $task->assignedTo = $assignedTo;
                $task->estimate   = $this->post->testEstimate[$i];
                $task->left       = $this->post->testEstimate[$i];

                /* Check requiredFields */
                $this->dao->insert(TABLE_TASK)->data($task)->batchCheck($requiredFields, 'notempty');
                if(dao::isError())
                {
                    foreach(dao::getError() as $field => $error)
                    {
                        dao::$errors[] = $error;
                        return false;
                    }
                }
                $taskDatas[$i] = $task;
            }

            $requiredFields = str_replace(",estimate,", ',', "$requiredFields");
            $requiredFields = str_replace(",story,", ',', "$requiredFields");
            $requiredFields = str_replace(",estStarted,", ',', "$requiredFields");
            $requiredFields = str_replace(",deadline,", ',', "$requiredFields");
            $requiredFields = str_replace(",module,", ',', "$requiredFields");
        }

        $this->loadModel('file');
        $task = fixer::input('post')
            ->setDefault('execution', $executionID)
            ->setDefault('estimate,left,story', 0)
            ->setDefault('status', 'wait')
            ->setDefault('project', $this->getProjectID($executionID))
            ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setIF(strpos($requiredFields, 'estStarted') !== false, 'estStarted', helper::isZeroDate($this->post->estStarted) ? '' : $this->post->estStarted)
            ->setIF(strpos($requiredFields, 'deadline') !== false, 'deadline', helper::isZeroDate($this->post->deadline) ? '' : $this->post->deadline)
            ->setIF(strpos($requiredFields, 'estimate') !== false, 'estimate', $this->post->estimate)
            ->setIF(strpos($requiredFields, 'left') !== false, 'left', $this->post->left)
            ->setIF(strpos($requiredFields, 'story') !== false, 'story', $this->post->story)
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(is_numeric($this->post->left),     'left',     (float)$this->post->left)
            ->setIF(!$this->post->estStarted, 'estStarted', null)
            ->setIF(!$this->post->deadline, 'deadline', null)
            ->setDefault('openedBy',   $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('vision', $this->config->vision)
            ->cleanINT('execution,story,module')
            ->stripTags($this->config->task->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('after,files,labels,assignedTo,uid,storyEstimate,storyDesc,storyPri,team,teamSource,teamEstimate,teamConsumed,teamLeft,teamMember,multiple,teams,contactListMenu,selectTestStory,testStory,testPri,testEstStarted,testDeadline,testAssignedTo,testEstimate,sync,otherLane,region,lane,estStartedDitto,deadlineDitto')
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

            if(strpos($requiredFields, ',estStarted,') !== false and !isset($task->estStarted)) dao::$errors['estStarted'] = sprintf($this->lang->error->notempty, $this->lang->task->estStarted);
            if(strpos($requiredFields, ',deadline,') !== false and !isset($task->deadline)) dao::$errors['deadline'] = sprintf($this->lang->error->notempty, $this->lang->task->deadline);
            if(isset($task->estStarted) and isset($task->deadline) and !helper::isZeroDate($task->deadline) and $task->deadline < $task->estStarted) dao::$errors['deadline'] = sprintf($this->lang->error->ge, $this->lang->task->deadline, $task->estStarted);

            if(dao::isError()) return false;

            $requiredFields = trim($requiredFields, ',');

            /* Fix Bug #2466 */
            if($this->post->multiple)  $task->assignedTo = '';
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
                $this->dao->update(TABLE_BUG)->set('toTask')->eq($taskID)->where('id')->eq($bugID)->exec();
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

            if($task->estStarted) $taskSpec->estStarted = $task->estStarted;
            if($task->deadline)   $taskSpec->deadline   = $task->deadline;

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
                    $testStories = $this->dao->select('id,title,version,module')->from(TABLE_STORY)->where('id')->in($testStoryIdList)->fetchAll('id');
                    foreach($this->post->testStory as $i => $storyID)
                    {
                        if(!isset($testStories[$storyID])) continue;

                        $assignedTo     = $taskDatas[$i]->assignedTo;
                        $testEstStarted = $taskDatas[$i]->estStarted;
                        $testDeadline   = $taskDatas[$i]->deadline;

                        $task->parent       = $taskID;
                        $task->story        = $storyID;
                        $task->storyVersion = $testStories[$storyID]->version;
                        $task->name         = $this->lang->task->lblTestStory . " #{$storyID} " . $testStories[$storyID]->title;
                        $task->pri          = $this->post->testPri[$i];
                        $task->estStarted   = $testEstStarted;
                        $task->deadline     = $testDeadline;
                        $task->assignedTo   = $assignedTo;
                        $task->estimate     = $this->post->testEstimate[$i];
                        $task->left         = $this->post->testEstimate[$i];
                        $task->module       = $testStories[$storyID]->module;
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

            if($this->post->multiple and count(array_filter($this->post->team)) > 1)
            {
                $teams = $this->manageTaskTeam($task->mode, $taskID, 'wait');
                if($teams)
                {
                    $task->id = $taskID;
                    $this->computeHours4Multiple($task);
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
        $estStarted = null;
        $deadline   = null;

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

            if(empty($tasks->name[$i]))
            {
                if($this->common->checkValidRow('task', $tasks, $i))
                {
                    dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->name);
                    return false;
                }
                continue;
            }

            $data[$i]             = new stdclass();
            $data[$i]->story      = (int)$story;
            $data[$i]->type       = $type;
            $data[$i]->module     = (int)$module;
            $data[$i]->assignedTo = $assignedTo;
            $data[$i]->color      = $tasks->color[$i];
            $data[$i]->name       = $tasks->name[$i];
            $data[$i]->desc       = nl2br($tasks->desc[$i]);
            $data[$i]->pri        = $tasks->pri[$i];
            $data[$i]->estimate   = $tasks->estimate[$i] ? $tasks->estimate[$i] : 0;
            $data[$i]->left       = $tasks->estimate[$i] ? $tasks->estimate[$i] : 0;
            $data[$i]->project    = $projectID;
            $data[$i]->execution  = $executionID;
            $data[$i]->estStarted = $estStarted;
            $data[$i]->deadline   = $deadline;
            $data[$i]->status     = 'wait';
            $data[$i]->openedBy   = $this->app->user->account;
            $data[$i]->openedDate = $now;
            $data[$i]->parent     = $tasks->parent[$i];
            $data[$i]->vision     = isset($tasks->vision[$i]) ? $tasks->vision[$i] : $this->config->vision;
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
            if(!empty($this->config->limitTaskDate))
            {
                $this->checkEstStartedAndDeadline($executionID, $task->estStarted, $task->deadline);
                if(dao::isError()) return false;
            }

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
            if($task->estStarted) $taskSpec->estStarted = $task->estStarted;
            if($task->deadline)   $taskSpec->deadline   = $task->deadline;

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
                $clonedTask->parent = $parentID;
                unset($clonedTask->id);
                $this->dao->insert(TABLE_TASK)->data($clonedTask)->autoCheck()->exec();

                $clonedTaskID = $this->dao->lastInsertID();

                $this->dao->update(TABLE_EFFORT)->set('objectID')->eq($clonedTaskID)
                    ->where('objectID')->eq($oldParentTask->id)
                    ->andWhere('objectType')->eq('task')
                    ->exec();
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
        if(empty($tasks))
        {
            $this->dao->update(TABLE_TASK)->set('consumed')->eq(0)->where('id')->eq($taskID)->exec();
            return true;
        }

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

        $earliestEstStarted  = '';
        $earliestRealStarted = '';
        $latestDeadline      = '';
        foreach($tasks as $task)
        {
            if(!helper::isZeroDate($task->estStarted)  and (empty($earliestEstStarted)   or $earliestEstStarted  > $task->estStarted))  $earliestEstStarted  = $task->estStarted;
            if(!helper::isZeroDate($task->realStarted) and (empty($earliestRealStarted)  or $earliestRealStarted > $task->realStarted)) $earliestRealStarted = $task->realStarted;
            if(!helper::isZeroDate($task->deadline)    and (empty($latestDeadline)       or $latestDeadline      < $task->deadline))    $latestDeadline      = $task->deadline;
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

                if(($this->config->edition != 'open') && $oldParentTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldParentTask->feedback, $newParentTask->status, $oldParentTask->status);
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
     * @param  array   $team
     * @param  bool    $autoStatus
     * @access public
     * @return object|bool
     */
    public function computeHours4Multiple($oldTask, $task = null, $team = array(), $autoStatus = true)
    {
        if(!$oldTask) return false;

        if(empty($team)) $team = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($oldTask->id)->orderBy('order')->fetchAll();
        if(!empty($team))
        {
            $now         = helper::now();
            $oldTeam     = zget($oldTask, 'team', array());
            $members     = array_map(function($member){ return $member->account; }, $team);
            $currentTask = !empty($task) ? $task : new stdclass();
            if(!isset($currentTask->status)) $currentTask->status = $oldTask->status;
            $oldTask->team = $team;

            $currentTask->assignedTo = $oldTask->assignedTo;
            if(!empty($_POST['assignedTo']) and is_string($_POST['assignedTo']))
            {
                $currentTask->assignedTo = $this->post->assignedTo;
            }
            else
            {
                $currentTask->assignedTo = $this->getAssignedTo4Multi($members, $oldTask);
                if($oldTask->assignedTo != $currentTask->assignedTo) $currentTask->assignedDate = $now;
            }

            $currentTask->estimate = 0;
            $currentTask->left     = 0;
            foreach($team as $member)
            {
                $currentTask->estimate += (float)$member->estimate;
                $currentTask->left     += (float)$member->left;
            }

            $efforts = $this->getTaskEstimate($oldTask->id);
            $currentTask->consumed = 0;
            foreach($efforts as $effort) $currentTask->consumed += (float)$effort->consumed;

            $oldTask->team = $oldTeam;

            if(!empty($task))
            {
                if(!$autoStatus) return $currentTask;

                if($currentTask->consumed == 0 and empty($efforts))
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
                    $finisedUsers = $this->getFinishedUsers($oldTask->id, $members);
                    if(count($finisedUsers) != count($team))
                    {
                        if(strpos('cancel,pause', $oldTask->status) === false or ($oldTask->status == 'closed' and $oldTask->reason == 'done'))
                        {
                            $currentTask->status       = 'doing';
                            $currentTask->finishedBy   = '';
                            $currentTask->finishedDate = '';
                        }
                    }
                    elseif(strpos('wait,doing,pause', $oldTask->status) !== false)
                    {
                        $currentTask->status       = 'done';
                        $currentTask->assignedTo   = $oldTask->openedBy;
                        $currentTask->assignedDate = $now;
                        $currentTask->finishedBy   = $this->app->user->account;
                        $currentTask->finishedDate = $task->finishedDate;
                    }
                }

                return $currentTask;
            }
            $this->dao->update(TABLE_TASK)->data($currentTask)->autoCheck()->where('id')->eq($oldTask->id)->exec();
        }
    }

    /**
     * Manage multi task team members.
     *
     * @param  string  $mode
     * @param  int     $taskID
     * @param  string  $taskStatus
     * @access public
     * @return array
     */
    public function manageTaskTeam($mode, $taskID, $taskStatus)
    {
        $oldTeams   = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->fetchAll();
        $oldMembers = array_map(function($team){return $team->account;}, $oldTeams);

        $this->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->exec();

        if($taskStatus == 'doing')
        {
            $efforts    = $this->getTaskEstimate($taskID);
            $doingUsers = array();
            foreach($efforts as $i => $effort)
            {
                if($effort->left != 0) $doingUsers[$effort->account] = $effort->account;
                if($effort->left == 0) unset($doingUsers[$effort->account]);
            }
        }

        $teams       = array();
        $minStatus   = 'done';
        $changeUsers = array();
        foreach($this->post->team as $row => $account)
        {
            if(empty($account)) continue;

            $teamSource = $this->post->teamSource[$row];
            $member = new stdClass();
            $member->task     = $taskID;
            $member->order    = $row;
            $member->account  = $account;
            $member->estimate = zget($this->post->teamEstimate, $row, 0);
            $member->consumed = $this->post->teamConsumed ? zget($this->post->teamConsumed, $row, 0) : 0;
            $member->left     = $this->post->teamLeft ? zget($this->post->teamLeft, $row, 0) : 0;
            $member->status   = 'wait';
            if($taskStatus == 'wait' and $member->estimate > 0 and $member->left == 0) $member->left = $member->estimate;
            if($taskStatus == 'done') $member->left = 0;

            if($member->left == 0 and $member->consumed > 0)
            {
                $member->status = 'done';
            }
            elseif($taskStatus == 'doing')
            {
                if(!empty($teamSource) and $teamSource != $account and isset($doingUsers[$teamSource])) $member->transfer = $teamSource;
                if(isset($doingUsers[$account]) and ($mode == 'multi' or ($mode == 'linear' and $minStatus != 'wait'))) $member->status = 'doing';
            }
            if($minStatus != 'wait' and $member->status == 'doing') $minStatus = 'doing';
            if($member->status == 'wait') $minStatus = 'wait';

            /* Doing status is only one in linear task. */
            if($mode == 'linear' and $member->status == 'doing') $minStatus = 'wait';
            if($member->status == 'wait') $minStatus = 'wait';
            if($minStatus != 'wait' and $member->status == 'doing') $minStatus = 'doing';

            /* Insert or update team. */
            if($mode == 'multi' and isset($teams[$account]))
            {
                $this->dao->update(TABLE_TASKTEAM)
                    ->beginIF($member->estimate)->set("estimate= estimate + {$member->estimate}")->fi()
                    ->beginIF($member->left)->set("`left` = `left` + {$member->left}")->fi()
                    ->beginIF($member->consumed)->set("`consumed` = `consumed` + {$member->consumed}")->fi()
                    ->where('task')->eq($member->task)
                    ->andWhere('account')->eq($member->account)
                    ->exec();
            }
            else
            {
                $this->dao->insert(TABLE_TASKTEAM)->data($member)->autoCheck()->exec();
            }

            /* Set effort left = 0 when linear task members be changed. */
            if($mode == 'linear' and isset($oldTeams[$row]) and $oldTeams[$row]->account != $account) $changeUsers[] = $oldTeams[$row]->account;

            $teams[$account] = $account;
        }

        /* Set effort left = 0 when multi task members be removed. */
        if($mode == 'multi' and $oldMembers)
        {
            $removedMembers = array_diff($oldMembers, $teams);
            $changeUsers    = array_merge($changeUsers, $removedMembers);
        }
        if($changeUsers) $this->resetEffortLeft($taskID, $changeUsers);

        return $teams;
    }

    /**
     * Get team by account from task teams.
     *
     * @param  array  $teams
     * @param  string $account
     * @param  array  $extra
     * @access public
     * @return object
     */
    public function getTeamByAccount($teams, $account = '', $extra = array('filter' => 'done'))
    {
        if(empty($account)) $account = $this->app->user->account;

        $filter   = zget($extra, 'filter', '');
        $effortID = zget($extra, 'effortID', '');

        $duplicates = array();
        $members    = array();
        $taskID     = 0;
        foreach($teams as $team)
        {
            if(isset($extra['order']) and $team->order == $extra['order'] and $team->account == $account) return $team;

            if(empty($taskID)) $taskID = $team->task;
            if(isset($members[$team->account]))  $duplicates[$team->account] = $team->account;
            if(!isset($members[$team->account])) $members[$team->account]    = 0;
            $members[$team->account] += 1;
        }

        /*
         * 1. No duplicate account;
         * 2. Account is not duplicate account;
         * 3. Not by effort;
         * Then direct get team by account.
         */
        if(empty($duplicates) or (!isset($duplicates[$account])))
        {
            foreach($teams as $team)
            {
                if($team->account == $account) return $team;
            }
        }
        elseif(empty($effortID))
        {
            foreach($teams as $team)
            {
                if($filter and $team->status == $filter) continue;
                if($team->account == $account) return $team;
            }
        }
        elseif($effortID)
        {
            $efforts = $this->getTaskEstimate($taskID, '', $effortID);

            $prevTeam = null;
            $thisTeam = null;
            foreach($efforts as $effort)
            {
                $thisTeam = reset($teams);
                if($effort->id == $effortID)
                {
                    if($effort->account == $thisTeam->account) return $thisTeam;
                    if($effort->account == $prevTeam->account) return $prevTeam;
                    return false;
                }

                if($effort->left == 0 and $thisTeam->account == $effort->account) $prevTeam = array_shift($teams);
            }
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

        if(!empty($this->config->limitTaskDate))
        {
            $this->checkEstStartedAndDeadline($oldTask->execution, $this->post->estStarted, $this->post->deadline);
            if(dao::isError()) return false;
        }

        if(!empty($_POST['lastEditedDate']) and $oldTask->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        /* If a multiple task is assigned to a team member who is not the task, assign to the team member instead. */
        if(!$this->post->assignedTo and !empty($oldTask->team) and !empty($_POST['team'])) $_POST['assignedTo'] = $this->getAssignedTo4Multi($_POST['team'], $oldTask);
        if(!$oldTask->mode and !$this->post->assignedTo and !empty($_POST['team'])) $_POST['assignedTo'] = $_POST['team'][0];

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
            ->setDefault('deleteFiles', array())
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

            ->setIF($this->post->mode   == 'single', 'mode', '')
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
            ->remove('comment,files,labels,uid,multiple,team,teamEstimate,teamConsumed,teamLeft,teamSource,contactListMenu')
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

        if($this->post->team and count(array_filter($this->post->team)) > 1)
        {
            $teams = $this->manageTaskTeam($oldTask->mode, $taskID, $task->status);
            if(!empty($teams)) $task = $this->computeHours4Multiple($oldTask, $task, array(), $autoStatus = false);
        }
        if(empty($teams)) $task->mode = '';

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

        $this->dao->update(TABLE_TASK)->data($task, 'deleteFiles')
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

            if($_POST['mode'] == 'single')
            {
                $this->dao->delete()->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->exec();
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

            unset($oldTask->parent);
            unset($task->parent);

            if($this->config->edition != 'open' && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);
            if(isset($oldTask->team))
            {
                $users = $this->loadModel('user')->getPairs('noletter|noempty');
                $oldTeams = $oldTask->team;
                $oldTask->team = '';
                foreach($oldTeams as $team) $oldTask->team .= "{$this->lang->task->teamMember}: " . zget($users, $team->account) . ", {$this->lang->task->estimateAB}: " . (float)$team->estimate . ", {$this->lang->task->consumedAB}: " . (float)$team->consumed . ", {$this->lang->task->leftAB}: " . (float)$team->left . "\n";
                $task->team = '';
                foreach($this->post->team as $i => $account)
                {
                    if(empty($account)) continue;
                    $task->team .= "{$this->lang->task->teamMember}: " . zget($users, $account) . ", {$this->lang->task->estimateAB}: " . zget($this->post->teamEstimate, $i, 0) . ", {$this->lang->task->consumedAB}: " . zget($this->post->teamConsumed, $i, 0) . ", {$this->lang->task->leftAB}: " . zget($this->post->teamLeft, $i, 0) . "\n";
                }
            }

            $this->file->processFile4Object('task', $oldTask, $task);
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

            if(strpos(',doing,pause,', $task->status) and empty($teams) and empty($task->left) and $task->parent >= 0)
            {
                dao::$errors[] = sprintf($this->lang->task->error->leftEmptyAB, zget($this->lang->task->statusList, $task->status));
                return false;
            }

            if(!empty($this->config->limitTaskDate))
            {
                $this->checkEstStartedAndDeadline($oldTask->execution, $task->estStarted, $task->deadline, "task:{$taskID} ");
                if(dao::isError()) return false;
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

            if(!empty($data->consumeds[$taskID]))
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
                if($this->config->edition != 'open' && $oldTask->feedback && !isset($feedbacks[$oldTask->feedback]))
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
        foreach($this->post->team as $i => $account)
        {
            if(!$account) continue;

            if($this->post->teamConsumed[$i] == 0 and $this->post->teamLeft[$i] == 0)
            {
                dao::$errors[] = $this->lang->task->noticeTaskStart;
                return false;
            }
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
            ->remove('comment,showModule,team,teamEstimate,teamConsumed,teamLeft,teamSource')
            ->get();

        if(count(array_filter($this->post->team)) > 1)
        {
            $teams = $this->manageTaskTeam($oldTask->mode, $taskID, $task->status);
            if(!empty($teams)) $task = $this->computeHours4Multiple($oldTask, $task);
        }
        if(empty($teams)) $task->mode = '';

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
        if(!empty($oldTask->team))
        {
            $currentTeam = $this->getTeamByAccount($oldTask->team);
            if($currentTeam and $this->post->consumed < $currentTeam->consumed) dao::$errors['consumed'] = $this->lang->task->error->consumedSmall;
            if($currentTeam and $currentTeam->status == 'doing' and $oldTask->status == 'doing') dao::$errors[] = $this->lang->task->error->alreadyStarted;
        }
        else
        {
            if($this->post->consumed < $oldTask->consumed) dao::$errors['consumed'] = $this->lang->task->error->consumedSmall;
            if($oldTask->status == 'doing') dao::$errors[] = $this->lang->task->error->alreadyStarted;
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
            if(isset($task->consumed) and $task->consumed == 0) return dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->task->consumed);
            if(empty($oldTask->team))
            {
                $task->status       = 'done';
                $task->finishedBy   = $this->app->user->account;
                $task->finishedDate = $now;
                $task->assignedTo   = $oldTask->openedBy;
            }
        }

        /* Record consumed and left. */
        $estimate = new stdclass();
        $estimate->date     = helper::today();
        $estimate->task     = $taskID;
        $estimate->consumed = zget($_POST, 'consumed', 0);
        $estimate->left     = zget($_POST, 'left', 0);
        $estimate->work     = zget($task, 'work', '');
        $estimate->account  = $this->app->user->account;
        $estimate->consumed = (!empty($oldTask->team) and $currentTeam) ? $estimate->consumed - $currentTeam->consumed : $estimate->consumed - $oldTask->consumed;
        if($this->post->comment) $estimate->work = $this->post->comment;
        if($estimate->consumed > 0) $estimateID = $this->addTaskEstimate($estimate);

        if(!empty($oldTask->team) and $currentTeam)
        {
            $team = new stdclass();
            $team->consumed = $this->post->consumed;
            $team->left     = $this->post->left;
            $team->status   = empty($team->left) ? 'done' : 'doing';

            $this->dao->update(TABLE_TASKTEAM)->data($team)->where('id')->eq($currentTeam->id)->exec();
            if($oldTask->mode == 'linear' and !empty($estimateID)) $this->updateEstimateOrder($estimateID, $currentTeam->order);

            $task = $this->computeHours4Multiple($oldTask, $task);
            if($team->status == 'done')
            {
                $task->assignedTo   = $this->getAssignedTo4Multi($oldTask->team, $oldTask, 'next');
                $task->assignedDate = $now;
            }

            $finishedUsers = $this->getFinishedUsers($oldTask->id, array_keys($oldTask->members));
            if(count($finishedUsers) == count($oldTask->team))
            {
                $task->status       = 'done';
                $task->finishedBy   = $this->app->user->account;
                $task->finishedDate = $task->finishedDate;
            }
        }

        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()
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
        if($this->config->edition != 'open' && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);
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
        foreach($record->consumed as $id => $item)
        {
            if(!is_numeric($item) and !empty($item))
            {
                dao::$errors[] = 'ID #' . $id . ' ' . $this->lang->task->error->totalNumber;
            }
            elseif(is_numeric($item) and $item <= 0)
            {
                dao::$errors[] = sprintf($this->lang->error->gt, 'ID #' . $id . ' ' . $this->lang->task->record, '0');
            }
        }
        foreach($record->left as $id => $item)
        {
            $record->left[$id] = trim($item);
            if(!is_numeric($item) and !empty($item)) dao::$errors[] = 'ID #' . $id . ' ' . $this->lang->task->error->leftNumber;
        }
        foreach($record->dates as $id => $item) if($item > $today) dao::$errors[] = 'ID #' . $id . ' ' . $this->lang->task->error->date;
        if(dao::isError()) return false;

        $task       = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();;
        $task->team = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->orderBy('order')->fetchAll('id');

        /* Check if the current user is in the team. */
        $inTeam = empty($task->team) ? true : false;
        foreach($task->team as $teamMember)
        {
            if($teamMember->account == $this->app->user->account) $inTeam = true;
        }
        if(!$inTeam) return false;

        $estimates    = array();
        $earliestTime = '';
        foreach(array_keys($record->dates) as $id)
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
                if(helper::isZeroDate($record->dates[$id])) helper::end(js::alert($this->lang->task->error->dateEmpty));
                if(!$record->consumed[$id])                 helper::end(js::alert($this->lang->task->error->consumedThisTime));
                if($record->left[$id] === '')               helper::end(js::alert($this->lang->task->error->left));

                $estimates[$id] = new stdclass();
                $estimates[$id]->date     = $record->dates[$id];
                $estimates[$id]->task     = $taskID;
                $estimates[$id]->consumed = $record->consumed[$id];
                $estimates[$id]->left     = $record->left[$id];
                $estimates[$id]->work     = $record->work[$id];
                $estimates[$id]->account  = $this->app->user->account;
                if(isset($record->order[$id])) $estimates[$id]->order = $record->order[$id];
            }
        }

        if(empty($estimates)) return;

        $this->loadModel('action');

        $allChanges = array();;
        $left       = $task->left;
        $now        = helper::now();
        $oldStatus  = $task->status;
        $lastDate   = $this->dao->select('*')->from(TABLE_EFFORT)->where('objectID')->eq($taskID)->andWhere('objectType')->eq('task')->orderBy('date_desc,id_desc')->limit(1)->fetch('date');

        foreach($estimates as $estimate)
        {
            $this->addTaskEstimate($estimate);

            $work       = $estimate->work;
            $estimateID = $this->dao->lastInsertID();

            $newTask = clone $task;
            $newTask->consumed      += $estimate->consumed;
            $newTask->lastEditedBy   = $this->app->user->account;
            $newTask->lastEditedDate = $now;
            if(helper::isZeroDate($task->realStarted)) $newTask->realStarted = $now;

            if(empty($lastDate) or $lastDate <= $estimate->date)
            {
                $newTask->left = $estimate->left;
                $lastDate      = $estimate->date;
            }

            if(!empty($task->team))
            {
                $extra = array('filter' => 'done');
                if(isset($estimate->order)) $extra['order'] = $estimate->order;
                $currentTeam = $this->getTeamByAccount($task->team, $this->app->user->account, $extra);
            }

            if($newTask->left == 0 and ((empty($currentTeam) and strpos('done,cancel,closed', $task->status) === false) or (!empty($currentTeam) and $currentTeam->status != 'done')))
            {
                $newTask->status         = 'done';
                $newTask->assignedTo     = $task->openedBy;
                $newTask->assignedDate   = $now;
                $newTask->finishedBy     = $this->app->user->account;
                $newTask->finishedDate   = $now;
                $actionID = $this->action->create('task', $taskID, 'Finished', $work);
            }
            elseif($newTask->status == 'wait')
            {
                $newTask->status       = 'doing';
                $newTask->assignedTo   = $this->app->user->account;
                $newTask->assignedDate = $now;
                $actionID = $this->action->create('task', $taskID, 'Started', $work);
            }
            elseif($newTask->left != 0 and strpos('done,pause,cancel,closed,pause', $task->status) !== false)
            {
                $newTask->status         = 'doing';
                $newTask->assignedTo     = $this->app->user->account;
                $newTask->finishedBy     = '';
                $newTask->canceledBy     = '';
                $newTask->closedBy       = '';
                $newTask->closedReason   = '';
                $newTask->finishedDate   = '0000-00-00 00:00:00';
                $newTask->canceledDate   = '0000-00-00 00:00:00';
                $newTask->closedDate     = '0000-00-00 00:00:00';
                $actionID = $this->action->create('task', $taskID, 'Activated', $work);
            }
            else
            {
                $actionID = $this->action->create('task', $taskID, 'RecordEstimate', $work, (float)$estimate->consumed);
            }

            /* Process multi-person task. Update consumed on team table. */
            if(!empty($task->team))
            {
                if(!empty($currentTeam))
                {
                    $teamStatus = $estimate->left == 0 ? 'done' : 'doing';
                    $this->dao->update(TABLE_TASKTEAM)->set('left')->eq($estimate->left)->set("consumed = consumed + {$estimate->consumed}")->set('status')->eq($teamStatus)->where('id')->eq($currentTeam->id)->exec();
                    if($task->mode == 'linear' and empty($estimate->order)) $this->updateEstimateOrder($estimateID, $currentTeam->order);
                    $currentTeam->consumed += $estimate->consumed;
                    $currentTeam->left      = $estimate->left;
                    $currentTeam->status    = $teamStatus;
                }

                $newTask = $this->computeHours4Multiple($task, $newTask, $task->team);
            }

            $changes = common::createChanges($task, $newTask, 'task');
            if($changes and !empty($actionID)) $this->action->logHistory($actionID, $changes);
            if($changes) $allChanges = array_merge($allChanges, $changes);
            $task = $newTask;
        }

        if($allChanges)
        {
            $this->dao->update(TABLE_TASK)->data($task, 'team')->where('id')->eq($taskID)->exec();

            if($task->parent > 0) $this->updateParentStatus($task->id);
            if($task->story)  $this->loadModel('story')->setStage($task->story);
            if($task->status != $oldStatus) $this->loadModel('kanban')->updateLane($task->execution, 'task', $taskID);
            if($task->status == 'done' and !dao::isError()) $this->loadModel('score')->create('task', 'finish', $taskID);
        }

        return $allChanges;
    }

    /**
     * Set effort left to 0.
     *
     * @param  int    $taskID
     * @param  array  $members
     * @access public
     * @return void
     */
    public function resetEffortLeft($taskID, $members)
    {
        foreach($members as $account)
        {
            $this->dao->update(TABLE_EFFORT)->set('`left`')->eq(0)
                ->where('account')->eq($account)
                ->andWhere('objectID')->eq($taskID)
                ->andWhere('objectType')->eq('task')
                ->orderBy('date_desc,id_desc')
                ->limit('1')
                ->exec();
        }
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
        if(!is_numeric($currentConsumed)) return dao::$errors[] = $this->lang->task->error->consumedNumber;
        if(empty($currentConsumed) and $oldTask->consumed == '0') return dao::$errors[] = $this->lang->task->error->consumedEmpty;
        if(!$this->post->realStarted) return dao::$errors[] = $this->lang->task->error->realStartedEmpty;
        if(!$this->post->finishedDate) return dao::$errors[] = $this->lang->task->error->finishedDateEmpty;
        if($this->post->realStarted > $this->post->finishedDate) return dao::$errors[] = $this->lang->task->error->finishedDateSmall;

        /* Record consumed and left. */
        if(empty($oldTask->team))
        {
            $consumed = $task->consumed - $oldTask->consumed;
            if($consumed < 0) return dao::$errors[] = $this->lang->task->error->consumedSmall;
        }
        else
        {
            $currentTeam = $this->getTeamByAccount($oldTask->team);
            $consumed = $currentTeam ? $task->consumed - $currentTeam->consumed : $task->consumed;
            if($consumed < 0) return dao::$errors[] = $this->lang->task->error->consumedSmall;
        }

        $estimate = new stdclass();
        $estimate->date     = helper::isZeroDate($task->finishedDate) ? helper::today() : substr($task->finishedDate, 0, 10);
        $estimate->task     = $taskID;
        $estimate->left     = 0;
        $estimate->work     = zget($task, 'work', '');
        $estimate->account  = $this->app->user->account;
        $estimate->consumed = $consumed;
        if($this->post->comment) $estimate->work = $this->post->comment;
        if($estimate->consumed) $estimateID = $this->addTaskEstimate($estimate);

        if(!empty($oldTask->team) and $currentTeam)
        {
            $this->dao->update(TABLE_TASKTEAM)->set('left')->eq(0)->set('consumed')->eq($task->consumed)->set('status')->eq('done')->where('id')->eq($currentTeam->id)->exec();
            if($oldTask->mode == 'linear' and isset($estimateID)) $this->updateEstimateOrder($estimateID, $currentTeam->order);
            $task = $this->computeHours4Multiple($oldTask, $task);
        }

        if($task->finishedDate == substr($now, 0, 10)) $task->finishedDate = $now;

        $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->finish['id'], $this->post->uid);
        $this->dao->update(TABLE_TASK)->data($task)->autoCheck()->checkFlow()
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

            if($this->config->edition != 'open' && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);

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

            if($this->config->edition != 'open' && $oldTask->feedback) $this->loadModel('feedback')->updateStatus('task', $oldTask->feedback, $task->status, $oldTask->status);

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
            ->setDefault('finishedDate', '0000-00-00 00:00:00')
            ->setDefault('canceledBy, lastEditedBy', $this->app->user->account)
            ->setDefault('canceledDate, lastEditedDate', $now)
            ->stripTags($this->config->task->editor->cancel['id'], $this->config->allowedTags)
            ->setIF(empty($oldTask->finishedDate), 'finishedDate', '')
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
            ->setDefault('assignedTo', '')
            ->setDefault('status', 'doing')
            ->setDefault('finishedBy, canceledBy, closedBy, closedReason', '')
            ->setDefault('finishedDate, canceledDate, closedDate', null)
            ->setDefault('lastEditedBy',   $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('assignedDate', helper::now())
            ->setDefault('activatedDate', helper::now())
            ->stripTags($this->config->task->editor->activate['id'], $this->config->allowedTags)
            ->setIF(empty($oldTask->finishedDate), 'finishedDate', '')
            ->setIF(empty($oldTask->canceledDate), 'canceledDate', '')
            ->setIF(empty($oldTask->closedDate), 'closedDate', '')
            ->remove('comment,uid,multiple,team,teamEstimate,teamConsumed,teamLeft,teamSource')
            ->get();

        if(!is_numeric($task->left))
        {
            dao::$errors[] = $this->lang->task->error->leftNumber;
            return false;
        }

        if(empty($task->left))
        {
            dao::$errors[] = sprintf($this->lang->task->error->notempty, $this->lang->task->left);
            return false;
        }

        if(!empty($oldTask->team))
        {
            $this->manageTaskTeam($oldTask->mode, $oldTask->id, $task->status);
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
        $task->openedDate     = substr($task->openedDate, 0, 19);
        $task->finishedDate   = substr($task->finishedDate, 0, 19);
        $task->canceledDate   = substr($task->canceledDate, 0, 19);
        $task->closedDate     = substr($task->closedDate, 0, 19);
        $task->lastEditedDate = substr($task->lastEditedDate, 0, 19);
        $task->realStarted    = substr($task->realStarted, 0, 19);

        $children = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->eq($taskID)->andWhere('deleted')->eq(0)->fetchAll('id');
        $task->children = $children;

        /* Check parent Task. */
        if($task->parent > 0) $task->parentName = $this->dao->findById($task->parent)->from(TABLE_TASK)->fetch('name');

        $task->members = array();
        $task->team    = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->orderBy('order')->fetchAll('id');
        foreach($task->team as $member) $task->members[$member->account] = $member->account;

        foreach($children as $child)
        {
            $child->team    = array();
            $child->members = array();
        }

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
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $fields  = "DISTINCT t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder";
        ($this->config->edition == 'max' or $this->config->edition == 'ipd') && $fields .= ', t5.name as designName, t5.version as latestDesignVersion';

        $actionIDList = array();
        if($type == 'assignedbyme') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($this->app->user->account)->fetchPairs('objectID', 'objectID');

        $tasks  = $this->dao->select($fields)
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_TASKTEAM)->alias('t3')->on('t3.task = t1.id')
            ->beginIF($productID)->leftJoin(TABLE_MODULE)->alias('t4')->on('t1.module = t4.id')->fi()
            ->beginIF($this->config->edition == 'max' or $this->config->edition == 'ipd')->leftJoin(TABLE_DESIGN)->alias('t5')->on('t1.design= t5.id')->fi()
            ->where('t1.execution')->eq((int)$executionID)
            ->beginIF($type == 'myinvolved')
            ->andWhere("((t3.`account` = '{$this->app->user->account}') OR t1.`assignedTo` = '{$this->app->user->account}' OR t1.`finishedby` = '{$this->app->user->account}')")
            ->fi()
            ->beginIF($productID)->andWhere("((t4.root=" . (int)$productID . " and t4.type='story') OR t2.product=" . (int)$productID . ")")->fi()
            ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($type == 'needconfirm')->andWhere('t2.version > t1.storyVersion')->andWhere("t2.status = 'active'")->fi()
            ->beginIF($type == 'assignedtome')->andWhere("(t1.assignedTo = '{$this->app->user->account}' or (t1.mode = 'multi' and t3.`account` = '{$this->app->user->account}' and t1.status != 'closed' and t3.status != 'done') )")->fi()
            ->beginIF($type == 'finishedbyme')
            ->andWhere('t1.finishedby', 1)->eq($this->app->user->account)
            ->orWhere('t3.status')->eq("done")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,assignedbyme,review,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($type == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', ($productID or in_array($type, array('myinvolved', 'needconfirm', 'assignedtome', 'finishedbyme'))) ? false : true);

        if(empty($tasks)) return array();

        $taskList = array_keys($tasks);
        $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in($taskList)->fetchGroup('task');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
        }

        $parents = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents  = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');
        $userList = $this->dao->select('account,realname')->from(TABLE_USER)->fetchPairs('account');

        if($this->config->vision == 'lite') $tasks = $this->appendLane($tasks);
        foreach($tasks as $task)
        {
            $task->assignedToRealName = zget($userList, $task->assignedTo);
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
        while($task = $stmt->fetch()) $tasks[$task->id] = ($task->parent > 0 ? "[{$this->lang->task->childrenAB}] " : '') . "$task->id:" . (empty($task->finishedByRealName) ? '' : "$task->finishedByRealName:") . "$task->name";
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

        $taskTeams = $this->dao->select('task, count(*) as count')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->groupBy('task')->fetchPairs('task', 'count');
        foreach($tasks as $id => $name)
        {
            if(!empty($taskTeams[$id])) unset($tasks[$id]);
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
        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        $orderBy = str_replace('project_', 't1.project_', $orderBy);
        $tasks   = $this->dao->select("t1.*, t4.id as project, t2.id as executionID, t2.name as executionName, t4.name as projectName, t2.multiple as executionMultiple, t2.type as executionType, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on("t2.project = t4.id")
            ->leftJoin(TABLE_TASKTEAM)->alias('t5')->on("t5.task = t1.id and t5.account = '{$account}'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($type == 'finishedBy')
            ->andWhere('t1.finishedby', 1)->eq($account)
            ->orWhere('t5.status')->eq("done")
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'assignedTo' and ($this->app->rawModule == 'my' or $this->app->rawModule == 'block'))->andWhere('t2.status', true)->ne('suspended')->orWhere('t4.status')->ne('suspended')->markRight(1)->fi()
            ->beginIF($type != 'all' and $type != 'finishedBy' and $type != 'assignedTo')->andWhere("t1.`$type`")->eq($account)->fi()
            ->beginIF($type == 'assignedTo')->andWhere("(t1.assignedTo = '{$account}' or (t1.mode = 'multi' and t5.`account` = '{$account}' and t1.status != 'closed' and t5.status != 'done') )")->fi()
            ->beginIF($type == 'assignedTo' and $this->app->rawModule == 'my' and $this->app->rawMethod == 'work')->andWhere('t1.status')->notin('closed,cancel')->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', false);

        $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');
        foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;

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
            ->andWhere('t3.deleted')->eq(0)
            ->fetchAll('id');
        return $tasks;
    }

    /**
     * Get task pairs of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getStoryTasks($storyID, $executionID = 0, $projectID = 0)
    {
        $tasks = $this->dao->select('id, parent, name, assignedTo, pri, status, estimate, consumed, closedReason, `left`')
            ->from(TABLE_TASK)
            ->where('story')->eq((int)$storyID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
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
     * Update estimate order for linear task team.
     *
     * @param  int    $effortID
     * @param  int    $order
     * @access public
     * @return void
     */
    public function updateEstimateOrder($effortID, $order)
    {
        $this->dao->update(TABLE_EFFORT)->set('`order`')->eq((int)$order)->where('id')->eq($effortID)->exec();
    }

    /**
     * Get task estimate.
     *
     * @param  int    $taskID
     * @param  string $account
     * @param  string $append
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getTaskEstimate($taskID, $account = '', $append = '', $orderBy = 'date,id')
    {
        return $this->dao->select('*')->from(TABLE_EFFORT)->where('objectID')->eq($taskID)
            ->andWhere('objectType')->eq('task')
            ->andWhere('deleted')->eq('0')
            ->beginIF($account)->andWhere('account')->eq($account)->fi()
            ->beginIF($append)->orWhere('id')->eq($append)->fi()
            ->orderBy($orderBy)
            ->fetchAll();
    }

    /**
     * Get taskList date record.
     *
     * @param  int|array $taskID
     * @access public
     * @return array
     */
    public function getTaskDateRecord($taskID)
    {
        return $this->dao->select('id,date')->from(TABLE_EFFORT)->where('objectID')->in($taskID)
            ->andWhere('objectType')->eq('task')
            ->andWhere('deleted')->eq('0')
            ->orderBy('date')
            ->fetchAll('id');
    }

    /**
     * Get estimate by id.
     *
     * @param  int    $effortID
     * @access public
     * @return object.
     */
    public function getEstimateById($effortID)
    {
        $estimate = $this->dao->select('*')->from(TABLE_EFFORT)
            ->where('id')->eq($effortID)
            ->fetch();

        /* If the estimate is the last of its task, status of task will be checked. */
        $lastID = $this->dao->select('id')->from(TABLE_EFFORT)
            ->where('objectID')->eq($estimate->objectID)
            ->andWhere('objectType')->eq('task')
            ->orderBy('date_desc,id_desc')->limit(1)->fetch('id');

        $estimate->isLast = $lastID == $estimate->id;
        return $estimate;
    }

    /**
     * Check operate effort.
     *
     * @param  object    $task
     * @param  object    $effort
     * @access public
     * @return bool
     */
    public function canOperateEffort($task, $effort = null)
    {
        if(empty($task->team)) return $this->loadModel('common')->canOperateEffort($effort);

        /* Check for add effort. */
        if(empty($effort))
        {
            $members = array_map(function($member){ return $member->account; }, $task->team);
            if(!in_array($this->app->user->account, $members)) return false;
            if($task->mode == 'linear' and $this->app->user->account != $task->assignedTo) return false;
            return true;
        }

        /* Check for edit and delete effort. */
        if($task->mode == 'linear')
        {
            if(strpos('|closed|cancel|pause|', "|{$task->status}|") !== false) return false;
            if($task->status == 'doing') return $effort->account == $this->app->user->account;
        }
        if($this->app->user->account == $effort->account) return true;
        return false;
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
        $today       = helper::today();
        $estimate    = fixer::input('post')
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(is_numeric($this->post->left), 'left', (float)$this->post->left)
            ->get();

        if(helper::isZeroDate($estimate->date)) return dao::$errors[] = $this->lang->task->error->dateEmpty;
        if($estimate->date > $today)            return dao::$errors[] = $this->lang->task->error->date;
        if($estimate->consumed <= 0)            return dao::$errors[] = sprintf($this->lang->error->gt, $this->lang->task->record, '0');
        if($estimate->left < 0)                 return dao::$errors[] = sprintf($this->lang->error->ge, $this->lang->task->left, '0');

        $task = $this->getById($oldEstimate->objectID);
        $this->dao->update(TABLE_EFFORT)->data($estimate)
            ->autoCheck()
            ->where('id')->eq((int)$estimateID)
            ->exec();

        $lastEstimate = $this->dao->select('*')->from(TABLE_EFFORT)
            ->where('objectID')->eq($task->id)
            ->andWhere('objectType')->eq('task')
            ->orderBy('date_desc,id_desc')->limit(1)->fetch();

        $consumed = $task->consumed + $estimate->consumed - $oldEstimate->consumed;
        $left     = ($lastEstimate and $estimateID == $lastEstimate->id) ? $estimate->left : $task->left;

        $now  = helper::now();
        $data = new stdClass();
        $data->consumed       = $consumed;
        $data->left           = $left;
        $data->status         = $task->status;
        $data->lastEditedBy   = $this->app->user->account;
        $data->lastEditedDate = $now;
        if(empty($left) and strpos('wait,doing,pause', $task->status) !== false)
        {
            $data->status       = 'done';
            $data->finishedBy   = $this->app->user->account;
            $data->finishedDate = $now;
            $data->assignedTo   = $task->openedBy;
        }

        if(!empty($task->team))
        {
            $currentTeam = $this->getTeamByAccount($task->team, $oldEstimate->account, array('order' => $oldEstimate->order));
            if($currentTeam)
            {
                $newTeamInfo = new stdClass();
                $newTeamInfo->consumed = $currentTeam->consumed + $estimate->consumed - $oldEstimate->consumed;
                if($currentTeam->status != 'done') $newTeamInfo->left = $left;
                if($currentTeam->status != 'done' and $newTeamInfo->consumed > 0 and $left == 0) $newTeamInfo->status = 'done';
                $this->dao->update(TABLE_TASKTEAM)->data($newTeamInfo)->where('id')->eq($currentTeam->id)->exec();

                $data = $this->computeHours4Multiple($task, $data);
            }
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
        $task     = $this->getById($estimate->objectID);
        $now      = helper::now();

        $consumed = $task->consumed - $estimate->consumed;
        $left     = $task->left;
        if($estimate->isLast)
        {
            $lastTwoEstimates = $this->dao->select('*')->from(TABLE_EFFORT)
                ->where('objectID')->eq($estimate->objectID)
                ->andWhere('objectType')->eq('task')
                ->orderBy('date desc,id desc')->limit(2)->fetchAll();
            $lastTwoEstimate  = isset($lastTwoEstimates[1]) ? $lastTwoEstimates[1] : '';
            if($lastTwoEstimate) $left = $lastTwoEstimate->left;
            if(empty($lastTwoEstimate) and $left == 0) $left = $task->estimate;
        }

        $data = new stdclass();
        $data->consumed = $consumed;
        $data->left     = $left;
        $data->status   = ($left == 0 && $consumed != 0) ? 'done' : $task->status;
        if($estimate->isLast and $consumed == 0 and $task->status != 'wait')
        {
            $data->status       = 'wait';
            $data->left         = $task->estimate;
            $data->finishedBy   = '';
            $data->canceledBy   = '';
            $data->closedBy     = '';
            $data->closedReason = '';
            $data->finishedDate = '0000-00-00 00:00:00';
            $data->canceledDate = '0000-00-00 00:00:00';
            $data->closedDate   = '0000-00-00 00:00:00';
            if($task->assignedTo == 'closed') $data->assignedTo = $this->app->user->account;
        }
        elseif($consumed != 0 and $left == 0 and strpos('done,pause,cancel,closed', $task->status) === false)
        {
            $data->status         = 'done';
            $data->assignedTo     = $task->openedBy;
            $data->assignedDate   = $now;
            $data->finishedBy     = $this->app->user->account;
            $data->finishedDate   = $now;
        }
        elseif($estimate->isLast and $left != 0 and strpos('done,pause,cancel,closed', $task->status) !== false)
        {
            $data->status         = 'doing';
            $data->finishedBy     = '';
            $data->canceledBy     = '';
            $data->closedBy       = '';
            $data->closedReason   = '';
            $data->finishedDate   = '0000-00-00 00:00:00';
            $data->canceledDate   = '0000-00-00 00:00:00';
            $data->closedDate     = '0000-00-00 00:00:00';
        }
        else
        {
            $data->status = $task->status;
        }

        if(!empty($task->team))
        {
            $currentTeam = $this->getTeamByAccount($task->team, $estimate->account, array('effortID' => $estimateID, 'order' => $estimate->order));
            if($currentTeam)
            {
                $left = $currentTeam->left;
                if($task->mode == 'multi')
                {
                    $accountEstimates = $this->getTaskEstimate($currentTeam->task, $estimate->account, $estimateID);
                    $lastEstimate     = array_pop($accountEstimates);
                    if($lastEstimate->id == $estimateID)
                    {
                        $lastTwoEstimate = array_pop($accountEstimates);
                        if($lastTwoEstimate) $left = $lastTwoEstimate->left;
                    }
                }

                $newTeamInfo = new stdClass();
                $newTeamInfo->consumed = $currentTeam->consumed - $estimate->consumed;
                if($currentTeam->status != 'done') $newTeamInfo->left = $left;
                if($currentTeam->status == 'done' and $left > 0 and $task->mode == 'multi') $newTeamInfo->left = $left;

                if($currentTeam->status != 'done' and $newTeamInfo->consumed > 0 and $left == 0) $newTeamInfo->status = 'done';
                if($task->mode == 'multi' and $currentTeam->status == 'done' and $left > 0) $newTeamInfo->status = 'doing';
                if($task->mode == 'multi' and $currentTeam->status == 'done' and ($newTeamInfo->consumed == 0 and $left == 0))
                {
                    $newTeamInfo->status = 'doing';
                    $newTeamInfo->left   = $currentTeam->estimate;
                }
                $this->dao->update(TABLE_TASKTEAM)->data($newTeamInfo)->where('id')->eq($currentTeam->id)->exec();
            }
        }

        $this->dao->update(TABLE_EFFORT)->set('deleted')->eq('1')->where('id')->eq($estimateID)->exec();
        if(!empty($task->team)) $data = $this->computeHours4Multiple($task, $data);

        $this->dao->update(TABLE_TASK)->data($data) ->where('id')->eq($estimate->objectID)->exec();
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

        if($task->mode == 'multi')
        {
            $teamMembers = $this->dao->select('t1.realname')->from(TABLE_USER)->alias('t1')
                ->leftJoin(TABLE_TASKTEAM)->alias('t2')
                ->on('t1.account = t2.account')
                ->where('t2.task')->eq($task->id)
                ->fetchPairs();
            $task->teamMembers= join(',', array_keys($teamMembers));
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
        $tasks = $this->dao->select("id, DATE_FORMAT(`finishedDate`, '%Y-%m-%d') AS `date`")->from(TABLE_TASK)
            ->where($this->reportCondition())
            ->andWhere('finishedDate')->notZeroDatetime()
            ->orderBy('finishedDate asc')
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

        if(!empty($task->team))
        {
            global $app;
            $myself = new self();
            if($task->mode == 'linear')
            {
                if($action == 'assignto' and strpos('done,cencel,closed', $task->status) === false) return false;
                if($action == 'start' and strpos('wait,doing', $task->status) !== false)
                {
                    if($task->assignedTo != $app->user->account) return false;

                    $currentTeam = $myself->getTeamByAccount($task->team, $app->user->account);
                    if($currentTeam and $currentTeam->status == 'wait') return true;
                }
                if($action == 'finish' and $task->assignedTo != $app->user->account) return false;
            }
            elseif($task->mode == 'multi')
            {
                $currentTeam = $myself->getTeamByAccount($task->team, $app->user->account);
                if($action == 'start' and strpos('wait,doing', $task->status) !== false and $currentTeam and $currentTeam->status == 'wait') return true;
                if($action == 'finish' and (empty($currentTeam) or $currentTeam->status == 'done')) return false;
            }
        }

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
     * @return int
     */
    public function addTaskEstimate($data)
    {
        $oldTask = $this->getById($data->task);

        $relation = $this->loadModel('action')->getRelatedFields('task', $data->task);

        $effort = new stdclass();
        $effort->objectType = 'task';
        $effort->objectID   = $data->task;
        $effort->execution  = $oldTask->execution;
        $effort->product    = $relation['product'];
        $effort->project    = (int)$relation['project'];
        $effort->account    = $data->account;
        $effort->date       = $data->date;
        $effort->consumed   = $data->consumed;
        $effort->left       = $data->left;
        $effort->work       = isset($data->work) ? $data->work : '';
        $effort->vision     = $this->config->vision;
        $effort->order      = isset($data->order) ? $data->order : 0;
        $this->dao->insert(TABLE_EFFORT)->data($effort)->autoCheck()->exec();

        return $this->dao->lastInsertID();
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
     * @param array  $privs
     *
     * @access public
     * @return void
     */
    public function printCell($col, $task, $users, $browseType, $branchGroups, $modulePairs = array(), $mode = 'datatable', $child = false, $showBranch = false, $privs = array())
    {
        if(!empty($privs))
        {
            $canBatchEdit         = $privs['canBatchEdit'];
            $canBatchClose        = $privs['canBatchClose'];
            $canBatchCancel       = $privs['canBatchCancel'];
            $canBatchChangeModule = $privs['canBatchChangeModule'];
            $canBatchAssignTo     = $privs['canBatchAssignTo'];
        }
        else
        {
            $canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
            $canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) and strtolower($browseType) != 'closed');
            $canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null);
            $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
            $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);
        }

        $canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchCancel or $canBatchChangeModule or $canBatchAssignTo);
        $storyChanged   = (!empty($task->storyStatus) and $task->storyStatus == 'active' and $task->latestStoryVersion > $task->storyVersion and !in_array($task->status, array('cancel', 'closed')));

        $canView  = common::hasPriv('task', 'view');
        if($this->config->vision == 'lite')
        {
            $taskLink  = helper::createLink('task', 'view', "taskID=$task->id", '', true);
            $linkClass = 'class="iframe"';
        }
        else
        {
            $taskLink  = helper::createLink('task', 'view', "taskID=$task->id");
            $linkClass = '';
        }
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
                $priClass = $task->pri ? "label-pri label-pri-{$task->pri}" : '';
                echo "<span class='$priClass' title='" . zget($this->lang->task->priList, $task->pri, $task->pri) . "'>";
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
                echo $canView ? html::a($taskLink, $task->name, null, "$linkClass style='color: $task->color' title='$task->name'") : "<span style='color: $task->color'>$task->name</span>";
                if(!empty($task->children)) echo '<a class="task-toggle" data-id="' . $task->id . '"><i class="icon icon-angle-double-right"></i></a>';
                if($task->fromBug) echo html::a(helper::createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '', "class='bug'");
                break;
            case 'type':
                echo zget($this->lang->task->typeList, $task->type, $task->type);
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
                if(!helper::isZeroDate($task->deadline)) echo '<span>' . substr($task->deadline, 5, 6) . '</span>';
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
     * @param  bool   $output
     * @access public
     * @return void
     */
    public function printAssignedHtml($task, $users, $output = true)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = $assignedToTitle = zget($users, $task->assignedTo);
        if(!empty($task->team) and $task->mode == 'multi' and strpos('done,closed', $task->status) === false)
        {
            $assignedToText = $this->lang->task->team;

            $teamMembers = array();
            foreach($task->team as $teamMember)
            {
                $realname = zget($users, $teamMember->account);
                if($this->app->user->account == $teamMember->account and $teamMember->status != 'done')
                {
                    $task->assignedTo = $this->app->user->account;
                    $assignedToText   = $realname;
                }
                $teamMembers[] = $realname;
            }

            $assignedToTitle = implode($this->lang->comma, $teamMembers);
        }
        elseif(empty($task->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->task->noAssigned;
        }
        if($task->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($task->assignedTo) and $task->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $task->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = $task->assignedTo == 'closed' ? '#' : helper::createLink('task', 'assignTo', "executionID=$task->execution&taskID=$task->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . $assignedToTitle . "'>{$assignedToText}</span>", '', "class='$btnClass' data-toggle='modal'");

        $html = !common::hasPriv('task', 'assignTo', $task) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;

        if(!$output) return $html;
        echo $html;
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
        $toList         = $task->assignedTo;
        $ccList         = trim($task->mailto, ',');
        $toTeamTaskList = '';
        if($task->mode == 'multi')
        {
            $toTeamTaskList = $this->getTeamMembers($task->id);
            $toTeamTaskList = implode(',', $toTeamTaskList);
            $toList         = $toTeamTaskList;
        }

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
     * @param  object $task
     * @param  string $type   current|next
     *
     * @access public
     * @return string
     */
    public function getAssignedTo4Multi($users, $task, $type = 'current')
    {
        if(empty($task->team) or $task->mode != 'linear') return $task->assignedTo;

        $teamHours = array_values($task->team);

        /* Process user */
        if(!is_array($users)) $users = explode(',', trim($users, ','));
        $users = array_values($users);
        if(is_object($users[0])) $users = array_map(function($member){ return $member->account; }, $users);

        foreach($users as $i => $account)
        {
            if(isset($teamHours[$i]) and $teamHours[$i]->status == 'done') continue;
            if($type == 'current') return $account;
            break;
        }
        if($type == 'next' and isset($users[$i + 1])) return $users[$i + 1];

        return $task->openedBy;
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
        return $this->dao->select('id,account')->from(TABLE_TASKTEAM)
            ->where('task')->eq($taskID)
            ->andWhere('status')->eq('done')
            ->beginIF($team)->andWhere('account')->in($team)->fi()
            ->fetchPairs('id', 'account');
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
        $this->app->loadLang('execution');

        $today    = helper::today();
        $showmore = $showmore ? 'showmore' : '';
        $trAttrs  = "data-id='t$task->id'";

        /* Remove projectID in execution path. */
        $executionPath = $execution->path;
        $executionPath = trim($executionPath, ',');
        $executionPath = substr($executionPath, strpos($executionPath, ',') + 1);

        if(!$isChild)
        {

            $path     = ",{$executionPath},t{$task->id},";
            $trAttrs .= " data-parent='$execution->id' data-nest-parent='$execution->id' data-nest-path='$path'";
            if(empty($task->children)) $trAttrs .= " data-nested='false'";
            $trClass  = empty($task->children) ? '' : " has-nest-child";
        }
        else
        {
            $path     = ",{$executionPath},{$task->parent},t{$task->id},";
            $trClass  = 'is-nest-child no-nest';
            $trAttrs .= " data-nested='false' data-parent='t$task->parent' data-nest-parent='t$task->parent' data-nest-path='$path'";
        }

        $list  = "<tr $trAttrs class='$trClass $showmore'>";
        $list .= '<td class="c-name text-left flex">';
        if($task->parent > 0) $list .= '<span class="label label-badge label-light" title="' . $this->lang->task->children . '">' . $this->lang->task->childrenAB . '</span> ';
        $list .= common::hasPriv('task', 'view') ? html::a(helper::createLink('task', 'view', "id=$task->id"), $task->name, '', "style='color: $task->color' class='text-ellipsis'", "data-app='project'") : "<span style='color:$task->color'>$task->name</span>";
        if(!helper::isZeroDate($task->deadline))
        {
            if($task->status != 'done')
            {
                $list .= strtotime($today) > strtotime($task->deadline) ? '<span class="label label-danger label-badge">' . $this->lang->execution->delayed . '</span>' : '';
            }
        }
        $list .= '</td>';
        if(!empty($execution->division) and $execution->hasProduct) $list .= '<td></td>';
        $list .= "<td class='status-{$task->status} text-center'>" . $this->processStatus('task', $task) . '</td>';
        $list .= '<td>' . zget($users, $task->assignedTo, '') . '</td>';
        $list .= helper::isZeroDate($task->estStarted) ? '<td class="c-date"></td>' : '<td class="c-date">' . $task->estStarted . '</td>';
        $list .= helper::isZeroDate($task->deadline) ? '<td class="c-date"></td>' : '<td class="c-date">' . $task->deadline . '</td>';
        $list .= '<td class="hours text-right">' . $task->estimate . $this->lang->execution->workHourUnit . '</td>';
        $list .= '<td class="hours text-right">' . $task->consumed . $this->lang->execution->workHourUnit . '</td>';
        $list .= '<td class="hours text-right">' . $task->left . $this->lang->execution->workHourUnit . '</td>';
        $list .= '<td></td>';
        $list .= '<td></td>';
        $list .= '<td class="c-actions">';
        $list .= $this->buildOperateBrowseMenu($task, $execution);
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

        $plmCanStart = true;
        $isPLMMode   = $this->config->systemMode == 'PLM';

        if($isPLMMode)
        {
            $execution           = $this->loadModel('execution')->getByID($task->execution);
            $execution->ipdStage = $this->loadModel('execution')->canStageStart($execution);
            $plmCanStart = $execution->status == 'wait' ? $execution->ipdStage['canStart'] : 1;
            if($execution->status == 'close') $plmCanStart = false;
        }

        $menu   = '';
        $params = "taskID=$task->id";
        if((empty($task->team) || empty($task->children)) && $task->executionList->type != 'kanban')
        {
            $menu .= $this->buildMenu('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", $task, 'view', 'split', '', '', '', "title='{$this->lang->task->children}'", $this->lang->task->children);
        }

        $menu .= $this->buildMenu('task', 'assignTo', "executionID=$task->execution&taskID=$task->id", $task, 'button', '', '', 'iframe', true, '', $this->lang->task->assignTo);

        if($plmCanStart) $menu .= $this->buildMenu('task', 'start',          $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        if($plmCanStart) $menu .= $this->buildMenu('task', 'restart',        $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        //if(empty($task->linkedBranch))
        //{
        //    $hasRepo = $this->loadModel('repo')->getRepoPairs('execution', $task->execution, false);
        //    if($hasRepo) $menu .= $this->buildMenu('repo', 'createBranch', $params . "&execution={$task->execution}", $task, '', 'treemap', '', 'iframe showinonlybody', true, '', $this->lang->repo->createBranchAction);
        //}
        if($plmCanStart) $menu .= $this->buildMenu('task', 'recordEstimate', $params, $task, 'view', '', '', 'iframe showinonlybody', true);

        $menu .= $this->buildMenu('task', 'pause',          $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        if($plmCanStart) $menu .= $this->buildMenu('task', 'finish',         $params, $task, 'view', '', '', 'iframe showinonlybody text-success', true);
        $menu .= $this->buildMenu('task', 'activate',       $params, $task, 'view', '', '', 'iframe showinonlybody text-success', true);
        $menu .= $this->buildMenu('task', 'close',          $params, $task, 'view', '', '', 'iframe showinonlybody', true);
        $menu .= $this->buildMenu('task', 'cancel',         $params, $task, 'view', '', '', 'iframe showinonlybody', true);

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('task', $task, 'view', 'direct');
        $menu .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('task', 'edit', $params, $task, 'view', '', '', 'showinonlybody');
        $menu .= $this->buildMenu('task', 'create', "projctID={$task->execution}&storyID=0&moduleID=0&taskID=$task->id", $task, 'view', 'copy');
        $menu .= $this->buildMenu('task', 'delete', "executionID=$task->execution&taskID=$task->id", $task, 'view', 'trash', 'hiddenwin', 'showinonlybody');
        if($task->parent > 0) $menu .= $this->buildMenu('task', 'view', "taskID=$task->parent", $task, 'view', 'chevron-double-up', '', '', '', '', $this->lang->task->parent);

        return $menu;
    }

    /**
     * Build task browse action menu.
     *
     * @param  object $task
     * @param  object $execution
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($task, $execution)
    {
        $menu   = '';
        $params = "taskID=$task->id";

        $storyChanged = !empty($task->storyStatus) && $task->storyStatus == 'active' && $task->latestStoryVersion > $task->storyVersion && !in_array($task->status, array('cancel', 'closed'));
        if($storyChanged) return $this->buildMenu('task', 'confirmStoryChange', $params, $task, 'browse', '', 'hiddenwin');

        $canStart          = ($task->status != 'pause' and common::hasPriv('task', 'start'));
        $canRestart        = ($task->status == 'pause' and common::hasPriv('task', 'restart'));
        $canFinish         = common::hasPriv('task', 'finish');
        $canClose          = common::hasPriv('task', 'close');
        $canRecordEstimate = common::hasPriv('task', 'recordEstimate');
        $canEdit           = common::hasPriv('task', 'edit');
        $canBatchCreate    = ($this->config->vision != 'lite' and common::hasPriv('task', 'batchCreate'));

        if($task->status != 'pause') $menu .= $this->buildMenu('task', 'start',   $params, $task, 'browse', '', '', 'iframe', true, 'data-toggle="modal"');
        if($task->status == 'pause') $menu .= $this->buildMenu('task', 'restart', $params, $task, 'browse', '', '', 'iframe', true, 'data-toggle="modal"');
        $menu .= $this->buildMenu('task', 'finish', $params, $task, 'browse', '', '', 'iframe', true, 'data-toggle="modal"');
        $menu .= $this->buildMenu('task', 'close',  $params, $task, 'browse', '', '', 'iframe', true, 'data-toggle="modal"');

        if(($canStart or $canRestart or $canFinish or $canClose) and ($canRecordEstimate or $canEdit or $canBatchCreate))
        {
            $menu .= "<div class='dividing-line'></div>";
        }

        $menu .= $this->buildMenu('task', 'recordEstimate', $params, $task, 'browse', 'time', '', 'iframe', true, 'data-toggle="modal"');
        $menu .= $this->buildMenu('task', 'edit',           $params, $task, 'browse', 'edit', '', '', false, 'data-app="execution"');
        if($this->config->vision != 'lite')
        {
            $menu .= $this->buildMenu('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id&ifame=0", $task, 'browse', 'split', '', '', '', '', $this->lang->task->children);
        }

        return $menu;
    }

    /**
     * Update estimate date by gantt.
     *
     * @param  int     $objectID
     * @param  string  $objectType
     * @access public
     * @return bool
     */
    public function updateEsDateByGantt($objectID, $objectType)
    {
        $this->app->loadLang('project');
        $post = fixer::input('post')->get();
        $post->endDate = date('Y-m-d', strtotime('-1 day', strtotime($post->endDate)));
        $changeTable = $objectType == 'task' ? TABLE_TASK : TABLE_PROJECT;
        $actionType  = $objectType == 'task' ? 'task' : 'execution';
        $oldObject   = $this->dao->select('*')->from($changeTable)->where('id')->eq($objectID)->fetch();
        if($objectType == 'task')
        {
            $this->updateTaskEsDateByGantt($objectID, $objectType, $post);
        }
        elseif($objectType == 'plan')
        {
            $this->updateExecutionEsDateByGantt($objectID, $objectType, $post);
        }

        if(dao::isError()) return false;

        $newObject = $this->dao->select('*')->from($changeTable)->where('id')->eq($objectID)->fetch();
        $changes   = common::createChanges($oldObject, $newObject);
        $actionID  = $this->loadModel('action')->create($actionType, $objectID, 'edited');
        if(!empty($changes)) $this->loadModel('action')->logHistory($actionID, $changes);

        return true;
    }

    /**
     * Update Task estimate date by gantt.
     *
     * @param  int     $objectID
     * @param  string  $objectType
     * @param  object  $postData
     * @access private
     * @return bool
     */
    private function updateTaskEsDateByGantt($objectID, $objectType, $postData)
    {
        $objectData = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($objectID)->fetch();
        $parent     = $objectData->parent;
        $project    = $objectData->project;
        $execution  = $objectData->execution;
        $stage      = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($execution)->andWhere('project')->eq($project)->fetch();

        if($parent <= 0)
        {
            $parentData = $stage;

            $start = $parentData->begin;
            $end   = $parentData->end;
        }
        else
        {
            $parentData = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($parent)->fetch();

            $start = helper::isZeroDate($parentData->estStarted)  ? '' : $parentData->estStarted;
            $end   = helper::isZeroDate($parentData->deadline)    ? '' : $parentData->deadline;
        }

        if(helper::diffDate($start, $postData->startDate) > 0)
        {
            $arg = !empty($parent) ? $this->lang->task->parent : $this->lang->project->stage;
            return dao::$errors = sprintf($this->lang->task->overEsStartDate, $arg, $arg);
        }

        if(helper::diffDate($end, $postData->endDate) < 0)
        {
            $arg = !empty($parent) ? $this->lang->task->parent : $this->lang->project->stage;
            return dao::$errors = sprintf($this->lang->task->overEsEndDate, $arg, $arg);
        }

        $this->dao->update(TABLE_TASK)
            ->set('estStarted')->eq($postData->startDate)
            ->set('deadline')->eq($postData->endDate)
            ->set('lastEditedBy')->eq($this->app->user->account)
            ->where('id')->eq($objectID)
            ->exec();

        return true;
    }

    /**
     * Update Execution estimate date by gantt.
     *
     * @param  int     $objectID
     * @param  string  $objectType
     * @param  object  $postData
     * @access private
     * @return bool
     */
    private function updateExecutionEsDateByGantt($objectID, $objectType, $postData)
    {
        $objectData = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch();
        $project    = $objectData->project;
        $parent     = '';
        if($objectData->project != $objectData->parent) $parent = $objectData->parent;

        if(empty($parent))
        {
            $parentData = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($project)->fetch();
        }
        else
        {
            $parentData = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($parent)->fetch();
        }

        $start      = helper::isZeroDate($parentData->begin) ? '' : $parentData->begin;
        $end        = helper::isZeroDate($parentData->end)   ? '' : $parentData->end;

        if(helper::diffDate($start, $postData->startDate) > 0)
        {
            $arg = !empty($parent) ? $this->lang->programplan->parent : $this->lang->project->common;
            return dao::$errors = sprintf($this->lang->task->overEsStartDate, $arg, $arg);
        }

        if(helper::diffDate($end, $postData->endDate) < 0)
        {
            $arg = !empty($parent) ? $this->lang->programplan->parent : $this->lang->project->common;
            return dao::$errors = sprintf($this->lang->task->overEsEndDate, $arg, $arg);
        }

        $this->dao->update(TABLE_PROJECT)
            ->set('begin')->eq($postData->startDate)
            ->set('end')->eq($postData->endDate)
            ->set('lastEditedBy')->eq($this->app->user->account)
            ->where('id')->eq($objectID)
            ->exec();

        return true;
    }

    /**
     * Update order by gantt.
     *
     * @access public
     * @return void
     */
    public function updateOrderByGantt()
    {
        $data = fixer::input('post')->get();

        $order = 1;
        foreach($data->tasks as $task)
        {
            $idList = explode('-', $task);
            $taskID = $idList[1];
            $this->dao->update(TABLE_TASK)->set('`order`')->eq($order)->where('id')->eq($taskID)->exec();
            $order ++;
        }
    }

    /**
     * Get task list by conditions.
     *
     * @param  object           $conds
     * @param  string           $orderBy
     * @param  object           $pager
     * @access public
     * @return array
     */
    public function getListByConds($conds, $orderBy = 'id_desc', $pager = null)
    {
        foreach(array('priList' => array(), 'assignedToList' => array(), 'statusList' => array(), 'idList' => array(), 'taskName' => '') as $condKey => $defaultValue)
        {
            if(!isset($conds->$condKey))
            {
                $conds->$condKey = $defaultValue;
                continue;
            }
            if(strripos($condKey, 'list') === strlen($condKey) - 4 && !is_array($conds->$condKey)) $conds->$condKey = array_filter(explode(',', $conds->$condKey));
        }

        return $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($conds->priList))->andWhere('pri')->in($conds->priList)->fi()
            ->beginIF(!empty($conds->assignedToList))->andWhere('assignedTo')->in($conds->assignedToList)->fi()
            ->beginIF(!empty($conds->statusList))->andWhere('status')->in($conds->statusList)->fi()
            ->beginIF(!empty($conds->idList))->andWhere('id')->in($conds->idList)->fi()
            ->beginIF(!empty($conds->taskName))->andWhere('name')->like("%{$conds->taskName}%")
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get teamTask members.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getTeamMembers($taskID)
    {
        $taskType = $this->dao->select('mode')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch('mode');
        if($taskType != 'multi') return array();
        $teamMembers = $this->dao->select('account')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->fetchPairs();
        return empty($teamMembers) ? $teamMembers : array_keys($teamMembers);
    }

    /**
     * Check estStarted and deadline date.
     *
     * @param  int    $executionID
     * @param  string $estStarted
     * @param  string $deadline
     * @param  string $pre
     * @access public
     * @return void
     */
    public function checkEstStartedAndDeadline($executionID, $estStarted, $deadline, $pre = '')
    {
        $execution = $this->loadModel('execution')->getByID($executionID);
        if(empty($execution) or empty($this->config->limitTaskDate)) return false;
        if(empty($execution->multiple)) $this->lang->execution->common = $this->lang->project->common;

        if(!empty($estStarted) and !helper::isZeroDate($estStarted) and $estStarted < $execution->begin) dao::$errors['estStarted'][] = $pre . sprintf($this->lang->task->error->beginLtExecution, $this->lang->execution->common, $execution->begin);
        if(!empty($estStarted) and !helper::isZeroDate($estStarted) and $estStarted > $execution->end)   dao::$errors['estStarted'][] = $pre . sprintf($this->lang->task->error->beginGtExecution, $this->lang->execution->common, $execution->end);
        if(!empty($deadline) and !helper::isZeroDate($deadline) and $deadline > $execution->end)       dao::$errors['deadline'][]   = $pre . sprintf($this->lang->task->error->endGtExecution, $this->lang->execution->common, $execution->end);
        if(!empty($deadline) and !helper::isZeroDate($deadline) and $deadline < $execution->begin)     dao::$errors['deadline'][]   = $pre . sprintf($this->lang->task->error->endLtExecution, $this->lang->execution->common, $execution->begin);
    }

    /**
     * Generate col for dtable.
     *
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function generateCol($orderBy = '')
    {
        $setting   = $this->loadModel('datatable')->getSetting('execution');
        $fieldList = $this->config->task->datatable->fieldList;

        foreach($fieldList as $field => $items)
        {
            if(isset($items['title'])) continue;

            $title    = $field == 'id' ? 'ID' : zget($this->lang->task, $field, zget($this->lang, $field, $field));
            $fieldList[$field]['title'] = $title;
        }

        if(empty($setting))
        {
            $setting = $this->config->task->datatable->defaultField;
            $order   = 1;
            foreach($setting as $key => $value)
            {
                $set = new stdclass();;
                $set->id    = $value;
                $set->order = $order ++;
                $set->show  = true;
                $setting[$key] = $set;
            }
        }

        foreach($setting as $key => $set)
        {
            if(empty($set->show))
            {
                unset($setting[$key]);
                continue;
            }

            $sortType = '';
            if(!strpos($orderBy, ',') && strpos($orderBy, $set->id) !== false)
            {
                $sort = str_replace("{$set->id}_", '', $orderBy);
                $sortType = $sort == 'asc' ? 'up' : 'down';
            }

            $set->name  = $set->id;
            $set->title = $fieldList[$set->id]['title'];

            if(isset($fieldList[$set->id]['checkbox']))     $set->checkbox     = $fieldList[$set->id]['checkbox'];
            if(isset($fieldList[$set->id]['nestedToggle'])) $set->nestedToggle = $fieldList[$set->id]['nestedToggle'];
            if(isset($fieldList[$set->id]['fixed']))        $set->fixed        = $fieldList[$set->id]['fixed'];
            if(isset($fieldList[$set->id]['type']))         $set->type         = $fieldList[$set->id]['type'];
            if(isset($fieldList[$set->id]['sortType']))     $set->sortType     = $fieldList[$set->id]['sortType'];
            if(isset($fieldList[$set->id]['flex']))         $set->flex         = $fieldList[$set->id]['flex'];
            if(isset($fieldList[$set->id]['minWidth']))     $set->minWidth     = $fieldList[$set->id]['minWidth'];
            if(isset($fieldList[$set->id]['maxWidth']))     $set->maxWidth     = $fieldList[$set->id]['maxWidth'];
            if(isset($fieldList[$set->id]['pri']))          $set->pri          = $fieldList[$set->id]['pri'];
            if(isset($fieldList[$set->id]['map']))          $set->map          = $fieldList[$set->id]['map'];

            if($sortType) $set->sortType = $sortType;

            if(isset($set->fixed) && $set->fixed == 'no') unset($set->fixed);
            if(isset($set->width)) $set->width = str_replace('px', '', $set->width);
            unset($set->id);
        }

        usort($setting, array('datatableModel', 'sortCols'));
        return $setting;
    }

    /**
     * Generate row for dtable.
     *
     * @param  array  $tasks
     * @param  array  $users
     * @param  object $execution
     * @param  bool   $showBranch
     * @param  array  $branchGroups
     * @param  array  $modulePairs
     * @access public
     * @return array
     */
    public function generateRow($tasks, $users, $execution, $showBranch, $branchGroups, $modulePairs)
    {
        $userFields = array('assignedTo', 'openedBy', 'closedBy', 'lastEditedBy', 'finishedBy');
        $dateFields = array('assignedDate', 'openedDate', 'deadline', 'finishedDate', 'closedDate', 'lastEditedDate', 'canceledDate', 'activatedDate', 'estStarted', 'realStarted', 'replacetypeDate');
        $canView    = common::hasPriv('task', 'view');
        $rows       = array();
        if($showBranch) $showBranch = isset($this->config->execution->task->showBranch) ? $this->config->execution->task->showBranch : 1;

        foreach($tasks as $task)
        {
            $task->actions    = '<div class="c-actions">' . $this->buildOperateBrowseMenu($task, $execution) . '</div>';
            $task->assignedTo = $this->printAssignedHtml($task, $users, false);

            $taskName  = '';
            $taskLink  = helper::createLink('task', 'view', "taskID=$task->id", '', $this->config->vision == 'lite' ? true : false);
            $linkClass = $this->config->vision == 'lite' ? 'data-toggle="modal" data-type="iframe"' : '';

            if($task->parent > 0 and isset($task->parentName)) $task->name = "{$task->parentName} / {$task->name}";
            if(!empty($task->product) and isset($branchGroups[$task->product][$task->branch]) and $showBranch) $taskName .= "<span class='label label-badge label-outline'>" . $branchGroups[$task->product][$task->branch] . '</span> ';
            if($task->module and isset($modulePairs[$task->module])) $taskName .= "<span class='label label-gray label-badge'>" . $modulePairs[$task->module] . '</span> ';
            if($task->parent > 0) $taskName .= '<span class="label label-badge label-light" title="' . $this->lang->task->children . '">' . $this->lang->task->childrenAB . '</span> ';
            if(!empty($task->team)) $taskName .= '<span class="label label-badge label-light" title="' . $this->lang->task->multiple . '">' . $this->lang->task->multipleAB . '</span> ';
            $taskName .= $canView ? html::a($taskLink, $task->name, null, "$linkClass style='color: $task->color' title='$task->name'") : "<span style='color: $task->color'>$task->name</span>";
            if(!empty($task->children)) $taskName .= '<a class="task-toggle" data-id="' . $task->id . '"><i class="icon icon-angle-double-right"></i></a>';
            if($task->fromBug) $taskName .= html::a(helper::createLink('bug', 'view', "id=$task->fromBug"), "[BUG#$task->fromBug]", '', "class='bug'");
            $task->name = $taskName;

            $priClass  = $task->pri ? "label-pri label-pri-{$task->pri}" : '';
            $task->pri = "<span class='{$priClass}'>" . zget($this->lang->task->priList, $task->pri) . '</span>';

            $task->statusCode = $task->status;
            $storyChanged     = (!empty($task->storyStatus) && $task->storyStatus == 'active' && $task->latestStoryVersion > $task->storyVersion && !in_array($task->status, array('cancel', 'closed')));
            $task->status     = $storyChanged ? "<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>" : "<span class='status-task status-{$task->status}' title='{$this->processStatus('task', $task)}'> " . $this->processStatus('task', $task) . "</span>";

            $task->estimateNum = round($task->estimate, 1);
            $task->consumedNum = round($task->consumed, 1);
            $task->leftNum     = round($task->left, 1);

            $task->estimate = round($task->estimate, 1) . $this->lang->execution->workHourUnit;
            $task->consumed = round($task->consumed, 1) . $this->lang->execution->workHourUnit;
            $task->left     = round($task->left, 1) . $this->lang->execution->workHourUnit;

            $task->design = '';
            $task->story  = '';
            if(!empty($task->storyID))
            {
                if(common::hasPriv('story', 'view'))
                {
                    $task->story = html::a(helper::createLink('story', 'view', "storyid=$task->storyID", 'html', true), "<i class='icon icon-{$this->lang->icons['story']}'></i>", '', "class='iframe' data-width='1050' title='{$task->storyTitle}' data-toggle='modal' data-size='lg'");
                }
                else
                {
                   $task->story = "<i class='icon icon-{$this->lang->icons['story']}' title='{$task->storyTitle}'></i>";
                }
            }

            $mailto = explode(',', $task->mailto);
            foreach($mailto as $key => $account)
            {
                $account = trim($account);
                if(empty($account))
                {
                    unset($mailto[$key]);
                    continue;
                }

                $mailto[$key] = zget($users, $account);
            }
            $task->mailto = implode(' &nbsp;', $mailto);

            foreach($userFields as $field) $task->$field = zget($users, $task->$field);
            foreach($dateFields as $field)
            {
                $task->$field = empty($task->$field) || helper::isZeroDate($task->$field) ? '' : $task->$field;
                if($field == 'deadline')
                {
                    $delayed = isset($task->delay) ? "class='delayed'" : '';
                    $task->deadline = "<span $delayed>" . substr($task->deadline, 5, 6) . '</span>';
                }
            }

            $children = isset($task->children) ? $task->children : array();
            unset($task->children);

            $task->isParent = false;
            if($task->parent == -1)
            {
                $task->isParent = true;
                $task->parent   = 0;
            }

            $rows[] = $task;

            if(!empty($children))
            {
                $rows = array_merge($rows, $this->generateRow($children, $users, $execution, $showBranch, $branchGroups, $modulePairs));
            }
        }
        return $rows;
    }

    /**
     * Get linked Branch.
     *
     * @param  int    $taskID
     * @access public
     * @return array
     */
    public function getLinkedBranch($taskID)
    {
        return $this->dao->select('BID,extra')->from(TABLE_RELATION)
            ->where('AType')->eq('task')
            ->andWhere('BType')->eq('repobranch')
            ->andWhere('relation')->eq('linkrepobranch')
            ->andWhere('AID')->eq($taskID)
            ->fetchPairs();
    }
}
