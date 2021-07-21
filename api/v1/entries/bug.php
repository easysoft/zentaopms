<?php
/**
 * 禅道API的bug资源类
 * 版本V1
 *
 * The bug entry point of zentaopms
 * Version 1
 */
class bugEntry extends entry 
{
    public function get($bugID)
    {
        $control = $this->loadController('bug', 'view');
        $control->view($bugID);

        $data = $this->getData();
        $bug  = $data->data->bug;
        $this->send(200, $this->format($bug, 'deleted:bool,activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time'));
    }

    public function put($bugID)
    {
        $oldBug = $this->loadModel('bug')->getByID($bugID);

        /* Set $_POST variables. */
        $fields = 'title,project,execution,openedBuild,assignedTo,pri,severity,type,story,resolvedBy,closedBy,resolution,product,plan,task';
        $this->batchSetPost($fields, $oldBug);

        $control = $this->loadController('bug', 'edit');
        $control->edit($bugID);

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->status)) return $this->sendError(400, 'error');

        $bug = $this->bug->getByID($bugID);
        $this->send(200, $this->format($bug, 'activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time'));
    }

    public function delete($bugID)
    {
        $control = $this->loadController('bug', 'delete');
        $control->delete($bugID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
