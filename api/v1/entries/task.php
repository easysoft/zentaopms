<?php
/**
 * 禅道API的task资源类
 * 版本V1
 *
 * The task entry point of zentaopms
 * Version 1
 */
class taskEntry extends Entry
{
    public function get($taskID)
    {
        $control = $this->loadController('task', 'view');
        $control->view($taskID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $task = $data->data->task;
        $this->send(200, $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time'));
    }

    public function put($taskID)
    {
        $oldTask = $this->loadModel('task')->getByID($taskID);

        /* Set $_POST variables. */
        $fields = 'name,type,assignedTo,estimate,left,consumed,story,parent,execution,module,closedReason,status,estStarted,deadline';
        $this->batchSetPost($fields, $oldTask);

        $control = $this->loadController('task', 'edit');
        $control->edit($taskID);

        $this->getData();
        $task = $this->task->getByID($taskID);
        $this->send(200, $this->format($task, 'openedDate:time,assignedDate:time,realStarted:time,finishedDate:time,canceledDate:time,closedDate:time,lastEditedDate:time'));
    }

    public function delete($taskID)
    {
        $control = $this->loadController('task', 'delete');
        $control->delete(0, $taskID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
