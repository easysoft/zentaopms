#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->getByList();
cid=1
pid=1

根据taskID查找任务详情 >> 开发任务61

*/

$taskID = '51';

$task = new taskTest();
r($task->getByListTest($taskID)) && p('51:name') && e('开发任务61'); //根据taskID查找任务详情
