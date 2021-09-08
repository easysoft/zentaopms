<?php
/**
 * 禅道API的tasks资源类
 * 版本V1
 *
 * The tasks entry point of zentaopms
 * Version 1
 */
class tasksEntry extends entry 
{
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
            $control->task($executionID, $this->param('status', 'all'), 0, $this->param('order', ''), $this->param('total', 0), $this->param('limit', 100), $this->param('page', 1));
            $data = $this->getData();
        }

        if(isset($data->status) and $data->status == 'success')
        {
            $tasks  = $data->data->tasks;
            $pager  = $data->data->pager;
            $result = array();
            foreach($tasks as $task)
            {
                $result[] = $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'tasks' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }

    public function post($executionID)
    {
        $fields = 'name,type,assignedTo,estimate,story,parent,execution,module,pri,desc,estStarted,deadline,mailto';
        $this->batchSetPost($fields);

        $assignedTo = $this->request('assignedTo');
        if($assignedTo and !is_array($assignedTo)) $this->setPost('assignedTo', array($assignedTo));

        $control = $this->loadController('task', 'create');
        $this->requireFields('name,assignedTo,type,estStarted,deadline');

        $control->create($executionID, $this->request('storyID', 0), $this->request('moduleID', 0), $this->request('copyTaskID', 0), $this->request('copyTodoID', 0));
        
        $data = $this->getData();
        if(!isset($data->id)) return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($data->id);

        $this->send(201, $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time'));
    }
}
