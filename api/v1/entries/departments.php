<?php
/**
 * 禅道API的departments资源类
 * 版本V1
 *
 * The departments entry point of zentaopms
 * Version 1
 */
class departmentsEntry extends entry
{
    public function get()
    {
        $depts = $this->loadModel('dept')->getDataStructure();

        return $this->send(200, $depts);
    }
}
