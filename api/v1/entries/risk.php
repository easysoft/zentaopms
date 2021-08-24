<?php
/**
 * 禅道API的risk资源类
 * 版本V1
 *
 * The risk entry point of zentaopms
 * Version 1
 */
class riskEntry extends Entry
{
    public function get($riskID)
    {
        $control = $this->loadController('risk', 'view');
        $control->view($riskID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'success') $this->send(200, $this->format($data->data->risk, 'createdDate:time,editedDate:time'));
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $this->sendError(400, 'error');
    }

    public function put($riskID)
    {
        $oldRisk = $this->loadModel('risk')->getByID($riskID);

        /* Set $_POST variables. */
        $fields = 'source,name,category,strategy,status,impact,probability,rate,identifiedDate,plannedClosedDate,actualClosedDate,resolvedBy,assignedTo,prevention,remedy,resolution';
        $this->batchSetPost($fields, $oldRisk);

        $control = $this->loadController('risk', 'edit');
        $control->edit($riskID);

        $this->getData();
        $risk = $this->risk->getByID($riskID);
        $this->send(200, $this->format($risk, 'createdDate:time,editedDate:time'));
    }

    public function delete($riskID)
    {
        $control = $this->loadController('risk', 'delete');
        $control->delete($riskID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
