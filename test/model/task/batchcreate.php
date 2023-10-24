#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=测试taskModel->batchCreate();
cid=1
pid=1

测试正常的创建开发任务 >> 批量任务三
测试正常的创建开发任务 >> 『任务类型』不能为空。

*/

$executionID = '101';

$name          = array('批量任务一','批量任务二','批量任务三');
$type          = array('devel','test','design');
$normal_create = array('name' => $name, 'type' => $type);

$name             = array('异常一','异常二','异常三');
$Exception_create = array('name' => $name);

$task = new taskTest();
r($task->batchCreateObject($normal_create, $executionID))    && p('name')      && e('批量任务三');             // 测试正常的创建开发任务
r($task->batchCreateObject($Exception_create, $executionID)) && p('message:0') && e('『任务类型』不能为空。'); // 测试正常的创建开发任务

