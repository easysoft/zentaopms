#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getTasksByModule();
cid=1
pid=1

根据模块获取任务列表 >> 子任务10
根据模块获取任务数量 >> 1

*/

$executionID  = '101';
$moduleIdList = '21';

$count = array('0','1');

$task = new taskTest();
r($task->getTasksByModuleTest($executionID,$moduleIdList,$count[0])) && p('0:name') && e('子任务10'); //根据模块获取任务列表
r($task->getTasksByModuleTest($executionID,$moduleIdList,$count[1])) && p()         && e('1');        //根据模块获取任务数量