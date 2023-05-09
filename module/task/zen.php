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
     * 准备编辑数据。
     * Prepare edit data.
     *
     * @param  form $postDataFixer
     * @param  int  $taskID
     * @access protected
     * @return object
     */
    protected function prepareEdit(form $postDataFixer, int $taskID): object
    {
        $now      = helper::now();
        $oldTask  = $this->task->getByID($taskID);
        $postData = $postDataFixer->get();

        if($postData->estimate < 0 || $postData->left < 0 || $postData->consumed < 0)
        {
            dao::$errors[] = $this->lang->task->error->recordMinus;
            return false;
        }

        if(!empty($this->config->limitTaskDate))
        {
            $this->task->checkEstStartedAndDeadline($oldTask->execution, $postData->estStarted, $postData->deadline);
            return !dao::isError();
        }

        if(!empty($postData->lastEditedDate) && $oldTask->lastEditedDate != $postData->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $task = $postDataFixer->add('id', $taskID)
            ->setIF(!$postData->assignedTo && !empty($oldTask->team) && !empty($postDataFixer->rawdata->team), 'assignedTo', $this->task->getAssignedTo4Multi($postDataFixer->rawdata->team, $oldTask))
            ->setIF(!$oldTask->mode && !$postData->assignedTo && !empty($postDataFixer->rawdata->team), 'assignedTo', $postDataFixer->rawdata->team[0])
            ->setIF(is_numeric($postData->estimate), 'estimate', (float)$postData->estimate)
            ->setIF(is_numeric($postData->consumed), 'consumed', (float)$postData->consumed)
            ->setIF(is_numeric($postData->left),     'left',     (float)$postData->left)
            ->setIF($oldTask->parent == 0 && $postData->parent == '', 'parent', 0)
            ->setIF($postData->story != false && $postData->story != $oldTask->story, 'storyVersion', $this->loadModel('story')->getVersion($postData->story))

            ->setIF($postData->mode   == 'single', 'mode', '')
            ->setIF($postData->status == 'done', 'left', 0)
            ->setIF($postData->status == 'done'   && !$postData->finishedBy,   'finishedBy',   $this->app->user->account)
            ->setIF($postData->status == 'done'   && !$postData->finishedDate, 'finishedDate', $now)

            ->setIF($postData->status == 'cancel' && !$postData->canceledBy,   'canceledBy',   $this->app->user->account)
            ->setIF($postData->status == 'cancel' && !$postData->canceledDate, 'canceledDate', $now)
            ->setIF($postData->status == 'cancel', 'assignedTo',   $oldTask->openedBy)
            ->setIF($postData->status == 'cancel', 'assignedDate', $now)

            ->setIF($postData->status == 'closed' && !$postData->closedBy,     'closedBy',     $this->app->user->account)
            ->setIF($postData->status == 'closed' && !$postData->closedDate,   'closedDate',   $now)
            ->setIF($postData->consumed > 0 && $postData->left > 0 && $postData->status == 'wait', 'status', 'doing')

            ->setIF($postData->assignedTo != $oldTask->assignedTo, 'assignedDate', $now)

            ->setIF($postData->status == 'wait' && $postData->left == $oldTask->left && $postData->consumed == 0 && $postData->estimate, 'left', $postData->estimate)
            ->setIF($oldTask->parent > 0 && !$postData->parent, 'parent', 0)
            ->setIF($oldTask->parent < 0, 'estimate', $oldTask->estimate)
            ->setIF($oldTask->parent < 0, 'left', $oldTask->left)

            ->setIF($oldTask->name != $postData->name || $oldTask->estStarted != $postData->estStarted || $oldTask->deadline != $postData->deadline, 'version', $oldTask->version + 1)
            ->add('lastEditedBy', $this->app->user->account)
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
     * @param  string  $from        ''|taskkanban
     * @param  array[] $changes
     * @access protected
     * @return array|int
     */
    protected function reponseAfterEdit(int $taskID, string $from, array $changes): array|int
    {
        $task = $this->task->getById($taskID);
        if($task->fromBug != 0)
        {
            foreach($changes as $change)
            {
                if($change['field'] == 'status')
                {
                    $confirmURL = $this->createLink('bug', 'view', "id={$task->fromBug}");
                    $cancelURL  = $this->server->HTTP_REFERER;
                    return print(js::confirm(sprintf($this->lang->task->remindBug, $task->fromBug), $confirmURL, $cancelURL, 'parent', 'parent'));
                }
            }
        }

        if(isonlybody()) return $this->reponseKanban($task, $from);

        if(defined('RUN_MODE') && RUN_MODE == 'api') return array('status' => 'success', 'data' => $taskID);
        return print(js::locate($this->createLink('task', 'view', "{taskID=$taskID}"), 'parent'));
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

        /* Prepare to assign to relevant parameters. */
        if(!isset($this->view->members[$task->assignedTo])) $this->view->members[$task->assignedTo] = $task->assignedTo;
        if(isset($this->view->members['closed']) || $task->status == 'closed') $this->view->members['closed'] = 'Closed';

        /* Get the executions of the task. */
        $executions = !empty($task->project) ? $this->execution->getByProject($task->project, 'all', 0, true) : array();

        /* Get task members. */
        $taskMembers = array();
        if(!empty($task->team))
        {
            foreach($task->members as $teamAccount)
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
     * 准备管理团队的数据。
     * Prepare manage team data.
     *
     * @param  form $postData
     * @param  int  $taskID
     * @access protected
     * @return object
     */
    protected function prepareManageTeam(form $postData, int $taskID): object
    {
        $now  = helper::now();
        $task = $postData->add('id', $taskID)
            ->add('lastEditedBy', $this->app->user->account)
            ->get();
        return $task;
    }

    /**
     * 准备指派给的数据.
     * Prepare assignto data.
     *
     * @param  form $postDataFixer
     * @param  int  $taskID
     * @access protected
     * @return object
     */
    protected function prepareAssignTo(form $postDataFixer, int $taskID): object
    {
        $task = $postDataFixer->add('id', $taskID)
            ->add('lastEditedBy', $this->app->user->account)
            ->stripTags($this->config->task->editor->assignto['id'], $this->config->allowedTags)
            ->get();
        return $task;
    }

    /**
     * 指派后返回响应.
     * Reponse after assignto.
     *
     * @param  int    $taskID
     * @param  string $from        ''|taskkanban
     * @access protected
     * @return array|int
     */
    protected function reponseAfterAssignTo(int $taskID, string $from): array|int
    {
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success');

        $task = $this->task->getById($taskID);
        if(isonlybody()) return $this->reponseKanban($task, $from);

        return print(js::locate($this->createLink('task', 'view', "{taskID=$taskID}"), 'parent'));
    }

    /**
     * 构建指派给表格。
     * Build AssignTo Form.
     *
     * @param  int $executionID
     * @param  int $taskID
     * @access protected
     * @return void
     */
    protected function buildAssignToForm(int $executionID, int $taskID): void
    {
        $this->loadModel('action');
        $members = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution', 'nodeleted');

        $task = $this->task->getByID($taskID);
        /* Compute next assignedTo. */
        if(!empty($task->team) && strpos('done,cencel,closed', $task->status) === false)
        {
            $task->nextUser = $this->task->getAssignedTo4Multi($task->team, $task, 'next');
            $members = $this->task->getMemberPairs($task);
        }

        if(!isset($members[$task->assignedTo])) $members[$task->assignedTo] = $task->assignedTo;
        if(isset($members['closed']) || $task->status == 'closed') $members['closed'] = 'Closed';

        $this->view->title      = $this->view->execution->name . $this->lang->colon . $this->lang->task->assign;
        $this->view->position[] = $this->lang->task->assign;
        $this->view->task       = $task;
        $this->view->members    = $members;
        $this->view->users      = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * 返回看板下响应。
     * Reposn from kanban.
     *
     * @param  object $task
     * @param  string $from        ''|taskkanban
     * @access protected
     * @return int
     */
    protected function reponseKanban(object $task, string $from): int
    {
        $execution    = $this->execution->getByID($task->execution);
        $execLaneType = $this->session->execLaneType ? $this->session->execLaneType : 'all';
        $execGroupBy  = $this->session->execGroupBy ? $this->session->execGroupBy : 'default';

        $inLiteKanban = $this->config->vision == 'lite' && $this->app->tab == 'project' && $this->session->kanbanview == 'kanban';
        if(($this->app->tab == 'execution' || $inLiteKanban) && $execution->type == 'kanban')
        {
            $rdSearchValue = $this->session->rdSearchValue ? $this->session->rdSearchValue : '';
            $kanbanData    = $this->loadModel('kanban')->getRDKanban($task->execution, $execLaneType, 'id_desc', 0, $execGroupBy, $rdSearchValue);
            $kanbanData    = json_encode($kanbanData);

            return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban({$kanbanData})"));
        }
        if($from == 'taskkanban')
        {
            $taskSearchValue = $this->session->taskSearchValue ? $this->session->taskSearchValue : '';
            $kanbanData      = $this->loadModel('kanban')->getExecutionKanban($task->execution, $execLaneType, $execGroupBy, $taskSearchValue);
            $kanbanType      = $execLaneType == 'all' ? 'task' : key($kanbanData);
            $kanbanData      = $kanbanData[$kanbanType];
            $kanbanData      = json_encode($kanbanData);

            return print(js::closeModal('parent.parent', '', "parent.parent.updateKanban(\"task\", {$kanbanData})"));
        }
        return print(js::closeModal('parent.parent', 'this'));
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
     * 处理创建任务的请求数据。
     * Process the request data for the creation task.
     *
     * @param  int     $executionID
     * @param  object  $formData
     * @access protected
     * @return object
     */
    protected function prepareTask4Create(int $executionID, object $formData): object
    {
        $rawData   = $formData->rawdata;
        $execution = $this->dao->findById($rawData->execution)->from(TABLE_EXECUTION)->fetch();
        $team      = !empty($rawData->team) ? array_filter($rawData->team) : array();
        $task      = $formData->setDefault('execution', $executionID)
            ->setDefault('project', $this->task->getProjectID($executionID))
            ->setIF($rawData->estimate != false, 'left', $rawData->estimate)
            ->setIF(isset($rawData->story), 'storyVersion', isset($rawData->story) ? $this->loadModel('story')->getVersion($rawData->story) : 0)
            ->setIF(empty($rawData->multiple) || count($team) < 1, 'mode', '')
            ->setIF($execution && ($execution->lifetime == 'ops' || in_array($execution->attribute, array('request', 'review'))), 'story', 0)
            ->stripTags($this->config->task->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->get();
        if(empty($formData->estStarted)) $task->estStarted = null;
        if(empty($formData->deadline)) $task->deadline = null;

        /* Processing image link. */
        return $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], $rawData->uid);
    }

    /**
     * 处理批量创建任务的请求数据。
     * Process the request data for batch create tasks.
     *
     * @param  object  $execution
     * @param  object  $formData
     * @access protected
     * @return object[]|false
     */
    protected function prepareTasks4BatchCreate(object $execution, object $formData): array|false
    {
        /* 去除重复数据。 */
        $tasks = $this->removeDuplicate4BatchCreate($execution, $formData);
        if(!$tasks) return false;

        /* Init. */
        $story      = 0;
        $module     = 0;
        $type       = '';
        $assignedTo = '';
        $estStarted = null;
        $deadline   = null;

        /* Get task data. */
        $this->loadModel('common');
        $extendFields = $this->task->getFlowExtendFields();
        $data         = array();
        foreach($formData->name as $i => $name)
        {
            /* 给同上的变量赋值。 */
            $story      = !isset($tasks->story[$i]) || $tasks->story[$i] == 'ditto'            ? $story      : $tasks->story[$i];
            $module     = !isset($tasks->module[$i]) || $tasks->module[$i] == 'ditto'          ? $module     : $tasks->module[$i];
            $type       = !isset($tasks->type[$i]) || $tasks->type[$i] == 'ditto'              ? $type       : $tasks->type[$i];
            $assignedTo = !isset($tasks->assignedTo[$i]) || $tasks->assignedTo[$i] == 'ditto'  ? $assignedTo : $tasks->assignedTo[$i];
            $estStarted = !isset($tasks->estStarted[$i]) || isset($tasks->estStartedDitto[$i]) ? $estStarted : $tasks->estStarted[$i];
            $deadline   = !isset($tasks->deadline[$i]) || isset($tasks->deadlineDitto[$i])     ? $deadline   : $tasks->deadline[$i];

            /* 检查任务名称为空的数据。 */
            if(empty($tasks->name[$i]))
            {
                if($this->common->checkValidRow('task', $tasks, $i))
                {
                    dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->name);
                    return false;
                }
                continue;
            }

            /* 构建任务数据。 */
            $dittoFields = array('story' => $story, 'module' => $module, 'type' => $type, 'assignedTo' => $assignedTo, 'estStarted' => $estStarted, 'deadline' => $deadline);
            $data[$i]    = $this->constructData4BatchCreate($execution, $tasks, $i, $dittoFields, $extendFields);
        }

        return $data;
    }

    /**
     * 在批量创建之前移除post数据中重复的数据。
     * Remove the duplicate data before batch create tasks.
     *
     * @param  object      $execution
     * @param  object      $tasks
     * @access protected
     * @return object|false
     */
    protected function removeDuplicate4BatchCreate(object $execution, object $tasks): object|false
    {
        $storyIDs  = array();
        $taskNames = array();
        $preStory  = 0;

        foreach($tasks->story as $key => $storyID)
        {
            /* 过滤事务型和任务名称为空的数据。 */
            if(empty($tasks->name[$key])) continue;
            if($tasks->type[$key] == 'affair') continue;
            if($tasks->type[$key] == 'ditto' && isset($tasks->type[$key - 1]) && $tasks->type[$key - 1] == 'affair') continue;

            if($storyID == 'ditto') $storyID = $preStory;
            $preStory = $storyID;

            if(!isset($tasks->story[$key - 1]) && $key > 1 && !empty($tasks->name[$key - 1]))
            {
                $storyIDs[]  = 0;
                $taskNames[] = $tasks->name[$key - 1];
            }

            /* 判断Post传过来的任务有没有重复数据。 */
            $hasExistsName = in_array($tasks->name[$key], $taskNames);
            if($hasExistsName && in_array($storyID, $storyIDs))
            {
                dao::$errors['message'][] = sprintf($this->lang->duplicate, $this->lang->task->common) . ' ' . $tasks->name[$key];
                return false;
            }

            $storyIDs[]  = $storyID;
            $taskNames[] = $tasks->name[$key];
        }

        /* 去重并赋值。 */
        $result = $this->loadModel('common')->removeDuplicate('task', $tasks, "execution={$execution->id} and story " . helper::dbIN($storyIDs));
        return $result['data'];
    }

    /**
     * 批量创建任务之前构造数据。
     * Construct data before batch create tasks.
     *
     * @param  object     $execution
     * @param  object     $tasks
     * @param  int        $index
     * @param  array      $dittoFields
     * @param  array      $extendFields
     * @access protected
     * @return object
     */
    protected function constructData4BatchCreate(object $execution, object $tasks, int $index, array $dittoFields, array $extendFields): object
    {
        extract($dittoFields);
        $now = helper::now();

        $task             = new stdclass();
        $task->story      = (int)$story;
        $task->type       = $type;
        $task->module     = (int)$module;
        $task->assignedTo = $assignedTo;
        $task->color      = $tasks->color[$index];
        $task->name       = $tasks->name[$index];
        $task->desc       = nl2br($tasks->desc[$index]);
        $task->pri        = $tasks->pri[$index];
        $task->estimate   = $tasks->estimate[$index] ? $tasks->estimate[$index] : 0;
        $task->left       = $tasks->estimate[$index] ? $tasks->estimate[$index] : 0;
        $task->project    = $execution->project;
        $task->execution  = $execution->id;
        $task->estStarted = $estStarted;
        $task->deadline   = $deadline;
        $task->status     = 'wait';
        $task->openedBy   = $this->app->user->account;
        $task->openedDate = $now;
        $task->parent     = $tasks->parent[$index];
        $task->vision     = isset($tasks->vision[$index]) ? $tasks->vision[$index] : 'rnd';
        $task->version    = 1;
        if($story) $task->storyVersion = (int)$this->dao->findById($task->story)->from(TABLE_STORY)->fetch('version');
        if($assignedTo) $task->assignedDate = $now;
        if(strpos($this->config->task->create->requiredFields, 'estStarted') !== false && empty($estStarted)) $task->estStarted = '';
        if(strpos($this->config->task->create->requiredFields, 'deadline') !== false && empty($deadline))     $task->deadline   = '';
        if(isset($tasks->lanes[$index])) $task->laneID = $tasks->lanes[$index];

        /* 附加工作流字段。 */
        foreach($extendFields as $extendField)
        {
            $task->{$extendField->field} = $tasks->{$extendField->field}[$index];
            if(is_array($task->{$extendField->field})) $task->{$extendField->field} = join(',', $task->{$extendField->field});

            $task->{$extendField->field} = htmlSpecialString($task->{$extendField->field});
        }

        return $task;
    }

    /**
     * 检查传入的创建数据是否符合要求。
     * Check if the input post meets the requirements.
     *
     * @param  int     $executionID
     * @param  float   $estimate
     * @param  string  $estStarted
     * @param  string  $deadline
     * @access protected
     * @return bool
     */
    protected function checkCreate(int $executionID, float $estimate, string $estStarted, string $deadline): bool
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
        if(!helper::isZeroDate($deadline) && $estStarted > $deadline)
        {
            dao::$errors['deadline'] = $this->lang->task->error->deadlineSmall;
            return false;
        }

        return !dao::isError();
    }

    /**
     * 检查规定时间内是否创建了同名任务。
     * Check whether a task with the same name is created within the specified time.
     *
     * @param  object    $task
     * @access protected
     * @return int
     */
    protected function checkDuplicateName($task): int
    {
        /* Check duplicate task. */
        if($task->type == 'affair' || !$task->name) return 0;
        $result = $this->loadModel('common')->removeDuplicate('task', $task, "execution={$task->execution} and story=" . (int)$task->story . (isset($task->feedback) ? " and feedback=" . (int)$task->feedback : ''));
        if($result['stop']) return zget($result, 'duplicate', 0);
        return 0;
    }

    /**
     * 检查关联需求的测试类型任务数据格式是否符合要求。
     * Check if the test type task data format of the linked stories meets the requirements.
     *
     * @param  object[]  $tasks
     * @access protected
     * @return bool
     */
    protected function checkTestTasks(array $tasks): bool
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
        return !dao::isError();
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
        if($this->viewType == 'json' || (defined('RUN_MODE') && RUN_MODE == 'api')) return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $task->id);

        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;

        /* Send Webhook notifications. */
        $message = $this->executeHooks($task->id);
        if($message) $response['message'] = $message;

        /* Processing the return information of pop-up windows. */
        if(isonlybody())
        {
            /* If it is Kanban execution, refresh the Kanban statically through callback. */
            if($this->app->tab == 'execution' || $this->config->vision == 'lite')
            {
                $kanbanData = $this->getKanbanData($execution);
                $response['closeModal'] = true;
                $response['callback']   = $execution->type == 'kanban' ? "parent.updateKanban({$kanbanData}, 0)" : "parent.updateKanban(\"task\", {$kanbanData})";
                return $response;
            }
            $response['locate'] = 'parent';
            return $response;
        }

        /* Locate the browser. */
        if($this->app->getViewType() == 'xhtml')
        {
            $response['locate'] = $this->createLink('task', 'view', "taskID={$task->id}", 'html');
            return $response;
        }

        /* Process the return information for selecting a jump after creation. */
        return $this->generalCreateResponse($task, $execution->id, $afterChoice);
    }

    /**
     * 处理创建后选择跳转的返回信息。
     * Process the return information for selecting a jump after creation.
     *
     * @param  object    $task
     * @param  int       $executionID
     * @param  string    $afterChoice
     * @access protected
     * @return array
     */
    protected function generalCreateResponse(object $task, int $executionID, string $afterChoice): array
    {
        /* Set the universal return value. */
        $response['result']  = 'success';
        $response['message'] = $this->lang->saveSuccess;
        $response['locate']  = $this->createLink('execution', 'browse', "executionID={$executionID}&tab=task");

        /* Set the response to continue adding task to story. */
        $executionID = $task->execution;
        if($afterChoice == 'continueAdding')
        {
            $storyID  = $task->story ? $task->story : 0;
            $moduleID = $task->module ? $task->module : 0;
            $response['message'] = $this->lang->task->successSaved . $this->lang->task->afterChoices['continueAdding'];
            $response['locate']  = $this->createLink('task', 'create', "executionID={$executionID}&storyID={$storyID}&moduleID={$moduleID}");
        }
        /* Set the response to return task list. */
        elseif($afterChoice == 'toTaskList')
        {
            setcookie('moduleBrowseParam',  0, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);
            $response['locate'] = $this->createLink('execution', 'task', "executionID={$executionID}&status=unclosed&param=0&orderBy=id_desc");
        }
        /* Set the response to return story list. */
        elseif($afterChoice == 'toStoryList')
        {
            $response['locate'] = $this->createLink('execution', 'story', "executionID={$executionID}");
            if($this->config->vision == 'lite')
            {
                $projectID = $this->execution->getProjectID($executionID);
                $response['locate'] = $this->createLink('projectstory', 'story', "projectID={$projectID}");
            }
        }

        return $response;
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
    protected function assignCreateVars(object $execution, int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID, array $output): void
    {
        /* Get information about the task. */
        $executionID = $execution->id;
        $task        = $this->setTaskByObjectID($storyID, $moduleID, $taskID, $todoID, $bugID);

        /* Get module information. */
        $showAllModule    = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $moduleOptionMenu = $this->tree->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');
        if(!$storyID && !isset($moduleOptionMenu[$task->module])) $task->module = 0;

        /* Display relevant variables. */
        $this->assignExecution4Create($execution);
        $this->assignStory4Create($executionID);
        if($execution->type == 'kanban') $this->assignKanban4Create($executionID, $output);

        /* Set Custom fields. */
        foreach(explode(',', $this->config->task->customCreateFields) as $field) $customFields[$field] = $this->lang->task->$field;

        $this->view->title            = $execution->name . $this->lang->colon . $this->lang->task->create;
        $this->view->customFields     = $customFields;
        $this->view->showAllModule    = $showAllModule;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->showFields       = $this->config->task->custom->createFields;
        $this->view->gobackLink       = (isset($output['from']) && $output['from'] == 'global') ? $this->createLink('execution', 'task', "executionID={$executionID}") : '';
        $this->view->execution        = $execution;
        $this->view->task             = $task;
        $this->view->storyID          = $storyID;
        $this->view->blockID          = isonlybody() ? $this->loadModel('block')->getSpecifiedBlockID('my', 'assingtome', 'assingtome') : 0;

        $this->display();
    }

    /**
     * 通过传入的对象ID设置任务信息。
     * Set task through the input object ID.
     *
     * @param  int       $storyID
     * @param  int       $moduleID
     * @param  int       $taskID
     * @param  int       $todoID
     * @param  int       $bugID
     * @access protected
     * @return object
     */
    protected function setTaskByObjectID(int $storyID, int $moduleID, int $taskID, int $todoID, int $bugID): object
    {
        $task = $this->config->task->create->template;
        $task->module = $moduleID;

        /* If exist task, copy task information by task id. */
        if($taskID)
        {
            /* Emptying consumed hours when copy task. */
            $task = $this->task->getByID($taskID);
            if($task->mode == 'multi')
            {
                foreach($task->team as $teamMember) $teamMember->consumed = 0;
            }
        }

        /* If exist todo, copy todo information by todo id. */
        if($todoID)
        {
            $todo = $this->loadModel('todo')->getById($todoID);
            $task->name = $todo->name;
            $task->pri  = $todo->pri;
            $task->desc = $todo->desc;
        }

        /* If exist bug, copy bug information by bug id. */
        if($bugID)
        {
            $bug = $this->loadModel('bug')->getById($bugID);
            $task->name       = $bug->title;
            $task->pri        = !empty($bug->pri) ? $bug->pri : $this->config->task->default->pri;
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
     * 设置创建页面展示的执行相关数据。
     * Set the execution-related data for the create page display.
     *
     * @param  object    $execution
     * @access protected
     * @return void
     */
    protected function assignExecution4Create(object $execution): void
    {
        $projectID     = $execution ? $execution->project : 0;
        $lifetimeList  = array();
        $attributeList = array();
        if($projectID)
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
     * 设置创建页面展示的需求相关数据。
     * Set the stories related data for the create page display.
     *
     * @param  int       $executionID
     * @access protected
     * @return void
     */
    protected function assignStory4Create(int $executionID): void
    {
        $stories         = $this->story->getExecutionStoryPairs($executionID, 0, 'all', '', '', 'active');
        $testStoryIdList = $this->loadModel('story')->getTestStories(array_keys($stories), $executionID);
        $testStories     = array();
        foreach($stories as $testStoryID => $storyTitle)
        {
            if(empty($testStoryID) || isset($testStoryIdList[$testStoryID])) continue;
            $testStories[$testStoryID] = $storyTitle;
        }
        $this->view->testStories     = $testStories;
        $this->view->testStoryIdList = $testStoryIdList;
        $this->view->stories         = $stories;
    }

    /**
     * 设置创建页面展示的看板相关数据。
     * Set Kanban related data for create page display.
     *
     * @param  int       $executionID
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function assignKanban4Create(int $executionID, array $output): void
    {
        $this->loadModel('kanban');

        $regionID    = (int)$output['regionID'];
        $laneID      = (int)$output['laneID'];
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
     * 准备创建任务前的数据信息。
     * Prepare the data before create the task.
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
        /* Check if the input post data meets the requirements. */
        $result = $this->checkCreate($executionID, $estimate, $estStarted, $deadline);
        if(!$result) return false;

        /* Process the request data for the creation task. */
        $formData = form::data($this->config->task->form->create);
        $task     = $this->prepareTask4Create($executionID, $formData);

        /* Prepare to create the data for the test subtask and to check the data format. */
        $testTasks = array();
        if($selectTestStory && $task->type == 'test')
        {
            $testTasks = $this->prepareTestTasks4Create($executionID, $formData);
            $result    = $this->checkTestTasks($testTasks);
            if(!$result) return false;
        }

        /* Check whether a task with the same name is created within the specified time. */
        $duplicateTaskID = $this->checkDuplicateName($task);

        return array($task, $testTasks, $duplicateTaskID);
    }

    /**
     * 处理关联需求的测试子任务的请求数据。
     * Process request data for test subtasks related to stories.
     *
     * @param  int       $executionID
     * @param  object    $formData
     * @access protected
     * @return array|bool
     */
    protected function prepareTestTasks4Create(int $executionID, object $formData): array|bool
    {
        /* Set data for the type of test task that has linked stories. */
        $testTasks = array();
        $rawData   = $formData->rawdata;
        foreach($rawData->testStory as $key => $storyID)
        {
            if(empty($storyID)) continue;

            /* Process the ditto option as a concrete value. */
            $estStarted = !isset($rawData->testEstStarted[$key]) || (isset($rawData->estStartedDitto[$key]) && $rawData->estStartedDitto[$key] == 'on') ? $estStarted : $rawData->testEstStarted[$key];
            $deadline   = !isset($rawData->testDeadline[$key]) || (isset($rawData->deadlineDitto[$key]) && $rawData->deadlineDitto[$key] == 'on') ? $deadline : $rawData->testDeadline[$key];
            $assignedTo = !isset($rawData->testAssignedTo[$key]) || $rawData->testAssignedTo[$key] == 'ditto' ? $assignedTo : $rawData->testAssignedTo[$key];

            /* Set task data. */
            $task = new stdclass();
            $task->execution  = $executionID;
            $task->story      = $storyID;
            $task->pri        = $rawData->testPri[$key];
            $task->estStarted = $estStarted;
            $task->deadline   = $deadline;
            $task->assignedTo = $assignedTo;
            $task->estimate   = (float)$rawData->testEstimate[$key];
            $task->left       = (float)$rawData->testEstimate[$key];

            $testTasks[$storyID] = $task;
        }
        return $testTasks;
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
     * @return  array
     */
    protected function getCustomFields(object $execution, string $action): array
    {
        /* 设置自定义字段列表。 */
        $customFormField = 'custom' . ucfirst($action). 'Fields';
        foreach(explode(',', $this->config->task->{$customFormField}) as $field)
        {
            if($execution->type == 'stage' && strpos('estStarted,deadline', $field) !== false) continue;
            $customFields[$field] = $this->lang->task->$field;
        }

        /* 设置已勾选的自定义字段。 */
        $showFields = $this->config->task->custom->{$action . 'Fields'};
        if($execution->lifetime == 'ops' || $execution->attribute == 'request' || $execution->attribute == 'review')
        {
            unset($customFields['story']);
            $showFields = str_replace(',story,', ',', ",{$showFields},");
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
        /* 获取区域和泳道下拉数据，并设置区域和泳道的默认值。 */
        if($execution->type == 'kanban') $this->assignKanbanRelatedVars($execution->id, $output);

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
        elseif($this->app->tab == 'project' && $execution->multiple)
        {
            $link = $this->createLink('project', 'execution', "browseType=all&projectID={$execution->project}");
        }
        else
        {
            $link = $this->createLink('execution', 'browse', "executionID={$execution->id}");
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
