<?php
declare(strict_types=1);
/**
 * The zen file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easysoft.ltd>
 * @package     task
 * @link        https://www.zentao.net
 */
class taskZen extends task
{

    /**
     * 执行数组。
     * Execution's [id] => 'name' pairs.
     *
     * @var    array
     * @access private
     */
    private $executionPairs = array();

    /**
     * 准备创建数据。
     * Prepare edit data.
     *
     * @param  form $postDataFixer
     * @param  int    $taskID
     * @access protected
     * @return object
     */
    protected function prepareEdit(form $postDataFixer, int $taskID): object
    {
        $oldTask  = $this->task->getByID($taskID);
        $now      = helper::now();
        $postData = $postDataFixer->get();
        $task     = $postDataFixer->add('id', $taskID)
            ->setIF(!$postData->assignedTo and !empty($oldTask->team) and !empty($postDataFixer->rawdata->team), 'assignedTo', $this->task->getAssignedTo4Multi($postDataFixer->rawdata->team, $oldTask))
            ->setIF(!$oldTask->mode and !$postData->assignedTo and !empty($postDataFixer->rawdata->team), 'assignedTo', $postDataFixer->rawdata->team[0])
            ->setIF($oldTask->parent == 0 && $postData->parent == '', 'parent', 0)
            ->setIF($postData->story != false and $postData->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion($postData->story))

            ->setIF($postData->mode   == 'single', 'mode', '')
            ->setIF($postData->status == 'done', 'left', 0)
            ->setIF($postData->status == 'done'   and !$postData->finishedBy,   'finishedBy',   $this->app->user->account)
            ->setIF($postData->status == 'done'   and !$postData->finishedDate, 'finishedDate', $now)

            ->setIF($postData->status == 'cancel' and !$postData->canceledBy,   'canceledBy',   $this->app->user->account)
            ->setIF($postData->status == 'cancel' and !$postData->canceledDate, 'canceledDate', $now)
            ->setIF($postData->status == 'cancel', 'assignedTo',   $oldTask->openedBy)
            ->setIF($postData->status == 'cancel', 'assignedDate', $now)

            ->setIF($postData->status == 'closed' and !$postData->closedBy,     'closedBy',     $this->app->user->account)
            ->setIF($postData->status == 'closed' and !$postData->closedDate,   'closedDate',   $now)
            ->setIF($postData->consumed > 0 and $postData->left > 0 and $postData->status == 'wait', 'status', 'doing')

            ->setIF($postData->assignedTo != $oldTask->assignedTo, 'assignedDate', $now)

            ->setIF($postData->status == 'wait' and $postData->left == $oldTask->left and $postData->consumed == 0 and $postData->estimate, 'left', $postData->estimate)
            ->setIF($oldTask->parent > 0 and !$postData->parent, 'parent', 0)
            ->setIF($oldTask->parent < 0, 'estimate', $oldTask->estimate)
            ->setIF($oldTask->parent < 0, 'left', $oldTask->left)

            ->setIF($oldTask->name != $postData->name || $oldTask->estStarted != $postData->estStarted || $oldTask->deadline != $postData->deadline, 'version', $oldTask->version + 1)
            ->add('lastEditedDate', $now)
            ->stripTags($this->config->task->editor->edit['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->get();

        return $task;
    }

    /**
     * 编辑任务后返回响应.
     * Reponse after edit.
     *
     * @param  int     $taskID
     * @param  string  $from
     * @param  array[] $changes
     * @access protected
     * @return int
     */
    protected function reponseAfterEdit(int $taskID, string $from, array $changes)
    {
        $task = $this->task->getById($taskID);
        if($task->fromBug != 0)
        {
            foreach($changes as $change)
            {
                if($change['field'] == 'status')
                {
                    $confirmURL = $this->createLink('bug', 'view', "id=$task->fromBug");
                    $cancelURL  = $this->server->HTTP_REFERER;
                    return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent'));
                }
            }
        }

        if(isonlybody())
        {
            $execution    = $this->execution->getByID($task->execution);
            $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
            $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
            if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project')) and $execution->type == 'kanban')
            {
                $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                $kanbanData    = json_encode($kanbanData);

                return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
            }
            if($from == 'taskkanban')
            {
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);

                return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
            }
            return print(js::reload('parent.parent'));
        }

        if(defined('RUN_MODE') && RUN_MODE == 'api') return array('status' => 'success', 'data' => $taskID);
        return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
    }

    /**
     * 构建任务编辑表格
     * Build task edit form.
     *
     * @param  int $taskID
     * @access protected
     * @return void
     */
    protected function buildEditForm(int $taskID): void
    {
        $task  = $this->view->task;
        $tasks = $this->task->getParentTaskPairs($this->view->execution->id, $task->parent);
        if(isset($tasks[$taskID])) unset($tasks[$taskID]);

        if(!isset($this->view->members[$task->assignedTo])) $this->view->members[$task->assignedTo] = $task->assignedTo;
        if(isset($this->view->members['closed']) or $task->status == 'closed') $this->view->members['closed']  = 'Closed';

        $executions = !empty($task->project) ? $this->execution->getByProject($task->project, 'all', 0, true) : array();

        /* Get task members. */
        $taskMembers = array();
        if(!empty($task->team))
        {
            $teamAccounts = $task->members;
            foreach($teamAccounts as $teamAccount)
            {
                if(!isset($this->view->members[$teamAccount])) continue;
                $taskMembers[$teamAccount] = $this->view->members[$teamAccount];
            }
        }
        else
        {
            $taskMembers = $this->view->members;
        }

        $this->view->title         = $this->lang->task->edit . 'TASK' . $this->lang->colon . $this->view->task->name;
        $this->view->position[]    = $this->lang->task->common;
        $this->view->position[]    = $this->lang->task->edit;
        $this->view->stories       = $this->story->getExecutionStoryPairs($this->view->execution->id, 0, 'all', '', 'full', 'active');
        $this->view->tasks         = $tasks;
        $this->view->taskMembers   = $taskMembers;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted|noclosed', "{$task->openedBy},{$task->canceledBy},{$task->closedBy}");
        $this->view->showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $this->view->modules       = $this->tree->getTaskOptionMenu($task->execution, 0, 0, $this->view->showAllModule ? 'allModule' : '');
        $this->view->executions    = $executions;
        $this->view->contactLists  = $this->loadModel('user')->getContactLists($this->app->user->account, 'withnote');
        $this->display();
    }

    /**
     * 准备指派给的数据.
     * Prepare assignto data.
     *
     * @param  form $postDataFixer
     * @param  int    $taskID
     * @access protected
     * @return object
     */
    protected function prepareAssignTo(form $postDataFixer, int $taskID): object
    {
        $task = $postDataFixer->add('id', $taskID)
            ->stripTags($this->config->task->editor->assignto['id'], $this->config->allowedTags)
            ->get();
        return $task;
    }

    /**
     * 指派后返回响应.
     * Reponse after assignto.
     *
     * @param  int    $taskID
     * @access protected
     * @return int
     */
    protected function reponseAfterAssignTo(int $taskID, string $from)
    {
        if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'success'));
        if(isonlybody())
        {
            $task         = $this->task->getById($taskID);
            $execution    = $this->execution->getByID($task->execution);
            $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
            $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
            if(($this->app->tab == 'execution' or ($this->config->vision == 'lite' and $this->app->tab == 'project' and $this->session->kanbanview == 'kanban')) and $execution->type == 'kanban')
            {
                $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
                $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
                $kanbanData    = json_encode($kanbanData);

                return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban($kanbanData)"));
            }
            if($from == 'taskkanban')
            {
                $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
                $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
                $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
                $kanbanData      = $kanbanData[$kanbanType];
                $kanbanData      = json_encode($kanbanData);

                return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", $kanbanData)"));
            }
            return print(js::closeModal('parent.parent', 'this'));
        }
        return print(js::locate($this->createLink('task', 'view', "taskID=$taskID"), 'parent'));
    }

    /**
     * 构建指派给表格。
     * Build AssignTo Form.
     *
     * @param  int    $executionID
     * @param  object $task
     * @access protected
     * @return void
     */
    protected function buildAssignToForm(int $executionID, object $task): void
    {
        $this->loadModel('action');
        $members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');

        /* Compute next assignedTo. */
        if(!empty($task->team) and strpos('done,cencel,closed', $task->status) === false)
        {
            $task->nextUser = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $members = $this->task->getMemberPairs($task);
        }

        if(!isset($members[$task->assignedTo])) $members[$task->assignedTo] = $task->assignedTo;
        if(isset($members['closed']) or $task->status == 'closed') $members['closed'] = 'Closed';

        $this->view->title      = $this->view->execution->name . $this->lang->colon . $this->lang->task->assign;
        $this->view->position[] = $this->lang->task->assign;
        $this->view->task       = $task;
        $this->view->members    = $members;
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * 设置任务二级导航。
     * Set the task secondary navigation.
     *
     * @param  int       $executionID
     * @access protected
     * @return int
     */
    protected function setMenu(int $executionID): int
    {
        $execution = $this->execution->getById($executionID);

        /* If the admin denied modification of closed executions, only query not closed executions. */
        $queryMode = $execution && common::canModify('execution', $execution) ? 'all' : 'noclosed';

        /* Get executions the current user can access. */
        $this->executionPairs = $this->execution->getPairs(0, 'all', $queryMode);

        /* Call checkAccess method to judge the user can access the execution or not, if not return the first one he can access. */
        $executionID = $this->execution->checkAccess($executionID, $this->executionPairs);

        /* Set Menu. */
        $this->execution->setMenu($executionID);
        if($this->app->tab == 'project') $this->project->setMenu($this->session->project);

        return $executionID;
    }

    /**
     * 通过传入的对象ID设置任务信息。
     * Set task information through the incoming object ID.
     *
     * @param  int       $storyID
     * @param  int       $moduleID
     * @param  int       $taskID
     * @param  int       $todoID
     * @param  int       $bugID
     * @access private
     * @return object
     */
    private function setTaskInfoByObjectID(int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID): object
    {
        $task = $this->config->task->create->template;
        $task->module = $moduleID;

        /* If exist task, copy task information by task id. */
        if($taskID > 0)
        {
            $task        = $this->task->getByID($taskID);
            $executionID = $task->execution;

            /* Emptying consumed hours when copy task. */
            if($task->mode == 'multi')
            {
                foreach($task->team as $teamMember) $teamMember->consumed = 0;
            }
        }

        /* If exist todo, copy todo information by todo id. */
        if($todoID > 0)
        {
            $todo = $this->loadModel('todo')->getById($todoID);
            $task->name = $todo->name;
            $task->pri  = $todo->pri;
            $task->desc = $todo->desc;
        }

        /* If exist bug, copy bug information by bug id. */
        if($bugID > 0)
        {
            $bug = $this->loadModel('bug')->getById($bugID);
            $task->name       = $bug->title;
            $task->pri        = !empty($bug->pri) ? $bug->pri : '3';
            $task->assignedTo = array($bug->assignedTo);
        }

        /* If exist story, copy story module by story id. */
        if($storyID)
        {
            $task->story  = $storyID;
            $task->module = $this->dao->findByID($storyID)->from(TABLE_STORY)->fetch('module');
        }
        elseif(!$moduleID)
        {
            $task->module = (int)$this->cookie->lastTaskModule;
        }

        return $task;
    }

    /**
     * 展示看板相关变量。
     * Show related variable about the Kanban.
     * 
     * @param  int     $executionID
     * @param  array   $output
     * @access private
     * @return void
     */
    private function showKanbanRelatedVars(int $executionID, array $output): void
    {
        $this->loadModel('kanban');

        $regionID    = isset($output['regionID']) ? (int)$output['regionID'] : 0;
        $laneID      = isset($output['laneID'])   ? (int)$output['laneID']   : 0;
        $regionPairs = $this->kanban->getRegionPairs($executionID, 0, 'execution');
        $regionID    = $regionID ? $regionID : key($regionPairs);
        $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'task');
        $laneID      = $laneID ? $laneID : key($lanePairs);

        $this->view->regionID    = $regionID;
        $this->view->laneID      = $laneID;
        $this->view->regionPairs = $regionPairs;
        $this->view->lanePairs   = $lanePairs;
    }

    /**
     * 展示地盘待处理区块的ID。
     * Show the ID of the block to be processed on the my.
     *
     * @access private
     * @return void
     */
    private function showAssignedToMeBlockID(): void
    {
        /* Get block id of assinge to me. */
        $blockID = 0;
        if(isonlybody())
        {
            $blockID = $this->dao->select('id')->from(TABLE_BLOCK)
                ->where('block')->eq('assingtome')
                ->andWhere('module')->eq('my')
                ->andWhere('account')->eq($this->app->user->account)
                ->orderBy('order_desc')
                ->fetch('id');
        }

        $this->view->blockID = $blockID;
    }

    /**
     * 展示执行相关数据。
     * Show execution related data.
     *
     * @param  object    $execution
     * @access private
     * @return void
     */
    private function showExecutionData(object $execution): void
    {
        $projectID     = $execution ? $execution->project : 0;
        $lifetimeList  = array();
        $attributeList = array();
        if(!empty($projectID))
        {
            $executionKey  = 0;
            $executions    = $this->execution->getByProject($projectID, 'all', 0, true);
            $executionList = $this->execution->getByIdList(array_keys($executions));
            foreach($executionList as $executionItem)
            {
                if(!common::canModify('execution', $executionItem)) $executionKey = $executionItem->id;
                if($executionKey) unset($executions[$executionKey]);
                if(!$executionKey) continue;

                $lifetimeList[$executionKey]  = $executionItem->lifetime;
                $attributeList[$executionKey] = $executionItem->attribute;
            }
        }
        else
        {
            $executions    = $this->executionPairs;
            $executionList = $this->execution->getByIdList(array_keys($executions));
            foreach($executionList as $executionItem)
            {
                $executionKey = $executionItem->id;
                $lifetimeList[$executionKey]  = $executionItem->lifetime;
                $attributeList[$executionKey] = $executionItem->attribute;
            }
        }

        $this->view->projectID     = $projectID;
        $this->view->executions    = $executions;
        $this->view->lifetimeList  = $lifetimeList;
        $this->view->attributeList = $attributeList;
        $this->view->productID     = $this->loadModel('product')->getProductIDByProject($projectID);
        $this->view->features      = $this->execution->getExecutionFeatures($execution);
        $this->view->users         = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->members       = $this->user->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
    }

    /**
     * 处理创建任务的请求数据。
     * Process the request data for the creation task.
     *
     * @param  int     $executionID 
     * @param  object  $formData 
     * @access private
     * @return object
     */
    private function prepareTask4Create(int $executionID, object $formData): object
    {
        $rawData   = $formData->rawdata;
        $execution = $this->dao->findById($rawData->execution)->from(TABLE_EXECUTION)->fetch();
        $team      = !empty($rawData->team) ? array_filter($rawData->team) : array();
        $task      = $formData->setDefault('execution', $executionID)
            ->setDefault('project', $this->task->getProjectID($executionID))
            ->setIF($rawData->estimate != false, 'left', $rawData->estimate)
            ->setIF(isset($rawData->story), 'storyVersion', isset($rawData->story) ? $this->loadModel('story')->getVersion($rawData->story) : 0)
            ->setIF(empty($rawData->multiple) || count($team) < 1, 'mode', '')
            ->setIF($execution and ($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))), 'story', 0)
            ->stripTags($this->config->task->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->get();

        /* Processing image link. */
        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], $rawData->uid);
    }

    /**
     * 检查传入的创建数据是否符合要求。
     * Check if the incoming creation data meets the requirements.
     *
     * @param  int     $executionID
     * @param  float   $estimate
     * @param  string  $estStarted
     * @param  string  $deadline
     * @access private
     * @return bool
     */
    private function checkCreate(int $executionID, float $estimate, string $estStarted, string $deadline): bool
    {
        /* Check if the estimate is positive. */
        if($estimate < 0)
        {
            dao::$errors['estimate'] = $this->lang->task->error->recordMinus;
            return false;
        }

        /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
        if(!empty($this->config->limitTaskDate))
        {
            $this->task->checkEstStartedAndDeadline($executionID, $estStarted, $deadline);
            if(dao::isError()) return false;
        }

        /* Check start and end date. */
        if(!helper::isZeroDate($deadline) and $estStarted > $deadline)
        {
            dao::$errors['deadline'] = $this->lang->task->error->deadlineSmall;
            return false;
        }

        return true;
    }

    /**
     * 检查规定时间内是否创建了同名任务。 
     * Check whether a task with the same name is created within the specified time.
     * 
     * @param  object    $task 
     * @access private
     * @return int
     */
    private function checkDuplicateName($task): int
    {
        /* Check duplicate task. */
        if($task->type != 'affair' and $task->name)
        {
            $result = $this->loadModel('common')->removeDuplicate('task', $task, "execution={$task->execution} and story=" . (int)$task->story . (isset($task->feedback) ? " and feedback=" . (int)$task->feedback : ''));
            if($result['stop']) return zget($result, 'duplicate', 0);
        }
        return 0;
    }

    /**
     * 处理关联需求的测试子任务的请求数据。
     * Process request data for test subtasks related to stories.
     * 
     * @param  int       $executionID 
     * @param  object    $formData 
     * @access private
     * @return array|bool
     */
    private function prepareTestTasks4Create(int $executionID, object $formData): array|bool
    {
        /* Set data for the type of test task that has linked stories. */
        $testTasks = array();
        $rawData   = $formData->rawdata;
        foreach($rawData->testStory as $i => $storyID)
        {
            if(empty($storyID)) continue;

            /* Process the ditto option as a concrete value. */
            $estStarted = !isset($rawData->testEstStarted[$i]) || (isset($rawData->estStartedDitto[$i]) && $rawData->estStartedDitto[$i] == 'on') ? $estStarted : $rawData->testEstStarted[$i];
            $deadline   = !isset($rawData->testDeadline[$i]) || (isset($rawData->deadlineDitto[$i]) && $rawData->deadlineDitto[$i] == 'on') ? $deadline : $rawData->testDeadline[$i];
            $assignedTo = !isset($rawData->testAssignedTo[$i]) || $rawData->testAssignedTo[$i] == 'ditto' ? $assignedTo : $rawData->testAssignedTo[$i];

            /* Set task data. */
            $task = new stdclass();
            $task->execution  = $executionID;
            $task->story      = $storyID;
            $task->pri        = $rawData->testPri[$i];
            $task->estStarted = $estStarted;
            $task->deadline   = $deadline;
            $task->assignedTo = $assignedTo;
            $task->estimate   = (float)$rawData->testEstimate[$i];
            $task->left       = (float)$rawData->testEstimate[$i];

            $testTasks[$storyID] = $task;
        }
        return $testTasks;
    }

    /**
     * 检查关联需求的测试类型任务数据格式是否符合要求。 
     * Check if the test type task data format of the linked stories meets the requirements.
     * 
     * @param  object[] $tasks 
     * @access private
     * @return bool
     */
    private function checkTestTasks(array $tasks): bool
    {
        foreach($tasks as $task)
        {
            /* Check if the estimate is positive. */
            if($task->estimate < 0)
            {
                dao::$errors[] = "ID: {$task->story} {$this->lang->task->error->recordMinus}";
                return false;
            }

            /* If the task start and end date must be between the execution start and end date, check if the task start and end date accord with the conditions. */
            if(!empty($this->config->limitTaskDate))
            {
                $this->checkEstStartedAndDeadline($task->execuiton, $task->estStarted, $task->deadline);
                if(dao::isError())
                {
                    foreach(dao::getError() as $field => $error)
                    {
                        dao::$errors[] = "ID: {$task->story} {$error}";
                        return false;
                    }
                }
            }

            /* Check start and end date. */
            if($task->estStarted > $task->deadline)
            {
                dao::$errors[] = "ID: {$task->story} {$this->lang->task->error->deadlineSmall}";
                return false;
            }

            /* Checking the required fields of task data. */
            $this->dao->insert(TABLE_TASK)->data($task)->batchCheck($this->config->task->create->requiredFields, 'notempty');
            if(dao::isError())
            {
                foreach(dao::getError() as $field => $error)
                {
                    dao::$errors[] = "ID: {$task->story} {$error}";
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 处理创建任务后的返回信息。
     * The information returned after processing the creation task.
     *
     * @param  int       $taskID
     * @param  object    $execution
     * @param  string    $afterChoice continueAdding|toTaskList|toStoryList
     * @access protected
     * @return array|bool
     */
    protected function responseAfterCreate(object $task, object $execution, string $afterChoice): array|bool
    {
        /* If there is a database error, return the error message. */
        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        /* Return task id when call the API. */
        if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $taskID);

        $taskID = $task->id;
        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;

        /* Send Webhook notifications. */
        $message = $this->executeHooks($taskID);
        if($message) $response['message'] = $message;

        /* Processing the return information of pop-up windows. */
        if(isonlybody())
        {
            /* If it is Kanban execution, refresh the Kanban statically through callback. */
            if($this->app->tab == 'execution' or $this->config->vision == 'lite')
            {
                $kanbanData = $this->getKanbanData($execution);
                $response['closeModal'] = true;
                $response['callback']   = $execution->type == 'kanban' ? "parent.updateKanban($kanbanData, 0)" : "parent.updateKanban(\"task\", $kanbanData)";
                return $response;
            }
            $response['locate'] = 'parent';
            return $response;
        }

        /* Locate the browser. */
        if($this->app->getViewType() == 'xhtml')
        {
            $response['locate'] = $this->createLink('task', 'view', "taskID=$taskID", 'html');
            return $response;
        }

        /* Process the return information for selecting a jump after creation. */
        return $this->getLocateAfterCreate($task, $execution->id, $afterChoice);
    }

    /**
     * 处理创建后选择跳转的返回信息。
     * Process the return information for selecting a jump after creation.
     *
     * @param  object    $task
     * @param  int       $executionID
     * @param  string    $afterChoice
     * @access private
     * @return array
     */
    private function getLocateAfterCreate(object $task, int $executionID, string $afterChoice): array
    {
        /* Set the universal return value. */
        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;
        $response['locate']  = $this->createLink('execution', 'browse', "executionID=$executionID&tab=task");

        /* Set the response to continue adding task to story. */
        $executionID = $task->execution;
        if($afterChoice == 'continueAdding')
        {
            $storyID  = $task->story ? $task->story : 0;
            $moduleID = $task->module ? $task->module : 0;
            $response['message'] = $this->lang->task->successSaved . $this->lang->task->afterChoices['continueAdding'];
            $response['locate']  = $this->createLink('task', 'create', "executionID=$executionID&storyID=$storyID&moduleID=$moduleID");
        }
        /* Set the response to return task list. */
        elseif($afterChoice == 'toTaskList')
        {
            setcookie('moduleBrowseParam',  0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $response['locate'] = $this->createLink('execution', 'task', "executionID=$executionID&status=unclosed&param=0&orderBy=id_desc");
        }
        /* Set the response to return story list. */
        elseif($afterChoice == 'toStoryList')
        {
            $response['locate'] = $this->createLink('execution', 'story', "executionID=$executionID");
            if($this->config->vision == 'lite')
            {
                $projectID = $this->execution->getProjectID($executionID);
                $response['locate'] = $this->createLink('projectstory', 'story', "projectID=$projectID");
            }
        }

        return $response;
    }

    /**
     * 展示需求相关变量。
     * Show requirements related variables.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function showStoryVars(int $executionID): void
    {
        $stories         = $this->story->getExecutionStoryPairs($executionID, 0, 'all', '', '', 'active');
        $testStoryIdList = $this->loadModel('story')->getTestStories(array_keys($stories), $executionID);
        $testStories     = array();
        foreach($stories as $testStoryID => $storyTitle)
        {
            if(empty($testStoryID) or isset($testStoryIdList[$testStoryID])) continue;
            $testStories[$testStoryID] = $storyTitle;
        }
        $this->view->testStories     = $testStories;
        $this->view->testStoryIdList = $testStoryIdList;
        $this->view->stories         = $stories;
    }

    /**
     * 展示创建任务的相关变量。
     * Show the variables associated with the creation task.
     *
     * @param  object    $execution
     * @param  int       $storyID
     * @param  int       $moduleID
     * @param  int       $taskID
     * @param  int       $todoID
     * @param  int       $bugID
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function showCreateVars(object $execution, int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID, array $output): void
    {
        /* Get information about the task. */
        $executionID = $execution->id;
        $task        = $this->setTaskInfoByObjectID($storyID, $moduleID, $taskID, $todoID, $bugID);

        /* Get module information. */
        $showAllModule    = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $moduleOptionMenu = $this->tree->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');
        if(!$storyID and !isset($moduleOptionMenu[$task->module])) $task->module = 0;

        /* Display relevant variables. */
        $this->showAssignedToMeBlockID();
        $this->showExecutionData($execution);
        $this->showStoryVars($executionID);
        if($execution->type == 'kanban') $this->showKanbanRelatedVars($executionID, $output);

        /* Set Custom fields. */
        foreach(explode(',', $this->config->task->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;

        $this->view->title            = $execution->name . $this->lang->colon . $this->lang->task->create;
        $this->view->customFields     = $customFields;
        $this->view->showAllModule    = $showAllModule;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->showFields       = $this->config->task->custom->createFields;
        $this->view->gobackLink       = (isset($output['from']) and $output['from'] == 'global') ? $this->createLink('execution', 'task', "executionID=$executionID") : '';
        $this->view->execution        = $execution;
        $this->view->task             = $task;
        $this->view->storyID          = $storyID;

        $this->display();
    }

    /**
     * 准备创建任务前的数据信息。
     * Prepare the data information before creating the task.
     *
     * @param  int       $executionID
     * @param  float     $estimate
     * @param  string    $estStarted
     * @param  string    $deadline
     * @param  bool      $selectTestStory
     * @access protected
     * @return bool|array
     */
    protected function prepareCreate(int $executionID, float $estimate, string $estStarted, string $deadline, bool $selectTestStory): bool|array
    {
        /* Check if the incoming creation data meets the requirements. */
        $result = $this->checkCreate($executionID, $estimate, $estStarted, $deadline);
        if(!$result) return false;

        /* Process the request data for the creation task. */
        $formData = form::data($this->config->task->form->create);
        $task     = $this->prepareTask4Create($executionID, $formData);

        /* Prepare to create the data for the test subtask and to check the data format. */
        $testTasks = array();
        if($selectTestStory and $task->type == 'test')
        {
            $testTasks = $this->prepareTestTasks4Create($executionID, $formData);
            $result    = $this->checkTestTasks($testTasks);
            if(!$result) return false;
        }

        /* Check whether a task with the same name is created within the specified time. */
        $existTaskID = $this->checkDuplicateName($task);

        return array($task, $testTasks, $existTaskID);
    }

    /**
     * 根据一级菜单设置任务模块的导航。
     * According to the main menu, set the navbar of the task module.
     *
     * @param  int       $executionID
     * @param  int       $projectID
     * @access protected
     * @return void
     */
    protected function setMenuByTab(int $executionID, int $projectID = 0): void
    {
        $this->execution->setMenu($executionID);

        if($this->app->tab == 'project') $this->loadModel('project')->setMenu($projectID);
    }

    /**
     * 为表单获取自定义字段。
     * Get task's custom fields for form.
     *
     * @param  object    $execution
     * @param  string    $action
     * @access protected
     * return  array
     */
    protected function getCustomFields(object $execution, string $action): array
    {
        /* 设置自定义字段列表。 */
        $customFormField = 'custom' . ucfirst($action). 'Fields';
        foreach(explode(',', $this->config->task->{$customFormField}) as $field)
        {
            if($execution->type == 'stage' and strpos('estStarted,deadline', $field) !== false) continue;
            $customFields[$field] = $this->lang->task->$field;
        }

        /* 设置已勾选的自定义字段。 */
        $showFields = $this->config->task->custom->{$action . 'Fields'};
        if($execution->lifetime == 'ops' or $execution->attribute == 'request' or $execution->attribute == 'review')
        {
            unset($customFields['story']);
            $showFields = str_replace(',story,', ',', ",$showFields,");
            $showFields = trim($showFields, ',');
        }

        return array($customFields, $showFields);
    }

    /**
     * 构建批量创建任务的表单数据。
     * Build batch create form.
     *
     * @param  object    $execution
     * @param  int       $storyID
     * @param  int       $moduleID
     * @param  int       $taskID
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function buildBatchCreateForm(object $execution, int $storyID, int $moduleID, int $taskID, array $output): void
    {
        /* 获取区域和泳道下拉数据，并设置区域和泳道的默认值。*/
        if($execution->type == 'kanban') $this->taskZen->showKanbanRelatedVars($execution->id, $output);

        /* 任务拆解。 */
        if($taskID)
        {
            $task = $this->dao->findById($taskID)->from(TABLE_TASK)->fetch();
            $this->view->parentTitle  = $task->name;
            $this->view->parentPri    = $task->pri;
        }

        /* 需求批量分解任务。 */
        $story       = $this->story->getByID($storyID);
        $moduleID    = $story ? $story->module : $moduleID;
        $moduleParam = $story ? $moduleID : 0;

        /* 获取模块下拉数据。 */
        $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $modules       = $this->loadModel('tree')->getTaskOptionMenu($execution->id, 0, 0, $showAllModule ? 'allModule' : '');
        $stories       = $this->story->getExecutionStoryPairs($execution->id, 0, 'all', $moduleParam, 'short', 'active');

        /* Set Custom. */
        list($customFields, $showFields) = $this->getCustomFields($execution, 'batchCreate');

        $this->view->title        = $execution->name . $this->lang->colon . $this->lang->task->batchCreate;
        $this->view->execution    = $execution;
        $this->view->modules      = $modules;
        $this->view->parent       = $taskID;
        $this->view->storyID      = $storyID;
        $this->view->story        = $story;
        $this->view->moduleID     = $moduleID;
        $this->view->stories      = $stories;
        $this->view->storyTasks   = $this->task->getStoryTaskCounts(array_keys($stories), $execution->id);
        $this->view->members      = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution', 'nodeleted');
        $this->view->taskConsumed = isset($task) ? $task->consumed : 0;
        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;

        $this->display();
    }

    /**
     * 获取重定向链接。
     * Get redirected link.
     *
     * @param  object    $execution
     * @access protected
     * @return string
     */
    protected function getRedirectedLink(object $execution): string
    {
        if($this->app->tab == 'my')
        {
            $link = $this->createLink('my', 'work', 'mode=task');
        }
        elseif($this->app->tab == 'project' and $execution->multiple)
        {
            $link = $this->createLink('project', 'execution', "browseType=all&projectID={$execution->project}");
        }
        else
        {
            $link = $this->createLink('execution', 'browse', "executionID=$execution->id");
        }

        return $link;
    }

    /**
     * 任务的数据更新之后，获取对应看板的数据。
     * Get R&D kanban's or task kanban's data after task's data is updated.
     *
     * @param  object     $execution
     * @access protected
     * @return string
     */
    protected function getKanbanData(object $execution): string
    {
        $this->loadModel('kanban');

        $execLaneType    = $this->session->execLaneType ? $this->session->execLaneType : 'all';
        $execGroupBy     = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';
        $rdSearchValue   = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
        $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';

        /* 处理专业研发看板。 */
        if($execution->type == 'kanban')
        {
            $kanbanData    = $this->kanban->getRDKanban($execution->id, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
            $kanbanData    = json_encode($kanbanData);

            return $kanbanData;
        }

        /* 处理任务看板。 */
        $kanbanData = $this->kanban->getExecutionKanban($execution->id, $execLaneType, $execGroupBy, $taskSearchValue);
        $kanbanType = $execLaneType == 'all' ? 'task' : key($kanbanData);
        $kanbanData = json_encode($kanbanData[$kanbanType]);

        return $kanbanData;
    }
}
