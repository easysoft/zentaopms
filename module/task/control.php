<?php
/**
 * The control file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2013-07-12 01:28:54Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class task extends control
{
    /**
     * Construct function, load model of project and story modules.
     *
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
        $this->loadModel('project');
        $this->loadModel('execution');
        $this->loadModel('story');
        $this->loadModel('tree');
    }

    /**
     * 创建一个任务。
     * Create a task.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  int    $todoID
     * @param  string $cardPosition
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function create(int $executionID = 0, int $storyID = 0, int $moduleID = 0, int $taskID = 0, int $todoID = 0, string $cardPosition = '', int $bugID = 0)
    {
        /* Analytic parameter. */
        $cardPosition = str_replace(array(',', ' '), array('&', ''), $cardPosition);
        parse_str($cardPosition, $output);

        /* If you do not have permission to access any execution, go to the create execution page. */
        if(!$this->execution->checkPriv($executionID)) $this->locate($this->createLink('execution', 'create'));

        /* Set menu and get execution information. */
        $executionID = $this->taskZen->setMenu($executionID);
        $execution   = $this->execution->getById($executionID);

        /* Check whether the execution has permission to create tasks. */
        $limitedExecutions = (string)$this->execution->getLimitedExecution();
        if(strpos(",{$limitedExecutions},", ",{$executionID},") !== false)
        {
            return $this->send(array('load' => $this->createLink('execution', 'task', "executionID={$executionID}"), 'message' => $this->lang->task->createDenied));
        }

        /* Submit the data process after create the task form. */
        if(!empty($_POST))
        {
            $task = $this->taskZen->buildTaskForCreate($executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Check whether a task with the same name is created within the specified time. */
            $duplicateTaskID = $this->taskZen->checkDuplicateName($task);
            if($duplicateTaskID) return $this->send(array('result' => 'success', 'message' => sprintf($this->lang->duplicate, $this->lang->task->common), 'load' => $this->createLink('task', 'view', "taskID={$duplicateTaskID}")));

            $this->dao->begin();
            if($this->post->type == 'test')
            {
                /* Prepare to create the data for the test subtask and to check the data format. */
                $testTasks  = $this->taskZen->buildTestTasksForCreate($task->execution);
                $taskIdList = $this->task->createTaskOfTest($task, $testTasks);
            }
            elseif($this->post->type == 'affair')
            {
                $taskIdList = $this->task->createTaskOfAffair($task, $this->post->assignedTo);
            }
            elseif($this->post->multiple)
            {
                $teamData   = form::data($config->task->form->team->create)->get();
                $taskIdList = $this->task->createMultiTask($task, $teamData);
            }
            else
            {
                $taskIdList = $this->task->create($task);
            }

            if(dao::isError())
            {
                $this->dao->rollBack();
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }
            $this->dao->commit();

            /* Update other data related to the task after it is created. */
            $columnID   = isset($output['columnID']) ? (int)$output['columnID'] : 0;
            $taskIdList = (array)$taskIdList;
            $task->id   = current($taskIdList);
            $this->task->afterCreate($task, $taskIdList, $bugID, $todoID);
            $this->task->updateKanbanData($task->execution, $taskIdList, (int)$this->post->lane, $columnID);
            helper::setcookie('lastTaskModule', (int)$this->post->module);

            /* Get the information returned after a task is created. */
            $response = $this->taskZen->responseAfterCreate($task, $execution, $this->post->after);
            return $this->send($response);
        }

        /* Shows the variables needed to create the task page. */
        $this->taskZen->assignCreateVars($execution, $storyID, $moduleID, $taskID, $todoID, $bugID, $output);
    }

    /**
     * Batch create task.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  string $iframe
     * @param  string $cardPosition
     * @access public
     * @return void
     */
    public function batchCreate(int $executionID, int $storyID = 0, int $moduleID = 0, int $taskID = 0, string $cardPosition = '')
    {
        /* Init vars. */
        $cardPosition = str_replace(array(',', ' '), array('&', ''), $cardPosition);
        parse_str($cardPosition, $output);

        /* 判断不能访问的执行。 Judge execution without access. */
        if($this->taskZen->isLimitedInExecution($executionID))
        {
            echo js::alert($this->lang->task->createDenied);
            return print(js::locate($this->createLink('execution', 'task', "executionID=$executionID")));
        }

        $execution = $this->execution->getById($executionID);

        if(!empty($_POST))
        {
            /* 批量创建任务。 Batch create tasks. */
            $taskData = $this->taskZen->buildTasksForBatchCreate($execution, $taskID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $taskIdList = $this->task->batchCreate($execution, $taskData, $taskID, $output);
            if(dao::isError()) return print(js::error(dao::getError()));

            /* 接口调用返回任务编号列表。 Return task id list when call the API. */
            if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'idList' => $taskIdList));

            /* 生成跳转链接。 Generate jump link. */
            $jumpLink = $this->taskZen->getJumpLink($execution);
            if(!isonlybody()) return print(js::locate($jumpLink, 'parent'));

            /* 执行应用下或者在运营管理界面下更新看板数据。 */
            /* Update kanban data under the execution application or under the operation management interface. */
            if($this->app->tab == 'execution' or $this->config->vision == 'lite')
            {
                $kanbanData = $this->taskZen->getKanbanData($execution);
                if($execution->type == 'kanban') return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, 0)"));
                return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
            }
            return print(js::reload('parent.parent'));
        }

        $this->taskZen->setMenu($executionID);

        $this->taskZen->buildBatchCreateForm($execution, $storyID, $moduleID, $taskID, $output);
    }

    /**
     * 编辑一个任务。
     * Edit a task.
     *
     * @param  int    $taskID
     * @param  string $from   ''|taskkanban
     * @access public
     * @return void
     */
    public function edit(int $taskID, string $from = '')
    {
        $this->taskZen->commonAction($taskID);

        if(!empty($_POST))
        {
            /* Prepare and check data. */
            $postData = form::data()->getAll(true);
            $task     = $this->taskZen->buildTaskForEdit($taskID, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Update task. */
            $changes = $this->task->update($task, $postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record log. */
            if($postData->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->loadModel('action')->create('task', $taskID, $action, $postData->comment);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            /* Execute hooks and synchronize the status of related objects. */
            $this->executeHooks($taskID);
            if($task->status == 'doing') $this->loadModel('common')->syncPPEStatus($taskID);

            $response = $this->taskZen->responseAfterEdit($taskID, $from, $changes);
            return $this->send($response);
        }

        $this->taskZen->buildEditForm($taskID);
    }

    /**
     * 批量编辑任务。
     * Batch edit task.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchEdit(int $executionID = 0)
    {
        if($this->post->names)
        {
            /* Batch edit tasks. */
            $postData   = form::data($this->config->task->form->batchEdit)->get();
            $allChanges = $this->task->batchUpdate($postData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $response = $this->taskZen->responseAfterBatchEdit($allChanges);
            return $this->send($response);
        }

        if(!$this->post->taskIDList) $this->locate($this->session->taskList);
        $taskIdList = array_unique($this->post->taskIDList);

        /* Set parameters based on whether the page is execution or my. */
        $this->taskZen->batchEdit4Pages($executionID);

        $this->taskZen->buildBatchEditForm($taskIdList, $executionID);
    }

    /**
     * 指派任务。
     * Update assign of task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $from        ''|taskkanban
     * @access public
     * @return void
     */
    public function assignTo(int $executionID, int $taskID, string $from = '')
    {
        $this->taskZen->commonAction($taskID);

        if(!empty($_POST))
        {
            $postDataFixer = form::data($this->config->task->form->assign);

            /* Assign task. */
            $task    = $this->taskZen->prepareAssignTo($postDataFixer, $taskID);
            $changes = $this->task->assign($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record log. */
            $actionID = $this->loadModel('action')->create('task', $taskID, 'Assigned', $this->post->comment, $task->assignedTo);
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);

            $response = $this->taskZen->responseAfterAssignTo($taskID, $from);
            return $this->send($response);
        }

        $this->taskZen->buildAssignToForm($executionID, $taskID);
    }

    /**
     * Batch change the module of task.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        if($this->post->taskIDList)
        {
            $taskIDList = $this->post->taskIDList;
            $taskIDList = array_unique($taskIDList);
            unset($_POST['taskIDList']);
            $allChanges = $this->task->batchChangeModule($taskIDList, $moduleID);
            if(dao::isError()) return print(js::error(dao::getError()));
            foreach($allChanges as $taskID => $changes)
            {
                $this->loadModel('action');
                $actionID = $this->action->create('task', $taskID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }
        return print(js::reload('parent'));
    }

    /**
     * 批量指派任务。
     * Batch update assign of task.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchAssignTo(int $executionID)
    {
        if(!empty($_POST))
        {
            if(!is_array($this->post->taskIDList)) return print(js::locate($this->createLink('execution', 'task', "executionID={$executionID}"), 'parent'));

            $this->loadModel('action');
            $taskData = $this->taskZen->buildTasksForBatchAssignTo($this->post->taskIDList, $this->post->assignedTo);
            foreach($taskData as $taskID => $task)
            {
                /* Assign task. */
                $changes = $this->task->assign($task);
                if(dao::isError()) return print(js::error(dao::getError()));

                /* Record log. */
                $actionID = $this->action->create('task', $taskID, 'Assigned', $this->post->comment, $this->post->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::reload('parent'));
        }
    }

    /**
     * View a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function view($taskID)
    {
        $taskID = (int)$taskID;
        $task   = $this->task->getById($taskID, true);
        if(!$task)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('execution', 'all')));
        }

        $this->session->set('executionList', $this->app->getURI(true), 'execution');

        $this->taskZen->commonAction($taskID);
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);

        $execution = $this->execution->getById($task->execution);
        if(!isonlybody() and $execution->type == 'kanban')
        {
            helper::setcookie('taskToOpen', $taskID);
            return print(js::locate($this->createLink('execution', 'kanban', "executionID=$execution->id")));
        }

        $this->session->project = $task->project;

        if($task->fromBug != 0)
        {
            $bug = $this->loadModel('bug')->getById($task->fromBug);
            $task->bugSteps = '';
            if($bug)
            {
                $task->bugSteps = $this->loadModel('file')->setImgSize($bug->steps);
                foreach($bug->files as $file) $task->files[] = $file;
            }
            $this->view->fromBug = $bug;
        }
        else
        {
            $story = $this->story->getById($task->story, $task->storyVersion);
            $task->storySpec   = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->spec);
            $task->storyVerify = empty($story) ? '' : $this->loadModel('file')->setImgSize($story->verify);
            $task->storyFiles  = zget($story, 'files', array());
        }

        if($task->team) $this->lang->task->assign = $this->lang->task->transfer;

        /* Update action. */
        if($task->assignedTo == $this->app->user->account) $this->loadModel('action')->read('task', $taskID);

        $this->executeHooks($taskID);

        $this->view->title        = "TASK#$task->id $task->name / $execution->name";
        $this->view->execution    = $execution;
        $this->view->task         = $task;
        $this->view->actions      = $this->loadModel('action')->getList('task', $taskID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->preAndNext   = $this->loadModel('common')->getPreAndNextObject('task', $taskID);
        $this->view->product      = $this->tree->getProduct($task->module);
        $this->view->modulePath   = $this->tree->getParents($task->module);
        $this->view->linkMRTitles = $this->loadModel('mr')->getLinkedMRPairs($taskID, 'task');
        $this->view->linkCommits  = $this->loadModel('repo')->getCommitsByObject($taskID, 'task');
        $this->view->methodName   = $this->methodName;
        // $this->display();
        $this->render();
    }

    /**
     * Confirm story change
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function confirmStoryChange($taskID)
    {
        $task = $this->task->getById($taskID);
        $this->dao->update(TABLE_TASK)->set('storyVersion')->eq($task->latestStoryVersion)->where('id')->eq($taskID)->exec();
        $this->loadModel('action')->create('task', $taskID, 'confirmed', '', $task->latestStoryVersion);

        $this->executeHooks($taskID);

        echo js::reload('parent');
    }

    /**
     * 开始一个任务。
     * Start a task.
     *
     * @param  int    $taskID
     * @param  string $cardPosition
     * @access public
     * @return void
     */
    public function start(int $taskID, string $cardPosition = '')
    {
        /* Analytic parameter. */
        $cardPosition = str_replace(array(',', ' '), array('&', ''), $cardPosition);
        parse_str($cardPosition, $output);

        /* Common actions of task module and task. */
        $this->taskZen->commonAction($taskID);
        $task        = $this->task->getById($taskID);
        $currentTeam = empty($task->team) ? $this->task->getTeamByAccount($task->team) : '';

        /* Submit the data process after start the task form. */
        if(!empty($_POST))
        {
            /* Prepare the data information before start the task. */
            $taskData = $this->taskZen->buildTaskForStart($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record task effort. */
            $effort = $this->buildEffortForStart($task, $taskData);
            if($this->post->comment) $effort->work = $this->post->comment;
            if($effort->consumed > 0) $effortID = $this->task->addTaskEffort($effort);
            if($task->mode == 'linear' && !empty($effortID)) $this->task->updateEstimateOrder($effortID, $currentTeam->order);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Start a task. */
            $changes = $this->task->start($task, $taskData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Update other data related to the task after it is started. */
            $result = $this->task->afterStart($task, $taskData, $changes, $this->post->left, $this->post->comment, $output);
            if(is_array($result)) $this->send($result);

            /* Get the information returned after a task is started. */
            $from     = zget($output, 'from');
            $response = $this->taskZen->responseAfterChangeStatus($task, $from);
            $this->send($response);
        }

        /* Shows the variables needed to start the task page. */
        $assignedTo = empty($task->assignedTo) ? $this->app->user->account : $task->assignedTo;

        $this->view->title           = $this->view->execution->name . $this->lang->colon .$this->lang->task->start;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->members         = $this->user->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $this->view->assignedTo      = !empty($task->team) ? $this->task->getAssignedTo4Multi($task->team, $task) : $assignedTo;
        $this->view->canRecordEffort = $this->taskZen->checkRecordEffort($task);
        $this->view->currentTeam     = $currentTeam;
        $this->display();
    }

    /**
     * Record consumed and estimate.
     *
     * @param  int    $taskID
     * @param  string $from
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function recordEstimate($taskID, $from = '', $orderBy = '')
    {
        $this->taskZen->commonAction($taskID);

        if(!empty($_POST))
        {
            $changes = $this->task->recordEstimate($taskID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $this->loadModel('common')->syncPPEStatus($taskID);

            /* Remind whether to update status of the bug, if task which from that bug has been finished. */
            $task = $this->task->getById($taskID);
            if($changes and $this->task->needUpdateBugStatus($task))
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status' and $change['new'] == 'done')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }

            if(isonlybody())
            {
                $execution     = $this->execution->getByID($task->execution);
                $executionLaneType  = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy   = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $executionLaneType, 'id_desc', 0, $executionGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::reload('parent'));
                }
                if($from == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $executionLaneType, $executionGroupBy, $taskSearchValue);
                    $kanbanType      = $executionLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::reload('parent'));
                }
                return print(js::reload('parent'));
            }
            return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
        }

        $uri = $this->app->getURI(true);
        $this->session->set('estimateList', $uri, 'execution');
        if(isonlybody()) $this->session->set('estimateList', $uri . (strpos($uri, '?') === false ? '?' : '&')  . 'onlybody=yes', 'execution');

        $task = $this->task->getById($taskID);
        if(!empty($task->team) and $task->mode == 'linear')
        {
            if(empty($orderBy))
            {
                $orderBy = 'id_desc';
            }
            else
            {
                /* The id sort with order or date style. */
                $orderBy .= preg_replace('/(order_|date_)/', ',id_', $orderBy);
            }
        }

        if(!$orderBy) $orderBy = 'id_desc';

        /* Set the fold state of the current task. */
        $referer = strtolower($_SERVER['HTTP_REFERER']);
        if(strpos($referer, 'recordestimate') and $this->cookie->taskEffortFold !== false)
        {
            $taskEffortFold = $this->cookie->taskEffortFold;
        }
        else
        {
            $taskEffortFold = 0;
            $currentAccount = $this->app->user->account;
            if($task->assignedTo == $currentAccount) $taskEffortFold = 1;
            if(!empty($task->team))
            {
                $teamMember = array_column($task->team, 'account');
                if(in_array($currentAccount, $teamMember)) $taskEffortFold = 1;
            }
        }

        $this->view->title          = $this->lang->task->record;
        $this->view->task           = $task;
        $this->view->from           = $from;
        $this->view->orderBy        = $orderBy;
        $this->view->efforts        = $this->task->getTaskEfforts($taskID, '', '', $orderBy);
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->taskEffortFold = $taskEffortFold;

        $this->display();
    }

    /**
     * Edit consumed and estimate.
     *
     * @param  int    $estimateID
     * @access public
     * @return void
     */
    public function editEstimate($estimateID)
    {
        $estimate = $this->task->getEstimateById($estimateID);
        if(!empty($_POST))
        {
            $changes = $this->task->updateEstimate($estimateID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('task', $estimate->task, 'EditEstimate', $this->post->work);
            $this->action->logHistory($actionID, $changes);

            $url = $this->session->estimateList ? $this->session->estimateList : inlink('recordEstimate', "taskID={$estimate->task}");
            return print(js::locate($url, 'parent'));
        }

        $this->view->title    = $this->lang->task->editEstimate;
        $this->view->estimate = $estimate;
        $this->view->task     = $this->task->getById($estimate->objectID);
        $this->display();
    }

    /**
     * Delete estimate.
     *
     * @param  int    $estimateID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteEstimate($estimateID, $confirm = 'no')
    {
        $estimate = $this->task->getEstimateById($estimateID);
        $taskID   = $estimate->objectID;
        $task     = $this->task->getById($taskID);
        if($confirm == 'no' and $task->consumed - $estimate->consumed != 0)
        {
            return print(js::confirm($this->lang->task->confirmDeleteEstimate, $this->createLink('task', 'deleteEstimate', "estimateID=$estimateID&confirm=yes")));
        }
        elseif($confirm == 'no' and $task->consumed - $estimate->consumed == 0)
        {
            return print(js::confirm($this->lang->task->confirmDeleteLastEstimate, $this->createLink('task', 'deleteEstimate', "estimateID=$estimateID&confirm=yes")));
        }
        else
        {
            $changes = $this->task->deleteEstimate($estimateID);
            if(dao::isError()) return print(js::error(dao::getError()));

            $actionID = $this->loadModel('action')->create('task', $taskID, 'DeleteEstimate');
            $this->action->logHistory($actionID, $changes);

            if($task->consumed - $estimate->consumed == 0)
            {
                $this->action->create('task', $taskID, 'Adjusttasktowait');
                return print(js::reload('parent.parent'));
            }

            return print(js::reload('parent'));
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
        $this->taskZen->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->finish($taskID, $extra);
            if(dao::isError())
            {
                if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return print(js::error(dao::getError()));
            }
            $files = $this->loadModel('file')->saveUpload('task', $taskID);

            $task = $this->task->getById($taskID);
            if($this->post->comment != '' or !empty($changes))
            {
                $fileAction = !empty($files) ? $this->lang->addFiles . implode(',', $files) . "\n" : '';
                $actionID = $this->action->create('task', $taskID, 'Finished', $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);
            $this->loadModel('common')->syncPPEStatus($taskID);

            if($this->task->needUpdateBugStatus($task))
            {
                foreach($changes as $change)
                {
                    if($change['field'] == 'status')
                    {
                        $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug", '', true);
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }
            }

            if(isonlybody())
            {
                $execution    = $this->execution->getByID($task->execution);
                $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project')) and $execution->type == "kanban")
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $executionLaneType, 'id_desc', $regionID, $executionGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if($output['from'] == "taskkanban")
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $executionLaneType, $executionGroupBy, $taskSearchValue);
                    $kanbanType      = $executionLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('result' => 'success', 'data' => $taskID));
            }
            else
            {
                return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $task         = $this->view->task;
        $members      = $task->team ? $this->task->getMemberPairs($task) : $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $task->nextBy = $task->openedBy;

        if(!empty($task->team))
        {
            $task->nextBy     = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $task->myConsumed = 0;
            $currentTeam      = $this->task->getTeamByAccount($task->team);
            if($currentTeam) $task->myConsumed = $currentTeam->consumed;
        }

        $this->view->title   = $this->view->execution->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->members = $members;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 暂停任务。
     * Pause task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function pause(int $taskID, string $extra = '')
    {
        $this->taskZen->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            /* Init task data. */
            $postData = form::data($this->config->task->form->pause);
            $task     = $postData->data;
            $task->id = $taskID;

            /* Pause task. */
            $changes = $this->task->pause($task, $output);
            if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

            /* Record log. */
            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('task', $taskID, 'Paused', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            /* Get response after the suspended task. */
            $from     = zget($output, 'from');
            $task     = $this->task->getById($taskID);
            $response = $this->taskZen->responseAfterChangeStatus($task, $from);
            return $this->send($response);
        }

        /* Show the variables associated. */
        $this->view->title = $this->view->execution->name . $this->lang->colon .$this->lang->task->pause;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 重新开始一个任务。
     * Restart a task.
     *
     * @param  int    $taskID
     * @param  string $from
     * @access public
     * @return void
     */
    public function restart(int $taskID, string $from = '')
    {
        /* Common actions of task module and task. */
        $this->taskZen->commonAction($taskID);
        $task        = $this->task->getById($taskID);
        $currentTeam = empty($task->team) ? $this->task->getTeamByAccount($task->team) : '';

        if(!empty($_POST))
        {
            /* Prepare the data information before restart the task. */
            $taskData = $this->taskZen->buildTaskForStart($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record task effort. */
            $effort = $this->buildEffortForStart($task, $taskData);
            if($this->post->comment) $effort->work = $this->post->comment;
            if($effort->consumed > 0) $effortID = $this->task->addTaskEffort($effort);
            if($task->mode == 'linear' && !empty($effortID)) $this->task->updateEstimateOrder($effortID, $currentTeam->order);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Restart a task. */
            $changes = $this->task->start($task, $taskData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $action   = $this->post->left == 0 ? 'Finished' : 'Restarted';
            $actionID = $this->loadModel('action')->create('task', $taskID, $action, $this->post->comment);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);
            $response = $this->taskZen->responseAfterChangeStatus($task, $from);
            $this->send($response);
        }

        $this->view->title           = $this->view->execution->name . $this->lang->colon .$this->lang->task->restart;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->members         = $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $this->view->assignedTo      = $task->assignedTo == '' ? $this->app->user->account : $task->assignedTo;
        $this->view->canRecordEffort = $this->taskZen->checkRecordEffort($task);
        $this->view->currentTeam     = $currentTeam;
        $this->display();
    }

    /**
     * Close a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function close($taskID, $extra = '')
    {
        $this->taskZen->commonAction($taskID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->task->close($taskID, $extra);

            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody())
            {
                $task      = $this->task->getById($taskID);
                $execution = $this->execution->getByID($task->execution);

                if(isset($task->fromIssue) and $task->fromIssue > 0)
                {
                    $fromIssue = $this->loadModel('issue')->getByID($task->fromIssue);
                    if($fromIssue->status != 'closed')
                    {
                        $confirmURL = $this->createLink('issue', 'close', "id=$task->fromIssue");
                        unset($_GET['onlybody']);
                        $cancelURL  = $this->createLink('task', 'view', "taskID=$taskID");
                        return print(js::confirm(sprintf($this->lang->task->remindIssue, $task->fromIssue), $confirmURL, $cancelURL, 'parent', 'parent.parent'));
                    }
                }

                $executionLaneType = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy  = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project')) and $execution->type == 'kanban')
                {
                    $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                    $regionID      = !empty($output['regionID']) ? $output['regionID'] : 0;
                    $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $executionLaneType, 'id_desc', $regionID, $executionGroupBy, $rdSearchValue);
                    $kanbanData    = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData, $regionID)"));
                }
                if($output['from'] == 'taskkanban')
                {
                    $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                    $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $executionLaneType, $executionGroupBy, $taskSearchValue);
                    $kanbanType      = $executionLaneType == 'all' ? 'task' : key($kanbanData);
                    $kanbanData      = $kanbanData[$kanbanType];
                    $kanbanData      = json_encode($kanbanData);

                    return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
                }
                return print(js::closeModal('parent.parent', 'this', "function(){parent.parent.location.reload();}"));
            }

            if(defined('RUN_MODE') && RUN_MODE == 'api')
            {
                return $this->send(array('status' => 'success', 'data' => $taskID));
            }
            else
            {
                return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
            }
        }

        $this->view->title = $this->view->execution->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Batch cancel tasks.
     *
     * @param  string $skipTaskIdList
     * @access public
     * @return void
     */
    public function batchCancel()
    {
        if($this->post->taskIDList)
        {
            $taskIDList = $this->post->taskIDList;
            $taskIDList = array_unique($taskIDList);
            unset($_POST['taskIDList']);
            unset($_POST['assignedTo']);
            $this->loadModel('action');

            $tasks = $this->task->getByList($taskIDList);
            foreach($tasks as $taskID => $task)
            {
                if($task->status == 'done' or $task->status == 'closed' or $task->status == 'cancel') continue;

                $changes = $this->task->cancel($taskID);
                if($changes)
                {
                    $actionID = $this->action->create('task', $taskID, 'Canceled', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }

        return print(js::reload('parent'));
    }

    /**
     * Batch close tasks.
     *
     * @access public
     * @return void
     */
    public function batchClose($skipTaskIdList = '')
    {
        if($this->post->taskIDList or $skipTaskIdList)
        {
            $taskIDList = $this->post->taskIDList;
            if($taskIDList)     $taskIDList = array_unique($taskIDList);
            if($skipTaskIdList) $taskIDList = $skipTaskIdList;

            unset($_POST['taskIDList']);
            unset($_POST['assignedTo']);
            $this->loadModel('action');

            $tasks = $this->task->getByList($taskIDList);
            foreach($tasks as $taskID => $task)
            {
                if(empty($skipTaskIdList) and ($task->status != 'done' and $task->status != 'cancel'))
                {
                    $skipTasks[$taskID] = $taskID;
                    continue;
                }

                /* Skip parent task when batch close task. */
                if($task->parent == '-1')
                {
                    $parentTasks[$taskID] = $taskID;
                    continue;
                }

                /* Skip closed task when batch close task. */
                if($task->status == 'closed') continue;

                $changes = $this->task->close($taskID);
                if($changes)
                {
                    $actionID = $this->action->create('task', $taskID, 'Closed', '');
                    $this->action->logHistory($actionID, $changes);
                }
            }
            if(isset($skipTasks) and empty($skipTaskIdList))
            {
                $skipTasks  = implode(',', $skipTasks);
                $confirmURL = $this->createLink('task', 'batchClose', "skipTaskIdList=$skipTasks");
                $cancelURL  = $this->server->HTTP_REFERER;
                return print(js::confirm(sprintf($this->lang->task->error->skipClose, $skipTasks), $confirmURL, $cancelURL, 'self', 'parent'));
            }

            if(isset($parentTasks))
            {
                $parentTasks = implode(',', $parentTasks);
                return print(js::alert(sprintf($this->lang->task->error->closeParent, $parentTasks)) . js::reload('parent'));
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }

        return print(js::reload('parent'));
    }

    /**
     * Cancel a task.
     *
     * @param  int    $taskID
     * @param  string $cardPosition
     * @param  string $from
     * @access public
     * @return void
     */
    public function cancel(int $taskID, string $cardPosition = '', string $from = '')
    {
        $this->taskZen->commonAction($taskID);

        $cardPosition = str_replace(array(',', ' '), array('&', ''), $cardPosition);
        parse_str($cardPosition, $output);

        if(!empty($_POST))
        {
            $this->loadModel('action');

            $postData = form::data($this->config->task->form->cancel)->get();
            $changes  = $this->task->cancel($taskID, $output['laneID']);
            if(dao::isError()) return print(js::error(dao::getError()));

            if($postData->comment != '' || !empty($changes))
            {
                $actionID = $this->action->create('task', $taskID, 'Canceled', $postData->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            $task = $this->task->getById($taskID);
            if(isonlybody()) return $this->taskZen->responseKanban($task, $from, $regionID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('task', 'view', "taskID=$taskID")));
        }

        $this->view->title = $this->view->execution->name . $this->lang->colon . $this->lang->task->cancel;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * Activate a task.
     *
     * @param  int    $taskID
     * @param  string $cardPosition
     * @param  string $from
     * @access public
     * @return void
     */
    public function activate(int $taskID, string $cardPosition = '', string $cardDrag = '', string $from = '')
    {
        $this->taskZen->commonAction($taskID);
        if($cardPosition) list($regionID) = $cardPosition;

        if(!empty($_POST))
        {
            $taskData = form::data($this->config->task->form->acivate);
            $teamData = form::data($this->config->task->form->team->edit)->get();

            $task     = $this->taskZen->prepareActivate($taskData, $taskID);
            $changes  = $this->task->activate($task, $this->post->comment, $teamData, explode(',', $cardDrag));
            if(dao::isError()) return print(js::error(dao::getError()));

            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('task', $taskID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            if(isonlybody())
            {
                $task     = $this->task->getById($taskID);
                $regionID = !empty($regionID) ? (int)$regionID : 0;
                return $this->taskZen->responseKanban($task, $from, $regionID);
            }
            return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('task', 'view', "taskID=$taskID"), 'closeModal' => true);
        }

        if(!isset($this->view->members[$this->view->task->finishedBy])) $this->view->members[$this->view->task->finishedBy] = $this->view->task->finishedBy; // Ensure that the completion person is on the user list.

        /* Get task teammembers. */
        if(!empty($this->view->task->team))
        {
            $teamAccounts = array_column($this->view->task->team, 'account');
            $teamMembers  = array();
            foreach($this->view->members as $account => $name)
            {
                if(!$account or in_array($account, $teamAccounts)) $teamMembers[$account] = $name;
            }
            $this->view->teamMembers = $teamMembers;
        }

        $this->view->title = $this->view->execution->name . $this->lang->colon . $this->lang->task->activate;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Delete a task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $confirm yes|no
     * @param  string $from taskkanban
     * @access public
     * @return void
     */
    public function delete($executionID, $taskID, $confirm = 'no', $from = '')
    {
        $task = $this->task->getById($taskID);
        if($task->parent < 0) return print(js::alert($this->lang->task->cannotDeleteParent));

        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->task->confirmDelete, inlink('delete', "executionID=$executionID&taskID=$taskID&confirm=yes&from=$from")));
        }
        else
        {
            $this->task->delete(TABLE_TASK, $taskID);
            if($task->parent > 0)
            {
                $this->task->updateParentStatus($task->id);
                $this->loadModel('action')->create('task', $task->parent, 'deleteChildrenTask', '', $taskID);
            }
            if($task->fromBug != 0) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($task->fromBug)->exec();
            if($task->story) $this->loadModel('story')->setStage($task->story);

            $this->executeHooks($taskID);

            if(isonlybody()) return print(js::reload('parent.parent'));
            if($from == 'taskkanban')
            {
                $executionLaneType    = $this->session->executionLaneType ? $this->session->executionLaneType : 'all';
                $executionGroupBy     = $this->session->executionGroupBy ? $this->session->executionGroupBy : 'default';
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($this->session->execution, $executionLaneType, $executionGroupBy, $taskSearchValue);
                $kanbanType      = $executionLaneType == 'all' ? 'task' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);
                return print(js::closeModal('parent', '', "parent.updateKanban(\"task\", $kanbanData)"));
            }

            $locateLink = $this->session->taskList ? $this->session->taskList : $this->createLink('execution', 'task', "executionID={$task->execution}");
            return print(js::locate($locateLink, 'parent'));
        }
    }

    /**
     * AJAX: return tasks of a user in html select.
     *
     * @param  int    $userID
     * @param  string $id
     * @param  string $status
     * @param  int    $appendID
     * @access public
     * @return string
     */
    public function ajaxGetUserTasks($userID = '', $id = '', $status = 'wait,doing', $appendID = 0)
    {
        if($userID == '') $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $tasks          = $this->task->getUserTaskPairs($account, $status, '', $appendID);
        $suspendedTasks = $this->task->getUserSuspendedTasks($account);
        foreach($tasks as $taskid => $task)
        {
            if(isset($suspendedTasks[$taskid])) unset($tasks[$taskid]);
        }

        if($id) return print(html::select("tasks[$id]", $tasks, '', 'class="form-control"'));
        echo html::select('task', $tasks, '', 'class=form-control');
    }

    /**
     * AJAX: return execution tasks in html select.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function ajaxGetExecutionTasks($executionID, $taskID = 0)
    {
        $tasks = $this->task->getExecutionTaskPairs((int)$executionID);
        echo html::select('task', empty($tasks) ? array('' => '') : $tasks, $taskID, "class='form-control'");
    }

    /**
     * Ajax get tasks for execution list.
     *
     * @param  int    $executionID
     * @param  int    $maxTaskID
     * @access public
     * @return void
     */
    public function ajaxGetTasks($executionID, $maxTaskID = 0)
    {
        $this->loadModel('task');
        $this->loadModel('execution');
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

        $tasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq('0')
            ->andWhere('status')->notin('closed,cancel')
            ->andWhere('parent')->le('0')
            ->andWhere('execution')->eq($executionID)
            ->andWhere('id')->gt($maxTaskID)
            ->orderBy('id_asc')
            ->limit(50)
            ->fetchAll('id');

        if(empty($tasks)) return print('');

        $parentGroup = $this->dao->select('*')->from(TABLE_TASK)
            ->where('parent')->in(array_keys($tasks))
            ->andWhere('parent')->gt('0')
            ->andWhere('deleted')->eq('0')
            ->fetchGroup('parent', 'id');

        $users = $this->loadModel('user')->getPairs('noletter|nodeleted');
        foreach($tasks as $taskID => $task)
        {
            if(isset($parentGroup[$taskID]))
            {
                $tasks[$taskID]->children = $parentGroup[$taskID];
            }
            else
            {
                $tasks[$taskID]->children = array();
            }
        }

        $list  = '';
        $count = count($tasks);
        foreach($tasks as $task)
        {
            $showmore = ($count == 50) && ($task == end($tasks));
            $list    .= $this->task->buildNestedList($execution, $task, false, $showmore, $users);
        }

        return print($list);
    }

    /**
     * AJAX: get the actions of a task. for web app.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function ajaxGetDetail($taskID)
    {
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);
        $this->display();
    }

    /**
     * The report page.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function report($executionID, $browseType = 'all', $chartType = 'default')
    {
        $this->loadModel('report');
        $this->view->charts   = array();

        if(!empty($_POST))
        {
            foreach($this->post->charts as $chart)
            {
                $chartFunc   = 'getDataOf' . $chart;
                $chartData   = $this->task->$chartFunc();
                $chartOption = $this->lang->task->report->$chart;
                if(!empty($chartType) and $chartType != 'default') $chartOption->type = $chartType;
                $this->task->mergeChartOption($chart);
                $this->view->charts[$chart] = $chartOption;
                $this->view->datas[$chart]  = $this->report->computePercent($chartData);
            }
        }

        $execution = $this->loadModel('execution')->getByID($executionID);
        if(!$execution->multiple) unset($this->lang->task->report->charts['tasksPerExecution']);


        $this->execution->setMenu($executionID);
        $this->executions          = $this->execution->getPairs();
        $this->view->title         = $this->executions[$executionID] . $this->lang->colon . $this->lang->task->report->common;
        $this->view->executionID   = $executionID;
        $this->view->browseType    = $browseType;
        $this->view->chartType     = $chartType;
        $this->view->checkedCharts = $this->post->charts ? implode(',', $this->post->charts) : '';
        $this->display();
    }

    /**
     * get data to export
     *
     * @param  int $executionID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($executionID, $orderBy, $type)
    {
        $execution       = $this->execution->getById($executionID);
        $allExportFields = $this->config->task->exportFields;
        if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))) $allExportFields = str_replace(' story,', '', $allExportFields);

        if($_POST)
        {
            $this->loadModel('file');
            $taskLang = $this->lang->task;

            /* Create field lists. */
            $sort   = common::appendOrder($orderBy);
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $allExportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($taskLang->$fieldName) ? $taskLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get tasks. */
            $tasks = array();
            if($this->session->taskOnlyCondition)
            {
                $tasks = $this->dao->select('*')->from(TABLE_TASK)->alias('t1')->where($this->session->taskQueryCondition)
                    ->beginIF($this->post->exportType == 'selected')->andWhere('t1.id')->in($this->cookie->checkedItem)->fi()
                    ->orderBy($sort)->fetchAll('id');

                foreach($tasks as $key => $task)
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
                    $task->progress .= '%';
                }
            }
            elseif($this->session->taskQueryCondition)
            {
                $stmt = $this->dbh->query($this->session->taskQueryCondition . ($this->post->exportType == 'selected' ? " AND t1.id IN({$this->cookie->checkedItem})" : '') . " ORDER BY " . strtr($orderBy, '_', ' '));
                while($row = $stmt->fetch()) $tasks[$row->id] = $row;
            }

            /* Get users and executions. */
            $users      = $this->loadModel('user')->getPairs('noletter');
            $executions = $this->execution->getPairs($execution->project, 'all', 'all|nocode');

            /* Get related objects id lists. */
            $relatedStoryIdList  = array();
            foreach($tasks as $task)
            {
                $relatedStoryIdList[$task->story] = $task->story;
                $relatedBugIdList[$task->fromBug] = $task->fromBug;
            }

            /* Get team for multiple task. */
            $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');

            /* Process multiple task info. */
            if(!empty($taskTeam))
            {
                foreach($taskTeam as $taskID => $team)
                {
                    $tasks[$taskID]->team     = $team;
                    $tasks[$taskID]->estimate = '';
                    $tasks[$taskID]->left     = '';
                    $tasks[$taskID]->consumed = '';

                    foreach($team as $userInfo)
                    {
                        $tasks[$taskID]->estimate .= zget($users, $userInfo->account) . ':' . $userInfo->estimate . "\n";
                        $tasks[$taskID]->left     .= zget($users, $userInfo->account) . ':' . $userInfo->left . "\n";
                        $tasks[$taskID]->consumed .= zget($users, $userInfo->account) . ':' . $userInfo->consumed . "\n";
                    }
                }
            }

            /* Get related objects title or names. */
            $relatedStories = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($relatedStoryIdList)->fetchPairs();
            $relatedFiles   = $this->dao->select('id, objectID, pathname, title')->from(TABLE_FILE)->where('objectType')->eq('task')->andWhere('objectID')->in(array_keys($tasks))->andWhere('extra')->ne('editor')->fetchGroup('objectID');
            $relatedModules = $this->loadModel('tree')->getAllModulePairs('task');

            if($tasks)
            {
                $children = array();
                foreach($tasks as $task)
                {
                    if($task->parent > 0 and isset($tasks[$task->parent]))
                    {
                        $children[$task->parent][$task->id] = $task;
                        unset($tasks[$task->id]);
                    }
                }
                if(!empty($children))
                {
                    $position = 0;
                    foreach($tasks as $task)
                    {
                        $position ++;
                        if(isset($children[$task->id]))
                        {
                            array_splice($tasks, $position, 0, $children[$task->id]);
                            $position += count($children[$task->id]);
                        }
                    }
                }
            }

            if($type == 'group')
            {
                $stories    = $this->loadModel('story')->getExecutionStories($executionID);
                $groupTasks = array();
                foreach($tasks as $task)
                {
                    $task->storyTitle = isset($stories[$task->story]) ? $stories[$task->story]->title : '';

                    if(!isset($task->team))
                    {
                        $groupTasks[$task->$orderBy][] = $task;
                        continue;
                    }

                    if($orderBy == 'finishedBy') $task->consumed = $task->estimate = $task->left = 0;
                    foreach($task->team as $team)
                    {
                        if($orderBy == 'finishedBy' and $team->left != 0)
                        {
                            $task->estimate += $team->estimate;
                            $task->consumed += $team->consumed;
                            $task->left     += $team->left;
                            continue;
                        }

                        $cloneTask = clone $task;
                        $cloneTask->estimate = $team->estimate;
                        $cloneTask->consumed = $team->consumed;
                        $cloneTask->left     = $team->left;
                        if($team->left == 0) $cloneTask->status = 'done';

                        if($orderBy == 'assignedTo')
                        {
                            $cloneTask->assignedToRealName = zget($users, $team->account);
                            $cloneTask->assignedTo = $team->account;
                        }
                        if($orderBy == 'finishedBy')$cloneTask->finishedBy = $team->account;
                        $groupTasks[$team->account][] = $cloneTask;
                    }
                    if(!empty($task->left) and $orderBy == 'finishedBy') $groupTasks[$task->finishedBy][] = $task;
                }

                $tasks = array();
                foreach($groupTasks as $groupTask)
                {
                    foreach($groupTask as $task)$tasks[] = $task;
                }
            }

            $bugs = $this->loadModel('bug')->getByIdList($relatedBugIdList);
            foreach($tasks as $task)
            {
                if($this->post->fileType == 'csv')
                {
                    $task->desc = htmlspecialchars_decode($task->desc);
                    $task->desc = str_replace("<br />", "\n", $task->desc);
                    $task->desc = str_replace('"', '""', $task->desc);
                    $task->desc = str_replace('&nbsp;', ' ', $task->desc);
                }

                /* fill some field with useful value. */
                $task->story = isset($relatedStories[$task->story]) ? $relatedStories[$task->story] . "(#$task->story)" : '';

                $task->fromBug = empty($task->fromBug) ? '' : "#$task->fromBug " . $bugs[$task->fromBug]->title;

                if(isset($executions[$task->execution]))              $task->execution    = $executions[$task->execution] . "(#$task->execution)";
                if(isset($taskLang->typeList[$task->type]))           $task->type         = $taskLang->typeList[$task->type];
                if(isset($taskLang->priList[$task->pri]))             $task->pri          = $taskLang->priList[$task->pri];
                if(isset($taskLang->statusList[$task->status]))       $task->status       = $this->processStatus('task', $task);
                if(isset($taskLang->reasonList[$task->closedReason])) $task->closedReason = $taskLang->reasonList[$task->closedReason];
                if(isset($relatedModules[$task->module]))             $task->module       = $relatedModules[$task->module] . "(#$task->module)";
                if(isset($taskLang->modeList[$task->mode]))           $task->mode         = $taskLang->modeList[$task->mode];

                if(isset($users[$task->openedBy]))     $task->openedBy     = $users[$task->openedBy];
                if(isset($users[$task->assignedTo]))   $task->assignedTo   = $users[$task->assignedTo];
                if(isset($users[$task->finishedBy]))   $task->finishedBy   = $users[$task->finishedBy];
                if(isset($users[$task->canceledBy]))   $task->canceledBy   = $users[$task->canceledBy];
                if(isset($users[$task->closedBy]))     $task->closedBy     = $users[$task->closedBy];
                if(isset($users[$task->lastEditedBy])) $task->lastEditedBy = $users[$task->lastEditedBy];

                /* Convert username to real name. */
                if(!empty($task->mailto))
                {
                    $mailtoList = explode(',', $task->mailto);

                    $task->mailto = '';
                    foreach($mailtoList as $mailto)
                    {
                        if(!empty($mailto)) $task->mailto .= ',' . zget($users, $mailto);
                    }
                }

                if($task->parent > 0 && strpos($task->name, htmlentities('>')) !== 0) $task->name = '>' . $task->name;
                if(!empty($task->team))   $task->name = '[' . $taskLang->multipleAB . '] ' . $task->name;

                $task->openedDate     = substr($task->openedDate,     0, 10);
                $task->assignedDate   = substr($task->assignedDate,   0, 10);
                $task->finishedDate   = substr($task->finishedDate,   0, 10);
                $task->canceledDate   = substr($task->canceledDate,   0, 10);
                $task->closedDate     = substr($task->closedDate,     0, 10);
                $task->lastEditedDate = substr($task->lastEditedDate, 0, 10);
                $task->estimate       = $task->estimate . $this->lang->execution->workHourUnit;
                $task->consumed       = $task->consumed . $this->lang->execution->workHourUnit;
                $task->left           = $task->left     . $this->lang->execution->workHourUnit;

                /* Set related files. */
                $task->files = '';
                if(isset($relatedFiles[$task->id]))
                {
                    foreach($relatedFiles[$task->id] as $file)
                    {
                        $fileURL = common::getSysURL() . $this->createLink('file', 'download', "fileID={$file->id}");
                        $task->files .= html::a($fileURL, $file->title, '_blank') . '<br />';
                    }
                }
            }
            if($this->config->edition != 'open') list($fields, $tasks) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $tasks);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $tasks);
            $this->post->set('kind', 'task');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $this->app->loadLang('execution');
        $fileName      = $this->lang->task->common;
        $executionName = $this->dao->findById($executionID)->from(TABLE_PROJECT)->fetch('name');
        if(isset($this->lang->execution->featureBar['task'][$type]))
        {
            $browseType = $this->lang->execution->featureBar['task'][$type];
        }
        else
        {
            $browseType = isset($this->lang->execution->statusSelects[$type]) ? $this->lang->execution->statusSelects[$type] : '';
        }

        $this->view->fileName        = $executionName . $this->lang->dash . $browseType . $fileName;
        $this->view->allExportFields = $allExportFields;
        $this->view->customExport    = true;
        $this->view->orderBy         = $orderBy;
        $this->view->type            = $type;
        $this->view->executionID     = $executionID;
        $this->display();
    }

    /**
     * Ajax get task by ID.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function ajaxGetByID($taskID)
    {
        $task       = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        $realname   = $this->dao->select('*')->from(TABLE_USER)->where('account')->eq($task->assignedTo)->fetch('realname');
        $assignedTo = $task->assignedTo == 'closed' ? 'Closed' : $task->assignedTo;

        $task->assignedTo = $realname ? $realname : $assignedTo;
        if($task->story)
        {
            $this->app->loadLang('story');
            $stage = $this->dao->select('*')->from(TABLE_STORY)->where('id')->eq($task->story)->andWhere('version')->eq($task->storyVersion)->fetch('stage');
            $task->storyStage = zget($this->lang->story->stageList, $stage);
        }
        echo json_encode($task);
    }

    /**
     * 管理多人任务的团队。
     * Update assign of multi task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @param  string $from        ''|taskkanban
     * @access public
     * @return void
     */
    public function manageTeam(int $executionID, int $taskID, string $from = '')
    {
        $this->taskZen->commonAction($taskID);

        if(!empty($_POST))
        {
            /* Update assign of multi task. */
            $postData = form::data($this->config->task->form->manageTeam);
            $task     = $this->taskZen->prepareManageTeam($postData, $taskID);
            $changes  = $this->task->updateTeam($task, $this->post->team, $this->post->teamSource, $this->post->teamEstimate, $this->post->teamConsumed, $this->post->teamLeft);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record log. */
            $actionID = $this->loadModel('action')->create('task', $taskID, 'Edited');
            $this->action->logHistory($actionID, $changes);

            $this->executeHooks($taskID);

            $response = $this->taskZen->responseAfterAssignTo($taskID, $from);
            return $this->send($response);
        }

        $this->view->members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }
}
