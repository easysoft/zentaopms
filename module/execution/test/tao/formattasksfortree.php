#!/usr/bin/env php
<?php

/**

title=测试 executionTao::formatTasksForTree();
timeout=0
cid=16387

- 测试步骤1：空任务数组输入 @0
- 测试步骤2：单个任务格式化 @1
- 测试步骤3：验证任务type属性第0条的type属性 @task
- 测试步骤4：验证任务title属性第0条的title属性 @任务1
- 测试步骤5：验证任务status属性第0条的status属性 @wait

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

su('admin');

global $tester;
$executionModel = $tester->loadModel('execution');

// 创建任务测试数据
$tasks = array();
$task1 = new stdclass();
$task1->id = 1;
$task1->name = '任务1';
$task1->type = 'devel';
$task1->status = 'wait';
$task1->pri = 3;
$task1->estimate = 4;
$task1->consumed = 2;
$task1->left = 2;
$task1->color = '';
$task1->openedBy = 'admin';
$task1->assignedTo = 'admin';
$task1->parent = 0;
$task1->isParent = 0;
$task1->estStarted = '';
$task1->realStarted = '';
$tasks[] = $task1;

$executionTest = new executionTest();

r($executionTest->formatTasksForTreeTest(array())) && p() && e('0'); // 测试步骤1：空任务数组输入
r($executionTest->formatTasksForTreeTest($tasks)) && p() && e('1'); // 测试步骤2：单个任务格式化
r($executionTest->formatTasksForTreeTest($tasks)) && p('0:type') && e('task'); // 测试步骤3：验证任务type属性
r($executionTest->formatTasksForTreeTest($tasks)) && p('0:title') && e('任务1'); // 测试步骤4：验证任务title属性
r($executionTest->formatTasksForTreeTest($tasks)) && p('0:status') && e('wait'); // 测试步骤5：验证任务status属性