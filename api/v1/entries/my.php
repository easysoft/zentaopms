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
        $info = $this->loadModel('my')->getInfo();

        if(!$info) return $this->sendError(400, $info->message);
        $this->send(200, $info);
    }
}
