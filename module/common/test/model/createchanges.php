#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('task')->gen(2);

/**

title=测试 commonModel::createChanges();
timeout=0
cid=15666

- 查看任务1和任务2的差异字段数量 @14
- 查看任务1和任务2的差异字段8
 - 第8条的field属性 @estimate
 - 第8条的old属性 @0.00
 - 第8条的new属性 @1.00
- 查看任务1和任务2的差异字段12
 - 第12条的field属性 @status
 - 第12条的old属性 @wait
 - 第12条的new属性 @doing

*/

global $tester;
$tester->loadModel('task');

$task1 = $tester->task->fetchById(1);
$task2 = $tester->task->fetchById(2);

$changes = common::createChanges($task1, $task2);

r(count($changes)) && p()                   && e('14');                 // 查看任务1和任务2的差异字段数量
r($changes)        && p('8:field,old,new')  && e('estimate,0.00,1.00'); // 查看任务1和任务2的差异字段8
r($changes)        && p('12:field,old,new') && e('status,wait,doing');  // 查看任务1和任务2的差异字段12
