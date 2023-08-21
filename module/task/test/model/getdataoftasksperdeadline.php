#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerDeadline();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerDeadline())) && p()                        && e('30');           // 按照截止日期统计的任务数量
r($taskModule->getDataOfTasksPerDeadline())        && p('2021-01-30:name,value') && e('2021-01-30,1'); // 统计截止日期为2021-01-30的任务数量
