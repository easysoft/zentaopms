<?php
/**
 * 禅道API的错误处理类
 * 版本V1
 *
 * Display errors 
 * Version 1
 */
class errorEntry extends Entry 
{
    public function notFound()
    {
        $this->send(404, array('error' => 'not found'));
    }
}
