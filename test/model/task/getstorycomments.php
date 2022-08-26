#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getStoryComments();
cid=1
pid=1

根据storyID查找comment >> 这是一个系统日志测试备注2
根据不存在的storyID查找comment >> 0

*/

$storyIDList = array(2, 1000001);

$task = new taskTest();
r($task->getStoryCommentsTest($storyIDList[0])) && p('0:comment') && e('这是一个系统日志测试备注2'); //根据storyID查找comment
r($task->getStoryCommentsTest($storyIDList[1])) && p('0:comment') && e('0');                         //根据不存在的storyID查找comment