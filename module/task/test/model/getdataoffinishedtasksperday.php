#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfFinishedTasksPerDay();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfFinishedTasksPerDay())) && p()                        && e('30');           // 每日完成的数量
r($taskModule->getDataOfFinishedTasksPerDay())        && p('2021-01-30:name,value') && e('2021-01-30,1'); // 统计2021-01-30完成的任务数量
