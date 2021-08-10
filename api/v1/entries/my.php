<?php
/**
 * 禅道API的my地盘资源类
 * 版本V1
 *
 * The bug entry point of zentaopms
 * Version 1
 */
class myEntry extends entry 
{
    public function get()
    {
        $myModel = $this->loadModel('my');

        $data = $myModel->myInfo();

        if(!$data) return $this->sendError(400, $data->message);
        $this->send(200, $data);
    }

}
