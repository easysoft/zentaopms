#!/usr/bin/env php
<?php

/**

title=测试 executionZen::filterGroupTasks();
timeout=0
cid=0

- allCount保持不变属性allCount @3
- 返回2个分组属性groupCount @2
- allCount减少2属性allCount @1
- 只剩1个分组属性groupCount @1
- 只保留1个分组属性groupCount @1
- allCount减少2属性allCount @1
- allCount减少2属性allCount @2
- 保留2个分组属性groupCount @2
- 多人任务只计数一次,减少1属性allCount @3
- 保留2个分组属性groupCount @2
- allCount减少2属性allCount @1
- 保留1个分组属性groupCount @1
- allCount减少3属性allCount @1
- 保留1个分组属性groupCount @1
- allCount减少2属性allCount @2
- 保留1个分组属性groupCount @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('task')->loadYaml('filtergrouptasks/task', false, 2)->gen(20);

su('admin');

$executionTest = new executionZenTest();

// 准备测试数据
$task1 = new stdClass();
$task1->id = 1;
$task1->name = 'Task1';
$task1->status = 'wait';
$task1->mode = '';

$task2 = new stdClass();
$task2->id = 2;
$task2->name = 'Task2';
$task2->status = 'doing';
$task2->mode = '';

$task3 = new stdClass();
$task3->id = 3;
$task3->name = 'Task3';
$task3->status = 'done';
$task3->mode = '';

$task4 = new stdClass();
$task4->id = 4;
$task4->name = 'Task4';
$task4->status = 'closed';
$task4->mode = '';

$task5 = new stdClass();
$task5->id = 5;
$task5->name = 'Task5';
$task5->status = 'wait';
$task5->mode = 'multi';

$task6 = new stdClass();
$task6->id = 6;
$task6->name = 'Task6';
$task6->status = 'done';
$task6->mode = 'multi';

// 测试步骤1: filter为all时不过滤
$groupTasks1 = array(0 => array($task1, $task2), 1 => array($task3));
r($executionTest->filterGroupTasksTest($groupTasks1, 'story', 'all', 3, array())) && p('allCount') && e('3'); // allCount保持不变
r($executionTest->filterGroupTasksTest($groupTasks1, 'story', 'all', 3, array())) && p('groupCount') && e('2'); // 返回2个分组

// 测试步骤2: groupBy为story且filter为linked时,移除story为0的任务
$groupTasks2 = array(0 => array($task1, $task2), 1 => array($task3));
r($executionTest->filterGroupTasksTest($groupTasks2, 'story', 'linked', 3, array())) && p('allCount') && e('1'); // allCount减少2
r($executionTest->filterGroupTasksTest($groupTasks2, 'story', 'linked', 3, array())) && p('groupCount') && e('1'); // 只剩1个分组

// 测试步骤3: groupBy为pri且filter为noset时,只保留pri为0的任务
$groupTasks3 = array(0 => array($task1), 1 => array($task2), 2 => array($task3));
r($executionTest->filterGroupTasksTest($groupTasks3, 'pri', 'noset', 3, array())) && p('groupCount') && e('1'); // 只保留1个分组
r($executionTest->filterGroupTasksTest($groupTasks3, 'pri', 'noset', 3, array())) && p('allCount') && e('1'); // allCount减少2

// 测试步骤4: groupBy为assignedTo且filter为undone时,过滤已完成任务
$groupTasks4 = array('user1' => array($task1, $task3), 'user2' => array($task2, $task4));
r($executionTest->filterGroupTasksTest($groupTasks4, 'assignedTo', 'undone', 4, array())) && p('allCount') && e('2'); // allCount减少2
r($executionTest->filterGroupTasksTest($groupTasks4, 'assignedTo', 'undone', 4, array())) && p('groupCount') && e('2'); // 保留2个分组

// 测试步骤5: groupBy为assignedTo且filter为undone时,多人任务去重处理
$groupTasks5 = array('user1' => array($task5, $task6), 'user2' => array($task5, $task6));
r($executionTest->filterGroupTasksTest($groupTasks5, 'assignedTo', 'undone', 4, array())) && p('allCount') && e('3'); // 多人任务只计数一次,减少1
r($executionTest->filterGroupTasksTest($groupTasks5, 'assignedTo', 'undone', 4, array())) && p('groupCount') && e('2'); // 保留2个分组

// 测试步骤6: groupBy为finishedBy且tasks有空键时,移除空键数据
$groupTasks6 = array('user1' => array($task1));
$tasks6 = array('' => array($task2, $task3));
r($executionTest->filterGroupTasksTest($groupTasks6, 'finishedBy', 'filtered', 3, $tasks6)) && p('allCount') && e('1'); // allCount减少2
r($executionTest->filterGroupTasksTest($groupTasks6, 'finishedBy', 'filtered', 3, $tasks6)) && p('groupCount') && e('1'); // 保留1个分组

// 测试步骤7: groupBy为closedBy且tasks有空键时,移除空键数据
$groupTasks7 = array('user1' => array($task1));
$tasks7 = array('' => array($task2, $task3, $task4));
r($executionTest->filterGroupTasksTest($groupTasks7, 'closedBy', 'filtered', 4, $tasks7)) && p('allCount') && e('1'); // allCount减少3
r($executionTest->filterGroupTasksTest($groupTasks7, 'closedBy', 'filtered', 4, $tasks7)) && p('groupCount') && e('1'); // 保留1个分组

// 测试步骤8: groupBy为assignedTo且filter为undone时,验证非多人模式任务计数
$groupTasks8 = array('user1' => array($task1, $task2, $task3, $task4));
r($executionTest->filterGroupTasksTest($groupTasks8, 'assignedTo', 'undone', 4, array())) && p('allCount') && e('2'); // allCount减少2
r($executionTest->filterGroupTasksTest($groupTasks8, 'assignedTo', 'undone', 4, array())) && p('groupCount') && e('1'); // 保留1个分组