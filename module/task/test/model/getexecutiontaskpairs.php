#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->getExecutionTaskPairs();
cid=1
pid=1

根据执行id查出任务id与name >> 2::开发任务12

*/

$executionID = '102';

$task = new taskTest();
r($task->getExecutionTaskPairsTest($executionID)) && p('2') && e('2::开发任务12'); //根据执行id查出任务id与name
