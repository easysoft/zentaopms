<?php
/**
 * The tasks entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class tasksEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function get($executionID = 0)
    {
        if(!$executionID)
        {
            /* Get my tasks defaultly. */
            $control = $this->loadController('my', 'task');
            $control->task($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();
        }
        else
        {
            /* Get tasks by execution. */
            $control = $this->loadController('execution', 'task');
            $control->task($executionID, $this->param('status', 'all'), 0, $this->param('order', 'id_desc'), 0, $this->param('limit', 100), $this->param('page', 1));
            $data = $this->getData();
        }

        if(isset($data->status) and $data->status == 'success')
        {
            $tasks  = $data->data->tasks;
            $pager  = $data->data->pager;
            $result = array();
            foreach($tasks as $task)
            {
                if(isset($task->children)) $task->children = array_values((array)$task->children);
                $result[] = $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'tasks' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function post($executionID)
    {
        $fields = 'name,type,assignedTo,estimate,story,execution,project,module,pri,desc,estStarted,deadline,mailto,team,teamEstimate,multiple,uid';
        $this->batchSetPost($fields);

        $assignedTo = $this->request('assignedTo');
        if($assignedTo and !is_array($assignedTo)) $this->setPost('assignedTo', array($assignedTo));
        $this->setPost('execution', $executionID);

        $control = $this->loadController('task', 'create');
        $this->requireFields('name,assignedTo,type,estStarted,deadline');

        $control->create($executionID, $this->request('storyID', 0), $this->request('moduleID', 0), $this->request('copyTaskID', 0), $this->request('copyTodoID', 0));

        $data = $this->getData();
        if(!isset($data->id)) return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($data->id);

        $this->send(201, $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList'));
    }
}
