#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getUserTaskPairs();
cid=1
pid=1

根据指派人员查看执行和任务对应关系 >> 迭代3 / 开发任务13

*/

$taskID     = '3';
$assignedTo = 'user95';

$task = new taskTest();
r($task->getUserTaskPairsTest($taskID,$assignedTo)) && p('3') && e('迭代3 / 开发任务13'); // 根据指派人员查看执行和任务对应关系