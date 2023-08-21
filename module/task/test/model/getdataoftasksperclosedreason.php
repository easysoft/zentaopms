#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerClosedReason();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerClosedReason())) && p()                  && e('1');        // 完成原因的数量
r($taskModule->getDataOfTasksPerClosedReason())        && p('done:name,value') && e('已完成,3'); // 统计完成原因为已完成的任务数量
