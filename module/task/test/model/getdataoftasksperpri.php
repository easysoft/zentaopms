#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerPri();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getDataOfTasksPerPri()) && p('1:name,value') && e('1,8'); //统计优先级为1的任务数量
r($taskModule->getDataOfTasksPerPri()) && p('2:name,value') && e('2,8'); //统计优先级为2的任务数量
r($taskModule->getDataOfTasksPerPri()) && p('3:name,value') && e('3,7'); //统计优先级为3的任务数量
r($taskModule->getDataOfTasksPerPri()) && p('4:name,value') && e('4,7'); //统计优先级为4的任务数量
