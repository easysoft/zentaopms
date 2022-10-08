#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerAssignedTo();
cid=1
pid=1

统计指派给为po82的任务数量 >> 研发主管82,1

*/

$task = new taskTest();
r($task->getDataOfTasksPerAssignedToTest()) && p('po82:name,value') && e('研发主管82,1'); //统计指派给为po82的任务数量