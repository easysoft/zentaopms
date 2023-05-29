#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('task')->config('task')->gen(12);

/**

title=taskModel->getTasksByModule();
timeout=0
cid=1

*/

$moduleIdList = array(array(1), array(1, 2, 3));

$taskModel        = $tester->loadModel('task');
$emptyTasks       = $taskModel->getTasksByModule();
$tasksByExecution = $taskModel->getTasksByModule(3);
$tasksByOrder     = $taskModel->getTasksByModule(3, array(), 'id_asc');
$tasksByLimit     = $taskModel->getTasksByModule(3, array(), 'id_desc');

$tasksByModule[] = $taskModel->getTasksByModule(3, $moduleIdList[0]);
$tasksByModule[] = $taskModel->getTasksByModule(3, $moduleIdList[1]);
$tasksByModule[] = $taskModel->getTasksByModule(3, $moduleIdList[0], 'id_asc');

r(count($emptyTasks))       && p() && e('0'); // 获取执行ID为0的任务数
r(count($tasksByExecution)) && p() && e('9'); // 获取执行ID为3的任务数
r(count($tasksByModule[0])) && p() && e('3'); // 获取执行ID为3，moduleIdList为1的任务数
r(count($tasksByModule[1])) && p() && e('6'); // 获取执行ID为3，moduleIdList为1-3的任务数

r(implode(',', array_keys($tasksByOrder)))     && p() && e('0,1,2,3,4,5,6,7,8'); // 获取执行ID为3，排序为id正序的任务
r(implode(',', array_keys($tasksByModule[2]))) && p() && e('0,1,2');             // 获取执行ID为3，，moduleIdList为1，排序为id正序的任务
