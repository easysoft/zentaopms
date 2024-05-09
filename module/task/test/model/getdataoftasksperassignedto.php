#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(10);
zenData('task')->loadYaml('task')->gen(30);

su('admin');

/**

title=taskModel->getDataOfTasksPerAssignedTo();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerAssignedTo())) && p()                   && e('2');        // 统计指派给的人数
r($taskModule->getDataOfTasksPerAssignedTo())        && p('admin:name,value') && e('admin,10'); // 统计指派给为admin的任务数量
