#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getStoryTaskCounts();
cid=1
pid=1

根据需求1查看任务数量 >> 6
根据需求5查看任务数量 >> 6
根据需求9查看任务数量 >> 6

*/

$storyIDList = array('1','5','9');

$task = new taskTest();
r($task->getStoryTaskCountsTest($storyIDList)) && p('1') && e('6'); // 根据需求1查看任务数量
r($task->getStoryTaskCountsTest($storyIDList)) && p('5') && e('6'); // 根据需求5查看任务数量
r($task->getStoryTaskCountsTest($storyIDList)) && p('9') && e('6'); // 根据需求9查看任务数量