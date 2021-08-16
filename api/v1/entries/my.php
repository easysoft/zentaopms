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
        $info           = $this->loadModel('my')->getInfo();
        $info->products = $this->loadModel('my')->getProducts();
        $info->projects = $this->loadModel('my')->getProjects();
        $info->joinProjectCount = count($info->projects);
        $info->dynamic  = $this->loadModel('my')->getDynamic();

        if(!$info) return $this->sendError(400, $info->message);
        $this->send(200, $info);
    }
}
