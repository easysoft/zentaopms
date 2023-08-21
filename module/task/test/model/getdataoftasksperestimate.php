#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerEstimate();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerEstimate())) && p()               && e('11');  // 按预计工时统计的数量
r($taskModule->getDataOfTasksPerEstimate())        && p('1:name,value') && e('1,3'); // 统计预计工时为1的任务数量
