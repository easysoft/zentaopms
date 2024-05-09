#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('task')->loadYaml('task', true)->gen(30);
zenData('module')->loadYaml('module', true)->gen(10);
zenData('branch')->loadYaml('branch', true)->gen(10);

/**

title=taskModel->getDataOfTasksPerModule();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerModule())) && p()               && e('4');        // 按模块任务数统计的数量
r($taskModule->getDataOfTasksPerModule())        && p('2:name,value') && e('/模块2,6'); // 按模块任务数统计
