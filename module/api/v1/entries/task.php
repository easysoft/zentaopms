<?php
/**
 * The task entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class taskEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function get($taskID)
    {
        $this->resetOpenApp($this->param('tab', 'execution'));

        $control = $this->loadController('task', 'view');
        $control->view($taskID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $task = $data->data->task;

        if(!empty($task->children)) $task->children = array_values((array)$task->children);
        if($task->parent > 0) $task->parentPri = $this->dao->select('pri')->from(TABLE_TASK)->where('id')->eq($task->parent)->fetch('pri');

        /* Set execution name */
        $task->executionName = $data->data->execution->name;

        /* Set module title */
        $moduleTitle = '';
        if(empty($task->module)) $moduleTitle = '/';
        if($task->module)
        {
            $modulePath = $data->data->modulePath;
            foreach($modulePath as $key => $module)
            {
                $moduleTitle .= $module->name;
                if(isset($modulePath[$key + 1])) $moduleTitle .= '/';
            }
        }
        $task->moduleTitle = $moduleTitle;

        $queryAccounts = array();
        if($task->assignedTo) $queryAccounts[$task->assignedTo] = $task->assignedTo;
        if(!empty($task->team))
        {
            foreach($task->team as $account => $team) $queryAccounts[$account] = $account;
        }
        $usersWithAvatar = $this->loadModel('user')->getListByAccounts($queryAccounts, 'account');

        if(!empty($task->team))
        {
            $teams = array();
            foreach($task->team as $account => $team)
            {
                $user = zget($usersWithAvatar, $account, '');
                $team->realname = $user ? $user->realname : $account;
                $team->avatar   = $user ? $user->avatar : '';
                $team->estimate = round($team->estimate, 1);
                $team->consumed = round($team->consumed, 1);
                $team->left     = round($team->left, 1);

                $allHours = $team->consumed + $team->left;
                $team->progress = empty($allHours) ? 0 : round($team->consumed / $allHours * 100, 1);

                $teams[] = $team;
            }
            $task->team = $teams;
        }

        $task->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->task);

        $preAndNext = $data->data->preAndNext;
        $task->preAndNext = array();
        $task->preAndNext['pre']  = $preAndNext->pre  ? $preAndNext->pre->id : '';
        $task->preAndNext['next'] = $preAndNext->next ? $preAndNext->next->id : '';

        $execution             = $this->loadModel('project')->getByID($task->execution, 'execution,sprint');
        $task->executionStatus = $execution->status;

        return $this->send(200, $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList'));
    }

    /**
     * PUT method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function put($taskID)
    {
        $oldTask = $this->loadModel('task')->getByID($taskID);

        /* Set $_POST variables. */
        $fields = 'name,type,desc,assignedTo,pri,estimate,left,consumed,story,parent,execution,module,closedReason,status,estStarted,deadline,team,teamEstimate,teamConsumed,teamLeft,multiple,mailto,uid';
        $this->batchSetPost($fields, $oldTask);

        if($_POST['status'] == 'done')
        {
            if(!empty($oldTask->finishedBy)) $this->setPost('finishedBy', $oldTask->finishedBy);
            if(!empty($oldTask->finishedDate)) $this->setPost('finishedDate', $oldTask->finishedDate);
        }

        $control = $this->loadController('task', 'edit');
        $control->edit($taskID);

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        $task = $this->task->getByID($taskID);
        return $this->send(200, $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function delete($taskID)
    {
        $control = $this->loadController('task', 'delete');
        $control->delete(0, $taskID, 'true');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
