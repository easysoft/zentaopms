<?php
/**
 * 禅道API的todo资源类
 * 版本V1
 *
 * The testcase entry point of zentaopms
 * Version 1
 */
class testcaseEntry extends entry 
{
    public function get($testcaseID)
    {
        $control = $this->loadController('testcase', 'view');
        $control->view($testcaseID, $this->param('version', 0));

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->case)) $this->sendError(400, 'error');

        $this->send(200, $this->format($data->case, 'openedDate:time,lastEditedDate:time,lastRunDate:time'));
    }

    public function delete($testcaseID)
    {
        $control = $this->loadController('testcase', 'delete');
        $control->delete($testcaseID, 'yes');

        $this->getData();

        $this->sendSuccess(200, 'success');
    }
}
