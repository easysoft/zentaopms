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
     * prepareCreateExtras
     *
     * @param object $postData
     * @param int $taskID
     * @access protected
     * @return void
     */
    protected function prepareCreateExtras(object $postDataFixer, int $taskID)
    {
        $oldTask  = $this->task->getByID($taskID);
        $now      = helper::now();
        $postData = $postDataFixer->get();
        $task     = $postDataFixer->add('id', $taskID)
            ->setIF(!$postData->assignedTo and !empty($oldTask->team) and !empty($postData->team), 'assignedTo', $this->task->getAssignedTo4Multi($postData->team, $oldTask))
            ->setIF(!$oldTask->mode and !$postData->assignedTo and !empty($postData->team), 'assignedTo', $postData->team[0])
            ->setIF(is_numeric($postData->estimate), 'estimate', (float)$postData->estimate)
            ->setIF(is_numeric($postData->consumed), 'consumed', (float)$postData->consumed)
            ->setIF(is_numeric($postData->left),     'left',     (float)$postData->left)
            ->setIF($oldTask->parent == 0 && $postData->parent == '', 'parent', 0)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'estStarted') !== false, 'estStarted', $postData->estStarted)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'deadline') !== false, 'deadline', $postData->deadline)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'estimate') !== false, 'estimate', $postData->estimate)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'left') !== false,     'left',     $postData->left)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'consumed') !== false, 'consumed', $postData->consumed)
            ->setIF(strpos($this->config->task->edit->requiredFields, 'story') !== false,    'story',    $postData->story)
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
            ->remove('comment,files,labels,uid,multiple,team,teamEstimate,teamConsumed,teamLeft,teamSource,contactListMenu')
            ->get();

        return $task;
    }

    /**
     * 编辑任务后返回响应.
     * Reponse after edit.
     *
     * @param  object $task
     * @access protected
     * @return int
     */
    protected function reponseAfterEdit(int $taskID, string $from): int
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

        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $taskID));
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
        $task = $this->task->getById($taskID);

        $tasks = $this->task->getParentTaskPairs($this->view->execution->id, $this->view->task->parent);
        if(isset($tasks[$taskID])) unset($tasks[$taskID]);

        if(!isset($this->view->members[$this->view->task->assignedTo])) $this->view->members[$this->view->task->assignedTo] = $this->view->task->assignedTo;
        if(isset($this->view->members['closed']) or $this->view->task->status == 'closed') $this->view->members['closed']  = 'Closed';

        $executions = array();
        if(!empty($task->project)) $executions = $this->execution->getByProject($task->project, 'all', 0, true);

        $taskMembers = array();
        if(!empty($task->team))
        {
            $teamAccounts = $task->members;
            foreach($teamAccounts as $teamAccount)
            {
                if(!isset($members[$teamAccount])) continue;
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
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted|noclosed', "{$this->view->task->openedBy},{$this->view->task->canceledBy},{$this->view->task->closedBy}");
        $this->view->showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $this->view->modules       = $this->tree->getTaskOptionMenu($this->view->task->execution, 0, 0, $this->view->showAllModule ? 'allModule' : '');
        $this->view->executions    = $executions;
        $this->view->contactLists  = $this->loadModel('user')->getContactLists($this->app->user->account, 'withnote');
        $this->display();
    }

    /**
     * prepareAssignToExtras
     *
     * @param object $postDataFixer
     * @param int $taskID
     * @access protected
     * @return void
     */
    protected function prepareAssignToExtras(object $postDataFixer, int $taskID)
    {
        $task = $postDataFixer->add('id', $taskID)
            ->stripTags($this->config->task->editor->assignto['id'], $this->config->allowedTags)
            ->remove('comment,showModule')
            ->get();
        return $task;
    }

    /**
     * Reponse after assignto.
     *
     * @param  int    $taskID
     * @access protected
     * @return void
     */
    protected function reponseAfterAssignTo(int $taskID): int
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
     * Return the error after assignto.
     *
     * @access protected
     * @return void
     */
    protected function errorAfterAssignTo(): int
    {
        if($this->viewType == 'json' or (defined('RUN_MODE') && RUN_MODE == 'api')) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return print(js::error(dao::getError()));
    }

    /**
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
}
