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
