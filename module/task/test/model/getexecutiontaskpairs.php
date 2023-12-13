#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('project')->config('project', true)->gen(3);
zdTable('task')->config('task', true)->gen(12);

/**

title=taskModel->getExecutionTaskPairs();
timeout=0
cid=1

*/

$taskModel = $tester->loadModel('task');

$emptyExecution    = $taskModel->getExecutionTaskPairs(0);
$notExistExecution = $taskModel->getExecutionTaskPairs(4);
$existExecution    = $taskModel->getExecutionTaskPairs(3);
$waitTasks         = $taskModel->getExecutionTaskPairs(3, 'wait');
$doingTasks        = $taskModel->getExecutionTaskPairs(3, 'doing');
$doneTasks         = $taskModel->getExecutionTaskPairs(3, 'done');
$cancelTasks       = $taskModel->getExecutionTaskPairs(3, 'cancel');
$closeTasks        = $taskModel->getExecutionTaskPairs(3, 'close');
$tasksByIdASC      = $taskModel->getExecutionTaskPairs(3, 'all', 'id_asc');
$tasksByIdDESC     = $taskModel->getExecutionTaskPairs(3, 'all', 'id_desc');

r(count($emptyExecution))                   && p()    && e('0');                  // 获取执行ID为0的任务数组数量
r(count($notExistExecution))                && p()    && e('0');                  // 获取执行ID不存在的任务数组数量
r(count($existExecution))                   && p()    && e('9');                  // 获取执行ID为3的任务数组数量
r(count($waitTasks))                        && p()    && e('4');                  // 获取执行ID为3的未开始任务数组数量
r(count($doingTasks))                       && p()    && e('2');                  // 获取执行ID为3的进行中任务数组数量
r(count($doneTasks))                        && p()    && e('1');                  // 获取执行ID为3的已完成任务数组数量
r(count($cancelTasks))                      && p()    && e('1');                  // 获取执行ID为3的已取消任务数组数量
r(count($closeTasks))                       && p()    && e('0');                  // 获取执行ID为3的已关闭任务数组数量
r($existExecution)                          && p('7') && e('[子] 7:开发任务17');  // 获取执行ID为3的任务数组
r(implode(',', array_keys($tasksByIdASC)))  && p()    && e('1,2,3,4,5,6,7,8,9');  // 获取执行ID为3的按照任务Id正序任务数组
r(implode(',', array_keys($tasksByIdDESC))) && p()    && e('9,8,7,6,5,4,3,2,1');  // 获取执行ID为3的按照任务Id倒序任务数组
