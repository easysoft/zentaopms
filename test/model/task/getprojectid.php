#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getProjectID();
cid=1
pid=1

根据executionID查找projectID >> 11

*/

$executionID = '101';

$task = new taskTest();
r($task->getProjectIDTest($executionID)) && p('project') && e('11'); //根据executionID查找projectID