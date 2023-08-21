#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('task')->config('task')->gen(30);

su('admin');

/**

title=taskModel->getDataOfTasksPerStatus();
timeout=0
cid=1

*/

global $tester;
$taskModule = $tester->loadModel('task');

r($taskModule->getDataOfTasksPerStatus()) && p('done:name,value')   && e('已完成,4');  // 统计状态为已完成的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('wait:name,value')   && e('未开始,13'); // 统计状态为未开始的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('doing:name,value')  && e('进行中,7');  // 统计状态为进行中的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('cancel:name,value') && e('已取消,3');  // 统计状态为已取消的任务数量
r($taskModule->getDataOfTasksPerStatus()) && p('closed:name,value') && e('已关闭,3');  // 统计状态为已关闭的任务数量
