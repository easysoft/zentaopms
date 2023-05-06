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
}
