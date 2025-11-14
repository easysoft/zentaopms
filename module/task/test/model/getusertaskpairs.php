#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project', true)->gen(5);
zenData('task')->loadYaml('task')->gen(12);

/**

title=taskModel->getUserTaskPairs();
timeout=0
cid=18827

- 获取指派给admin的任务属性1 @迭代 / 任务1
- 获取指派给admin的任务数量 @4
- 获取指派给user1的任务属性3 @迭代 / 任务3
- 获取指派给user1的任务数量 @2
- 获取指派给user3的任务 @0
- 获取指派给user3的任务数量 @0
- 获取指派给admin未开始的任务属性1 @迭代 / 任务1
- 获取指派给admin进行中的任务属性2 @迭代 / 任务2
- 获取指派给admin已完成的任务属性8 @看板 / 任务8
- 获取指派给admin已取消的任务 @0
- 获取指派给admin已关闭的任务 @0
- 获取指派给admin并且跳过不存在的执行ID=1的任务属性1 @迭代 / 任务1
- 获取指派给admin并且跳过执行ID in (1,2)的任务属性7 @看板 / 任务7
- 获取指派给admin并且跳过执行ID in (2,3,4)的任务 @0
- 获取指派给admin并且追加任务ID=1的任务属性1 @迭代 / 任务1
- 获取指派给admin并且追加任务ID in (8,9)的任务属性8 @看板 / 任务8
- 获取指派给admin并且跳过执行ID in (2,3,4)追加任务ID=1的任务属性1 @迭代 / 任务1
- 获取指派给admin并且跳过执行ID in (2,3,4)追加任务ID in (8,9)的任务属性8 @看板 / 任务8

*/

$accountList         = array('admin', 'user1', 'user3');
$statusList          = array('wait', 'doing', 'done', 'cancel', 'closed');
$skipExecutionIdList = array(array(1), array(1, 2), array(2, 3, 4));
$taskIdList          = array(array(1), array(8, 9));

$taskModel = $tester->loadModel('task');
r($taskModel->getUserTaskPairs($accountList[0]))        && p('1') && e('迭代 / 任务1'); // 获取指派给admin的任务
r(count($taskModel->getUserTaskPairs($accountList[0]))) && p()    && e('4');            // 获取指派给admin的任务数量
r($taskModel->getUserTaskPairs($accountList[1]))        && p('3') && e('迭代 / 任务3'); // 获取指派给user1的任务
r(count($taskModel->getUserTaskPairs($accountList[1]))) && p()    && e('2');            // 获取指派给user1的任务数量
r($taskModel->getUserTaskPairs($accountList[2]))        && p()    && e('0');            // 获取指派给user3的任务
r(count($taskModel->getUserTaskPairs($accountList[2]))) && p()    && e('0');            // 获取指派给user3的任务数量

r($taskModel->getUserTaskPairs($accountList[0], $statusList[0])) && p('1') && e('迭代 / 任务1'); // 获取指派给admin未开始的任务
r($taskModel->getUserTaskPairs($accountList[0], $statusList[1])) && p('2') && e('迭代 / 任务2'); // 获取指派给admin进行中的任务
r($taskModel->getUserTaskPairs($accountList[0], $statusList[2])) && p('8') && e('看板 / 任务8'); // 获取指派给admin已完成的任务
r($taskModel->getUserTaskPairs($accountList[0], $statusList[3])) && p()    && e('0');            // 获取指派给admin已取消的任务
r($taskModel->getUserTaskPairs($accountList[0], $statusList[4])) && p()    && e('0');            // 获取指派给admin已关闭的任务

r($taskModel->getUserTaskPairs($accountList[0], 'all', $skipExecutionIdList[0])) && p('1') && e('迭代 / 任务1'); // 获取指派给admin并且跳过不存在的执行ID=1的任务
r($taskModel->getUserTaskPairs($accountList[0], 'all', $skipExecutionIdList[1])) && p('7') && e('看板 / 任务7'); // 获取指派给admin并且跳过执行ID in (1,2)的任务
r($taskModel->getUserTaskPairs($accountList[0], 'all', $skipExecutionIdList[2])) && p()    && e('0');            // 获取指派给admin并且跳过执行ID in (2,3,4)的任务

r($taskModel->getUserTaskPairs($accountList[0], 'all', array(), $taskIdList[0]))                 && p('1') && e('迭代 / 任务1'); // 获取指派给admin并且追加任务ID=1的任务
r($taskModel->getUserTaskPairs($accountList[0], 'all', array(), $taskIdList[1]))                 && p('8') && e('看板 / 任务8'); // 获取指派给admin并且追加任务ID in (8,9)的任务
r($taskModel->getUserTaskPairs($accountList[0], 'all', $skipExecutionIdList[2], $taskIdList[0])) && p('1') && e('迭代 / 任务1'); // 获取指派给admin并且跳过执行ID in (2,3,4)追加任务ID=1的任务
r($taskModel->getUserTaskPairs($accountList[0], 'all', $skipExecutionIdList[2], $taskIdList[1])) && p('8') && e('看板 / 任务8'); // 获取指派给admin并且跳过执行ID in (2,3,4)追加任务ID in (8,9)的任务