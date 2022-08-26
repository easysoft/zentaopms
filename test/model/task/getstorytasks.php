#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getStoryTasks();
cid=1
pid=1

根据需求查看任务 >> 开发任务11
根据需求查看任务数量统计 >> 6

*/

$storyID = '1';
$count   = array('0','1');

$task = new taskTest();
r($task->getStoryTasksTest($storyID,$count[0])) && p('1:name') && e('开发任务11'); // 根据需求查看任务
r($task->getStoryTasksTest($storyID,$count[1])) && p()         && e('6');          // 根据需求查看任务数量统计