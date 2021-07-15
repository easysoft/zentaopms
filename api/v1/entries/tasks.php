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
    public function get()
    {
    }

    public function post($executionID)
    {
        $fields = 'name,type,assignedTo,estimate,story,parent,execution,module';
        $this->batchSetPost($fields);

        $control = $this->loadController('task', 'create');
        $this->requireFields('name,assignedTo,type');

        $control->create($executionID, $this->request('storyID', 0), $this->request('moduleID', 0), $this->request('copyTaskID', 0), $this->request('copyTodoID', 0));
        
        $data = $this->getData();
        if(!isset($data->id)) return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($data->id);

        $this->send(200, $task);
    }
}
