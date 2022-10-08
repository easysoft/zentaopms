#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getById();
cid=1
pid=1

根据taskID查找任务详情 >> 开发任务60

*/

$taskID = '50';

$task = new taskTest();
r($task->getByIdTest($taskID)) && p('name') && e('开发任务60'); //根据taskID查找任务详情