#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerFinishedBy();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerFinishedBy())) && p()                   && e('1');        // 按由谁完成统计的数量
r($taskModule->getDataOfTasksPerFinishedBy())        && p('admin:name,value') && e('admin,10'); // 完成者为admin的任务数量
