#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getStoryComments();
cid=1
pid=1

根据executionID查找projectID >> 11

*/

$storyID = '2';

$task = new taskTest();
r($task->getStoryCommentsTest($storyID)) && p('comment') && e('这是一个系统日志测试备注' . $storyID); //根据executionID查找projectID
system("./ztest init");
