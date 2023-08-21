#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task', true)->gen(30);
zdTable('module')->config('module', true)->gen(10);
zdTable('branch')->config('branch', true)->gen(10);

/**

title=taskModel->getDataOfTasksPerModule();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerModule())) && p()               && e('4');        // 按模块任务数统计的数量
r($taskModule->getDataOfTasksPerModule())        && p('2:name,value') && e('/模块2,6'); // 按模块任务数统计
