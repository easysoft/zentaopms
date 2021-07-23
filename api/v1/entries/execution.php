<?php
/**
 * 禅道API的execution资源类
 * 版本V1
 *
 * The execution entry point of zentaopms
 * Version 1
 */
class executionEntry extends Entry
{
    public function get($executionID)
    {
        $control = $this->loadController('execution', 'view');
        $control->view($executionID);

        $data      = $this->getData();
        $execution = $data->data->execution;
        $this->send(200, $this->format($execution, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }

    public function put($executionID)
    {
        $oldExecution = $this->loadModel('execution')->getByID($executionID);

        /* Set $_POST variables. */
        $fields = 'project,code,name,begin,end,lifetime,desc,days,acl';
        $this->batchSetPost($fields, $oldExecution);

        $this->setPost('whitelist', $this->request('whitelist', explode(',', $oldExecution->whitelist)));

        $control = $this->loadController('execution', 'edit');
        $control->edit($executionID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $execution = $this->execution->getByID($executionID);
        $this->send(200, $this->format($execution, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }

    public function delete($executionID)
    {
        $control = $this->loadController('execution', 'delete');
        $control->delete($executionID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
