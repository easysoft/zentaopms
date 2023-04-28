#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->getListByStory();
cid=1
pid=1

根据需求查看任务 >> 开发任务11
根据需求查看任务数量统计 >> 6

*/

$storyID = '1';
$count   = array('0','1');

$task = new taskTest();
r($task->getListByStoryTest($storyID,$count[0])) && p('1:name') && e('开发任务11'); // 根据需求查看任务
r($task->getListByStoryTest($storyID,$count[1])) && p()         && e('6');          // 根据需求查看任务数量统计
