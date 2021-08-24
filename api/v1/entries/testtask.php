<?php
/**
 * 禅道API的todo资源类
 * 版本V1
 *
 * The testtask entry point of zentaopms
 * Version 1
 */
class testtaskEntry extends entry 
{
    public function get($testtaskID)
    {
        $control = $this->loadController('testtask', 'view');
        $control->view($testtaskID);

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->data->task)) $this->sendError(400, 'error');

        $testtask = $data->data->task;
        $this->send(200, $this->format($testtask, 'realFinishedDate:time'));
    }

    public function delete($testtaskID)
    {
        $control = $this->loadController('testtask', 'delete');
        $control->delete($testtaskID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
