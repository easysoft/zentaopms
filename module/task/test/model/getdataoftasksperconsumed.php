#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(30);

/**

title=taskModel->getDataOfTasksPerConsumed();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r(count($taskModule->getDataOfTasksPerConsumed())) && p()               && e('3');   // 按消耗时间统计的数量
r($taskModule->getDataOfTasksPerConsumed())        && p('3:name,value') && e('3,3'); // 统计消耗工时为4的任务数量
