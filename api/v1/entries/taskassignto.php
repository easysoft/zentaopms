<?php
/**
 * 禅道API的taskAssignTo资源类
 * 版本V1
 *
 * The task entry point of zentaopms
 * Version 1
 */
class taskAssignToEntry extends Entry
{
    public function post($taskID)
    {
        $task = $this->loadModel('task')->getByID($taskID);

        $fields = 'assignedTo,comment,left';
        $this->batchSetPost($fields);

        $control = $this->loadController('task', 'assignTo');
        $this->requireFields('assignedTo');

        $control->assignTo($task->execution, $taskID);
        
        $data = $this->getData();
        if($data->result == 'fail') return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        $this->send(200, $task);
    }
}
