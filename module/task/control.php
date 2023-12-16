<?php
declare(strict_types=1);
/**
 * The control file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: control.php 5106 2013-07-12 01:28:54Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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
        if($this->taskZen->isLimitedInExecution($executionID)) return $this->send(array('load' => array('locate' => $this->createLink('execution', 'task', "executionID={$executionID}"), 'alert' => $this->lang->task->createDenied)));

        /* Submit the data process after create the task form. */
        if(!empty($_POST))
        {
            $taskData = $this->taskZen->buildTaskForCreate($this->post->execution ? (int)$this->post->execution : $executionID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Check whether a task with the same name is created within the specified time. */
            $duplicateTaskID = $this->taskZen->checkDuplicateName($taskData);
            if($duplicateTaskID) return $this->send(array('result' => 'success', 'message' => sprintf($this->lang->duplicate, $this->lang->task->common), 'load' => $this->createLink('task', 'view', "taskID={$duplicateTaskID}")));

            $this->dao->begin();
            if($this->post->type == 'test' && $this->post->selectTestStory)
            {
                /* Prepare to create the data for the test subtask and to check the data format. */
                $testTasks  = $this->taskZen->buildTestTasksForCreate($taskData->execution);
                $taskIdList = $this->task->createTaskOfTest($taskData, $testTasks);
            }
            elseif($this->post->type == 'affair')
            {
                $taskIdList = $this->task->createTaskOfAffair($taskData, $this->post->assignedTo);
            }
            elseif($this->post->multiple)
            {
                $teamData   = form::data($this->config->task->form->team->create)->get();
                $taskIdList = $this->task->createMultiTask($taskData, $teamData);
            }
            else
            {
                $taskIdList = $this->task->create($taskData);
            }

            if(dao::isError())
            {
                $this->dao->rollBack();
                return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }
            $this->dao->commit();

            /* Update other data related to the task after it is created. */
            $columnID     = isset($output['columnID']) ? (int)$output['columnID'] : 0;
            $taskIdList   = (array)$taskIdList;
            $taskData->id = current($taskIdList);
            $this->task->afterCreate($taskData, $taskIdList, $bugID, $todoID);
            $this->task->updateKanbanData($taskData->execution, $taskIdList, (int)$this->post->lane, $columnID);
            helper::setcookie('lastTaskModule', $this->post->module);

            /* Get the information returned after a task is created. */
            $response = $this->taskZen->responseAfterCreate($taskData, $execution, $this->post->after ? $this->post->after : '');
            return $this->send($response);
        }

        /* Shows the variables needed to create the task page. */
        $this->taskZen->assignCreateVars($execution, $storyID, $moduleID, $taskID, $todoID, $bugID, $output);
    }

    /**
     * 批量创建任务。
     * Batch create tasks.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  string $cardPosition
     * @access public
     * @return void
     */
    public function batchCreate(int $executionID, int $storyID = 0, int $moduleID = 0, int $taskID = 0, string $cardPosition = '')
    {
        /* Analytic parameter. */
        $cardPosition = str_replace(array(',', ' '), array('&', ''), $cardPosition);
        parse_str($cardPosition, $output);

        /* Check whether the execution has permission to create tasks. */
        if($this->taskZen->isLimitedInExecution($executionID)) return $this->send(array('load' => array('locate' => $this->createLink('execution', 'task', "executionID={$executionID}"), 'alert' => $this->lang->task->createDenied)));

        $execution = $this->execution->getById($executionID);

        if(!empty($_POST))
        {
            /* Process the request data for the batch create tasks. */
            $taskData = $this->taskZen->buildTasksForBatchCreate($execution, $taskID, $output);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $taskIdList = $this->task->batchCreate($taskData, $output);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Update other data related to the task after it is created. */
            $this->task->afterBatchCreate($taskIdList, $taskID);
            if(!isset($output['laneID']) || !isset($output['columnID'])) $this->loadModel('kanban')->updateLane($executionID, 'task');

            $response = $this->taskZen->responseAfterbatchCreate($taskIdList, $execution);
            return $this->send($response);
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
            $task = form::data($this->config->task->form->edit)->add('id', $taskID)->get();
            $task = $this->taskZen->buildTaskForEdit($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* For team task. */
            $teamData = $this->post->team ? form::data($this->config->task->form->team->edit)->get() : new stdclass();

            /* Update task. */
            $changes = $this->task->update($task, $teamData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

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
     * Batch edit tasks.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchEdit(int $executionID = 0)
    {
        if($this->post->name)
        {
            /* Batch edit tasks. */
            $taskData = $this->taskZen->buildTasksForBatchEdit();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $allChanges = $this->task->batchUpdate($taskData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->task->afterBatchUpdate($taskData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $response = $this->taskZen->responseAfterBatchEdit($allChanges);
            return $this->send($response);
        }

        if(!$this->post->taskIdList)
        {
            $url = !empty($this->session->taskList) ? $this->session->taskList : $this->createLink('execution', 'all');
            $this->locate($url);
        }

        $this->taskZen->assignBatchEditVars($executionID);
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

        $task = $this->task->getByID($taskID);
        if(!empty($task->team) && $task->mode == 'multi' && strpos('done,cencel,closed', $task->status) === false)
        {
            echo $this->fetch('task', 'manageTeam', "executionID=$executionID&taskID=$taskID&from=$from");
            return;
        }

        if(!empty($_POST))
        {
            $task = form::data($this->config->task->form->assign)
                ->add('id', $taskID)
                ->get();

            $this->task->assign($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($taskID);

            $response = $this->taskZen->responseAfterAssignTo($taskID, $from);
            return $this->send($response);
        }

        $this->taskZen->buildAssignToForm($executionID, $taskID);
    }

    /**
     * 批量更改任务所属模块。
     * Batch change the module of task.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule(int $moduleID)
    {
        if($this->post->taskIdList)
        {
            $taskIdList = array_unique($this->post->taskIdList);
            $this->task->batchChangeModule($taskIdList, $moduleID);

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 批量指派任务。
     * Batch update assign of task.
     *
     * @param  int    $executionID
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function batchAssignTo(int $executionID, string $assignedTo)
    {
        if($this->post->taskIdList)
        {
            $taskData = $this->taskZen->buildTasksForBatchAssignTo($this->post->taskIdList, $assignedTo);
            foreach($taskData as $task)
            {
                $this->task->assign($task);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('execution', 'task', "executionID={$executionID}")));
    }

    /**
     * 查看一个任务。View a task.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function view(int $taskID)
    {
        $task = $this->task->getById($taskID, true);

        if(!$task)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return $this->send(array('result' => 'fail', 'message' => $this->lang->notFound, 'load' => $this->createLink('execution', 'all')));
        }
        if(!$this->loadModel('common')->checkPrivByObject('execution', $task->execution)) return print(js::error($this->lang->execution->accessDenied) . js::locate($this->createLink('execution', 'all')));

        /* 为视图设置常用的公共变量和设置菜单为任务所属执行. Set common variables to view and set menu to the execution of the task. */
        $this->taskZen->commonAction($taskID);

        /* 如果当前主导航是项目，则设置菜单为会话中保存的项目. Set menu to project which saved in session if current app tab is project. */
        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($this->session->project);
        $this->session->project = $task->project;

        $this->session->set('executionList', $this->app->getURI(true), 'execution'); // This allow get var of session as `$_SESSION['app-execution']['executionList']`.

        $execution = $this->view->execution ?? $this->execution->getById($task->execution);
        if(!helper::isAjaxRequest('modal') && $execution->type == 'kanban')
        {
            helper::setcookie('taskToOpen', (string)$taskID);
            return $this->send(array('load' => $this->createLink('execution', 'kanban', "executionID=$execution->id")));
        }

        /* 检查和设置任务的相关信息如果它来自缺陷或需求。Check and set related info if the task came from bug or story. */
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
            $task->storyTitle  = !empty($story) ? $story->title : '';
        }

        $task->linkedBranch = $this->task->getLinkedBranch($taskID);
        if($task->team) $this->lang->task->assign = $this->lang->task->transfer;

        /* Execute workflow hooks if edition is not open. */
        if($this->config->edition != 'open') $this->executeHooks($taskID);

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
        $this->display();
    }

    /**
     * 确认需求变更。
     * Confirm story change.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function confirmStoryChange(int $taskID)
    {
        $task = $this->task->getByID($taskID);
        $this->dao->update(TABLE_TASK)->set('storyVersion')->eq($task->latestStoryVersion)->where('id')->eq($taskID)->exec();
        $this->loadModel('action')->create('task', $taskID, 'confirmed', '', $task->latestStoryVersion);

        $this->executeHooks($taskID);

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
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
        $currentTeam = !empty($task->team) ? $this->task->getTeamByAccount($task->team) : '';

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
            if($task->mode == 'linear' && !empty($effortID)) $this->task->updateEffortOrder($effortID, $currentTeam->order);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Start a task. */
            $changes = $this->task->start($task, $taskData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Update other data related to the task after it is started. */
            $result = $this->task->afterStart($task, $changes, (float)$this->post->left, $output);
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
        $this->view->canRecordEffort = $this->task->canOperateEffort($task);
        $this->view->currentTeam     = $currentTeam;
        $this->display();
    }

    /**
     * 任务查看工时/新增工时的方法。
     * View and add task's workhour.
     *
     * @param  int    $taskID
     * @param  string $from
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function recordWorkhour(int $taskID, string $from = '', string $orderBy = '')
    {
        $this->taskZen->commonAction($taskID);

        if(!empty($_POST))
        {
            $workhour = form::batchData($this->config->task->form->recordWorkhour)->get();
            $changes  = $this->task->recordWorkhour($taskID, $workhour);
            if(dao::isError()) return $this->send(array('message' => dao::getError(), 'result' => 'fail'));

            /* 更新任务对应的项目集、项目和执行的状态。*/
            $this->loadModel('common')->syncPPEStatus($taskID);

            $task     = $this->task->getById($taskID);
            $response = $this->taskZen->responseAfterRecord($task, $changes, $from);
            return $this->send($response);
        }

        $this->taskZen->buildRecordForm($taskID, $from, $orderBy);
    }

    /**
     * 编辑一条日志。
     * Edit a effort.
     *
     * @param  int    $effortID
     * @access public
     * @return void
     */
    public function editEffort(int $effortID)
    {
        $effort = $this->task->getEffortByID($effortID);
        if(!empty($_POST))
        {
            $formData = form::data($this->config->task->form->editEffort)->add('id', $effortID)->get();
            $changes  = $this->task->updateEffort($formData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('task', $effort->objectID, 'EditEffort', $this->post->work);
            $this->action->logHistory($actionID, $changes);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title  = $this->lang->task->editEffort;
        $this->view->effort = $effort;
        $this->view->task   = $this->task->getByID($effort->objectID);
        $this->display();
    }

    /**
     * 删除任务工时。Delete the work hour from the task.
     *
     * @param  int    $effortID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteWorkhour(int $effortID, string $confirm = 'no')
    {
        $effort = $this->task->getEffortByID($effortID);
        $taskID = $effort->objectID;
        $task   = $this->task->getById($taskID);

        /* Show a confirm message if the task has no consumed effort. */
        if($confirm == 'no' && $task->consumed - $effort->consumed == 0)
        {
            $formUrl = $this->createLink('task', 'deleteWorkhour', "effortID=$effortID&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm('{$this->lang->task->confirmDeleteLastEffort}').then((res) => {if(res) $.ajaxSubmit({url: '$formUrl'});});"));
        }

        $changes = $this->task->deleteWorkhour($effortID);
        if(dao::isError() || empty($changes)) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $actionID = $this->loadModel('action')->create('task', $taskID, 'DeleteEstimate');
        $this->action->logHistory($actionID, $changes);

        /* The task status will be set to wait as the consumed effort is set to 0. */
        if($task->consumed - $effort->consumed == 0) $this->action->create('task', $taskID, 'Adjusttasktowait');

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
    }

    /**
     * 完成任务操作。
     * Finish a task.
     *
     * @param  int    $taskID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function finish(int $taskID, string $extra = '')
    {
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $this->taskZen->commonAction($taskID);
        $task        = $this->view->task;
        $currentTeam = !empty($task->team) ? $this->task->getTeamByAccount($task->team) : '';

        if(!empty($_POST))
        {
            $taskData = $this->taskZen->buildTaskForFinish($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Get and record estimate for task. */
            $effort = $this->taskZen->buildEffortForFinish($task, $taskData);
            if($effort->consumed > 0) $effortID = $this->task->addTaskEffort($effort);
            if($task->mode == 'linear' && !empty($effortID)) $this->task->updateEffortOrder($effortID, $currentTeam->order);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $changes = $this->task->finish($task, $taskData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Update other data related to the task after it is started. */
            $result = $this->task->afterStart($task, $changes, 0, $output);
            if(is_array($result)) $this->send($result);

            /* Get the information returned after a task is started. */
            $from     = zget($output, 'from');
            $response = $this->taskZen->responseAfterChangeStatus($task, $from);
            return $this->send($response);
        }

        $task         = $this->view->task;
        $members      = $task->team ? $this->task->getMemberPairs($task) : $this->loadModel('user')->getTeamMemberPairs($task->execution, 'execution', 'nodeleted');
        $task->nextBy = $task->openedBy;

        if(!empty($task->team))
        {
            $task->nextBy     = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $task->myConsumed = zget($currentTeam, 'consumed', 0);
        }

        $this->view->title           = $this->view->execution->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->members         = $members;
        $this->view->users           = $this->loadModel('user')->getPairs('noletter');
        $this->view->canRecordEffort = $this->task->canOperateEffort($task);
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
            $oldTask = $this->task->getByID($taskID);

            /* Init task data. */
            $task = form::data($this->config->task->form->pause)->get();
            $task->id = $taskID;

            /* Pause task. */
            $changes = $this->task->pause($task, $output);
            if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

            /* Record log. */
            if($this->post->comment != '' || !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('task', $taskID, 'Paused', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            /* Get response after the suspended task. */
            $from     = zget($output, 'from');
            $response = $this->taskZen->responseAfterChangeStatus($oldTask, $from);
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
        $currentTeam = !empty($task->team) ? $this->task->getTeamByAccount($task->team) : '';

        if(!empty($_POST))
        {
            /* Prepare the data information before restart the task. */
            $taskData = $this->taskZen->buildTaskForStart($task);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            /* Record task effort. */
            $effort = $this->taskZen->buildEffortForStart($task, $taskData);
            if($this->post->comment) $effort->work = $this->post->comment;
            if($effort->consumed > 0) $effortID = $this->task->addTaskEffort($effort);
            if($task->mode == 'linear' && !empty($effortID)) $this->task->updateEffortOrder($effortID, $currentTeam->order);
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
        $this->view->canRecordEffort = $this->task->canOperateEffort($task);
        $this->view->currentTeam     = $currentTeam;
        $this->display();
    }

    /**
     * 关闭一个任务。
     * Close a task.
     *
     * @param  int    $taskID
     * @param  string $cardPosition
     * @access public
     * @return void
     */
    public function close(int $taskID, string $cardPosition = '')
    {
        $cardPosition = str_replace(array(',', ' '), array('&', ''), $cardPosition);
        parse_str($cardPosition, $output);

        $this->taskZen->commonAction($taskID);

        if(!empty($_POST))
        {
            $task = $this->task->getById($taskID);

            /* Prepare the data information before start the task. */
            $taskData = $this->taskZen->buildTaskForClose($task);
            $result   = $this->task->close($task, $taskData, $output);
            if(!$result) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($taskID);
            if(is_array($result)) return $this->send($result);

            /* Get the information returned after a task is started. */
            $from     = zget($output, 'from');
            $response = $this->taskZen->responseAfterChangeStatus($task, $from);
            return $this->send($response);
        }

        $this->view->title = $this->view->execution->name . $this->lang->colon .$this->lang->task->finish;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * 批量取消任务。
     * Batch cancel tasks.
     *
     * @access public
     * @return void
     */
    public function batchCancel()
    {
        if($this->post->taskIdList)
        {
            $taskIdList = array_unique($this->post->taskIdList);

            $tasks = $this->task->getByIdList($taskIdList);
            foreach($tasks as $task)
            {
                if(!in_array($task->status, $this->config->task->unfinishedStatus)) continue;

                $task = $this->taskZen->buildTaskForCancel($task);
                $this->task->cancel($task);
            }
        }

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * 批量关闭任务。
     * Batch close tasks.
     *
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function batchClose(string $confirm = 'no')
    {
        $skipTasks      = array();
        $parentTasks    = array();
        $taskIdList     = $this->post->taskIdList ? array_unique($this->post->taskIdList) : array();
        if(!empty($taskIdList) || $confirm == 'yes')
        {
            if(!isset($_POST['taskIdList']) && !empty($_SESSION['batchCloseTaskIDList']))
            {
                $taskIdList = explode(',', $this->session->batchCloseTaskIDList);
                unset($_SESSION['batchCloseTaskIDList']);
            }
            if($taskIdList) $taskIdList = array_unique($taskIdList);

            $tasks = $this->task->getByIdList($taskIdList);
            foreach($tasks as $taskID => $task)
            {
                if($task->status == 'closed') continue;

                if($confirm == 'no' && !in_array($task->status, array('done', 'cancel')))
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

                $taskData = $this->taskZen->buildTaskForClose($task);
                $this->task->close($task, $taskData);
            }

            if(!dao::isError()) $this->loadModel('score')->create('ajax', 'batchOther');
        }

        return $this->send($this->taskZen->responseAfterBatchClose($skipTasks, $parentTasks, $confirm));
    }

    /**
     * 取消一个任务。
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

            $oldTask = $this->task->getByID($taskID);
            $task    = $this->taskZen->buildTaskForCancel($oldTask);
            $laneID  = isset($output['laneID']) ? $output['laneID'] : '';
            $this->task->cancel($task, (string)$laneID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->executeHooks($taskID);

            if(helper::isAjaxRequest('modal')) return $this->send($this->taskZen->responseModal($oldTask, $from));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $this->createLink('task', 'view', "taskID=$taskID")));
        }

        $this->view->title = $this->view->execution->name . $this->lang->colon . $this->lang->task->cancel;
        $this->view->users = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /**
     * 激活一个任务
     * Activate a task.
     *
     * @param  int    $taskID
     * @param  string $cardPosition
     * @param  string $from
     * @access public
     * @return void
     */
    public function activate(int $taskID, string $cardPosition = '', string $from = '')
    {
        /* Analytic parameter. */
        $cardPosition = str_replace(array(',', ' '), array('&', ''), $cardPosition);
        parse_str($cardPosition, $output);

        $this->taskZen->commonAction($taskID);

        if(!empty($_POST))
        {
            /* Prepare the data information before activate the task. */
            $task = $this->taskZen->buildTaskForActivate($taskID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $teamData = form::data($this->config->task->form->team->edit)->get();
            $changes  = $this->task->activate($task, $this->post->comment, $teamData, $output);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->post->comment != '' || !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('task', $taskID, 'Activated', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->executeHooks($taskID);

            /* Get the information returned after a task is started. */
            $task     = $this->task->fetchByID($taskID);
            $response = $this->taskZen->responseAfterChangeStatus($task, $from);
            $this->send($response);
        }

        if(!isset($this->view->members[$this->view->task->finishedBy])) $this->view->members[$this->view->task->finishedBy] = $this->view->task->finishedBy; // Ensure that the completion person is on the user list.

        /* Get task teammembers. */
        if(!empty($this->view->task->team))
        {
            $teamAccounts = array_column($this->view->task->team, 'account');
            $teamMembers  = array();
            foreach($this->view->members as $account => $name)
            {
                if(!$account || in_array($account, $teamAccounts)) $teamMembers[$account] = $name;
            }
            $this->view->teamMembers = $teamMembers;
        }

        $this->view->title      = $this->view->execution->name . $this->lang->colon . $this->lang->task->activate;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->isMultiple = !empty($this->view->task->team);
        $this->display();
    }

    /**
     * 删除一个任务。
     * Delete a task.
     *
     * @param  int    $executionID
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function delete(int $executionID, int $taskID)
    {
        $task = $this->task->getByID($taskID);
        if($task->parent == '-1') return $this->send(array('result' => 'fail', 'message' => $this->lang->task->cannotDeleteParent));

        $this->task->delete(TABLE_TASK, $taskID);
        if($task->parent > 0)
        {
            $this->task->updateParentStatus($task->id);
            $this->loadModel('action')->create('task', $task->parent, 'deleteChildrenTask', '', $taskID);
        }
        if($task->fromBug != 0) $this->dao->update(TABLE_BUG)->set('toTask')->eq(0)->where('id')->eq($task->fromBug)->exec();
        if($task->story) $this->loadModel('story')->setStage($task->story);

        $this->executeHooks($taskID);

        return $this->send(array('result' => 'success', 'load' => true));
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
    public function ajaxGetUserTasks(int $userID = 0, string $id = '', string $status = 'wait,doing', int $appendID = 0)
    {
        if($userID == 0) $userID = $this->app->user->id;
        $user    = $this->loadModel('user')->getById($userID, 'id');
        $account = $user->account;

        $tasks          = $this->task->getUserTaskPairs($account, $status, array(), array($appendID));
        $suspendedTasks = $this->task->getUserSuspendedTasks($account);
        $items          = array();
        foreach($tasks as $taskID => $taskName)
        {
            if(isset($suspendedTasks[$taskID]))
            {
                unset($tasks[$taskID]);
                continue;
            }
            $items[] = array('text' => $taskName, 'value' => $taskID);
        }

        $fieldName = $id ? "tasks[$id]" : 'task';
        return print(json_encode(array('name' => $fieldName, 'items' => $items)));
    }

    /**
     * AJAX: return execution tasks in html select.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function ajaxGetExecutionTasks(int $executionID)
    {
        $tasks = $this->task->getExecutionTaskPairs((int)$executionID);

        $items = array();
        foreach($tasks as $taskID => $taskName) $items[] = array('text' => $taskName, 'value' => $taskID, 'keys' => $taskName);
        return print(json_encode($items));
    }

    /**
     * 禅道客户端获取任务动态。
     * AJAX: get the actions of a task. for web app.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function ajaxGetDetail(int $taskID)
    {
        $this->view->actions = $this->loadModel('action')->getList('task', $taskID);
        $this->display();
    }

    /**
     * The report page.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $chartType  default|pie|bar|line
     * @access public
     * @return void
     */
    public function report(int $executionID, string $browseType = 'all', string $chartType = 'default')
    {
        $this->loadModel('report');
        $this->view->charts = array();

        /* Build chart data. */
        $chartList = array();
        if(!empty($_POST)) $chartList = $this->taskZen->buildChartData($chartType);

        /* If the project is non-execution, the chart of tasks by execution is not shown. */
        $execution = $this->loadModel('execution')->getByID($executionID);
        if(!$execution->multiple) unset($this->lang->task->report->charts['tasksPerExecution']);

        $this->execution->setMenu($executionID);

        $this->view->title         = $execution->name . $this->lang->colon . $this->lang->task->report->common;
        $this->view->executionID   = $executionID;
        $this->view->browseType    = $browseType;
        $this->view->chartType     = $chartType;
        $this->view->charts        = $chartList;
        $this->view->checkedCharts = $this->post->charts ? implode(',', $this->post->charts) : '';

        $this->display();
    }

    /**
     * 导出任务数据。
     * get data to export.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  string $type     browse type, such as: all,unclosed,assignedtome,myinvolved,assignedbyme,needconfirm,etc
     * @access public
     * @return void
     */
    public function export(int $executionID, string $orderBy, string $type)
    {
        /* Get execution info and export fields. */
        $execution       = $this->execution->getByID($executionID);
        $allExportFields = $this->config->task->exportFields;
        if($execution->lifetime == 'ops' || in_array($execution->attribute, array('request', 'review'))) $allExportFields = str_replace(' story,', '', $allExportFields);

        if($_POST)
        {
            $this->loadModel('file');
            if($type == 'bysearch') $this->config->task->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getAllModulePairs');

            /* Create field lists. */
            $fields = $this->taskZen->getExportFields($allExportFields);

            /* Get tasks. */
            $tasks = $this->task->getExportTasks($orderBy);
            if($type == 'group') $tasks = $this->taskZen->processExportGroup($executionID, $tasks, $orderBy);

            /* Process export data. */
            $tasks = $this->taskZen->processExportData($tasks, $execution->project);
            if($this->config->edition != 'open') list($fields, $tasks) = $this->loadModel('workflowfield')->appendDataFromFlow($fields, $tasks);

            $this->post->set('fields', $fields);
            $this->post->set('rows', $tasks);
            $this->post->set('kind', 'task');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        if(isset($this->lang->execution->featureBar['task'][$type]))
        {
            $browseType = $this->lang->execution->featureBar['task'][$type];
        }
        else
        {
            $browseType = isset($this->lang->execution->statusSelects[$type]) ? $this->lang->execution->statusSelects[$type] : '';
        }

        $this->view->fileName        = $execution->name . $this->lang->dash . $browseType . $this->lang->task->common;
        $this->view->allExportFields = $allExportFields;
        $this->view->customExport    = true;
        $this->view->orderBy         = $orderBy;
        $this->view->type            = $type;
        $this->view->executionID     = $executionID;

        $this->display();
    }

    /**
     * AJAX: Get the json of the task by ID.
     * Note: This function is NOT used in open edition.
     *
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function ajaxGetByID(int $taskID)
    {
        $task = $this->dao->select('*')->from(TABLE_TASK)->where('id')->eq($taskID)->fetch();
        if(!$task) return;

        $realname   = $this->dao->select('realname')->from(TABLE_USER)->where('account')->eq($task->assignedTo)->fetch('realname');
        $assignedTo = $task->assignedTo == 'closed' ? 'Closed' : $task->assignedTo;

        $task->assignedTo = $realname ? $realname : $assignedTo;
        if($task->story)
        {
            $this->app->loadLang('story');
            $stage = $this->dao->select('stage')->from(TABLE_STORY)->where('id')->eq($task->story)->andWhere('version')->eq($task->storyVersion)->fetch('stage');
            $task->storyStage = zget($this->lang->story->stageList, $stage);
        }
        return print(json_encode($task));
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
            $changes  = $this->task->updateTeam($task, $postData);

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

    /**
     * AJAX: 返回一个html格式的需求选择列表用于创建测试类型的任务。该需求的名称将作为创建任务的子任务名称。
     * AJAX: Return the stories selection list in html format for create the task with test type. The child task has same name with the story.
     *
     * @param  int    $executionID
     * @param  int    $taskID      The task ID which the task copy from. This will be used for creating a task from a existed task.
     * @access public
     * @return string
     */
    public function ajaxGetTestStories(int $executionID, int $taskID = 0)
    {
        $stories         = $this->story->getExecutionStoryPairs($executionID, 0, 'all', '', '', 'active');
        $testStoryIdList = $this->story->getTestStories(array_keys($stories), $executionID);
        $testStories     = array();
        foreach($stories as $testStoryID => $storyTitle)
        {
            /**
             * 如果在执行中已经为该需求创建了测试类型任务，则跳过这个需求不再给前台展示。
             * If a test type task has already been created for the requirement during execution, the requirement is skipped and not shown to the foreground.
             */
            if(empty($testStoryID) || isset($testStoryIdList[$testStoryID])) continue;

            $testStories[$testStoryID] = $storyTitle;
        }

        $this->view->testStories = $testStories;
        $this->view->task        = $this->loadModel('task')->getByID($taskID);
        $this->view->members     = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');
        $this->display();
    }

    /**
     * AJAX: 获取批量创建任务的需求下拉列表。
     * AJAX: Get the stories selection list for batch create tasks.
     *
     * @param  int    $executionID
     * @param  int    $moduleID
     * @param  string $zeroTaskStory
     * @access public
     * @return void
     */
    public function ajaxGetStories(int $executionID, int $moduleID, string $zeroTaskStory = 'false')
    {
        $stories       = $this->loadModel('story')->getExecutionStoryPairs($executionID, 0, 'all', $moduleID, 'short', 'active');
        $taskCountList = $this->task->getStoryTaskCounts(array_keys($stories), $executionID);

        $items = array();
        foreach($stories as $storyID => $storyName)
        {
            if(empty($storyName)) continue;
            if($zeroTaskStory == 'true' && $taskCountList[$storyID] > 0) continue;
            $items[] = array('text' => $storyName, 'value' => $storyID, 'keys' => $storyName);
        }

        return print(json_encode($items));
    }
}
