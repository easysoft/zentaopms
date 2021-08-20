<?php
/**
 * 禅道API的todo资源类
 * 版本V1
 *
 * The todo entry point of zentaopms
 * Version 1
 */
class todoEntry extends entry 
{
    public function get($todoID)
    {
        $control = $this->loadController('todo', 'view');
        $control->view($todoID, $this->param('from', 'my'));

        $data = $this->getData();
        $todo  = $data->data->todo;
        $this->send(200, $this->format($todo, 'assignedDate:time,finishedDate:time,closedDate:time'));
    }

    public function put($todoID)
    {
        $oldTodo       = $this->loadModel('todo')->getByID($todoID);
        $oldTodo->date = date("Y-m-d", strtotime($oldTodo->date));

        /* Set $_POST variables. */
        $fields = 'date,type,name,pri,desc,status,begin,end,private';
        $this->batchSetPost($fields, $oldTodo);
        
        $this->setPost('idvalue', 0);

        $control = $this->loadController('todo', 'edit');
        $control->edit($todoID);

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->status)) return $this->sendError(400, 'error');

        $todo = $this->todo->getByID($todoID);
        $this->send(200, $this->format($todo, 'assignedDate:time,finishedDate:time,closedDate:time'));
    }

    public function delete($todoID)
    {
        $control = $this->loadController('todo', 'delete');
        $control->delete($todoID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
