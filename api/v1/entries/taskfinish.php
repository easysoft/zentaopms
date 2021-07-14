<?php
/**
 * 禅道API的taskStat资源类
 * 版本V1
 *
 * The task entry point of zentaopms
 * Version 1
 */
class taskFinishEntry extends Entry
{
    public function post($taskID)
    {
        $task = $this->loadModel('task')->getByID($taskID);

        $fields = 'assignedTo,realStarted';
        $this->batchSetPost($fields, $task);

        $fields = 'finishedDate,comment';
        $this->batchSetPost($fields);

        $this->setPost('currentConsumed', $this->request('currentConsumed', 0));
        $this->setPost('consumed', $this->request('currentConsumed', 0) + $task->consumed);

        $control = $this->loadController('task', 'finish');
        $this->requireFields('assignedTo,currentConsumed,realStarted,finishedDate');
        $control->finish($taskID);
        
        $data = $this->getData();
        if($data->result == 'fail') return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        $this->send(200, $task);
    }
}
