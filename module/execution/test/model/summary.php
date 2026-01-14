#!/usr/bin/env php
<?php

/**

title=测试 executionModel::summary();
timeout=0
cid=16369

- 执行executionTest模块的summaryTest方法，参数是$emptyTasks  @本页共 <strong>0</strong> 个任务，未开始 <strong>0</strong>，进行中 <strong>0</strong>，总预计 <strong>0</strong> 工时，已消耗 <strong>0</strong> 工时，剩余 <strong>0</strong> 工时。
- 执行executionTest模块的summaryTest方法，参数是$singleTasks  @本页共 <strong>1</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>0</strong>，总预计 <strong>8</strong> 工时，已消耗 <strong>2</strong> 工时，剩余 <strong>6</strong> 工时。
- 执行executionTest模块的summaryTest方法，参数是$multiTasks  @本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>1</strong>，总预计 <strong>15</strong> 工时，已消耗 <strong>8</strong> 工时，剩余 <strong>7</strong> 工时。
- 执行executionTest模块的summaryTest方法，参数是$parentChildTasks  @本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>2</strong>，总预计 <strong>0</strong> 工时，已消耗 <strong>0</strong> 工时，剩余 <strong>0</strong> 工时。
- 执行executionTest模块的summaryTest方法，参数是$cancelClosedTasks  @本页共 <strong>3</strong> 个任务，未开始 <strong>0</strong>，进行中 <strong>1</strong>，总预计 <strong>18</strong> 工时，已消耗 <strong>9</strong> 工时，剩余 <strong>3</strong> 工时。
- 执行executionTest模块的summaryTest方法，参数是$fullTasks  @本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>5</strong> 工时，已消耗 <strong>0</strong> 工时，剩余 <strong>5</strong> 工时。
- 执行executionTest模块的summaryTest方法，参数是$edgeTasks  @本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>1</strong>，总预计 <strong>100.6</strong> 工时，已消耗 <strong>50.3</strong> 工时，剩余 <strong>50.3</strong> 工时。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$executionTest = new executionModelTest();

// 测试步骤1：空任务数组统计
$emptyTasks = array();
r($executionTest->summaryTest($emptyTasks)) && p() && e('本页共 <strong>0</strong> 个任务，未开始 <strong>0</strong>，进行中 <strong>0</strong>，总预计 <strong>0</strong> 工时，已消耗 <strong>0</strong> 工时，剩余 <strong>0</strong> 工时。');

// 测试步骤2：单个普通任务统计
$singleTask = new stdClass();
$singleTask->status = 'wait';
$singleTask->isParent = '0';
$singleTask->estimate = 8.0;
$singleTask->consumed = 2.0;
$singleTask->left = 6.0;
$singleTasks = array($singleTask);
r($executionTest->summaryTest($singleTasks)) && p() && e('本页共 <strong>1</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>0</strong>，总预计 <strong>8</strong> 工时，已消耗 <strong>2</strong> 工时，剩余 <strong>6</strong> 工时。');

// 测试步骤3：多个不同状态任务统计
$task1 = new stdClass();
$task1->status = 'wait';
$task1->isParent = '0';
$task1->estimate = 4.0;
$task1->consumed = 0.0;
$task1->left = 4.0;

$task2 = new stdClass();
$task2->status = 'doing';
$task2->isParent = '0';
$task2->estimate = 6.0;
$task2->consumed = 3.0;
$task2->left = 3.0;

$task3 = new stdClass();
$task3->status = 'done';
$task3->isParent = '0';
$task3->estimate = 5.0;
$task3->consumed = 5.0;
$task3->left = 0.0;

$multiTasks = array($task1, $task2, $task3);
r($executionTest->summaryTest($multiTasks)) && p() && e('本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>1</strong>，总预计 <strong>15</strong> 工时，已消耗 <strong>8</strong> 工时，剩余 <strong>7</strong> 工时。');

// 测试步骤4：包含父子任务的复杂场景统计
$parentTask = new stdClass();
$parentTask->status = 'doing';
$parentTask->isParent = '1';
$parentTask->estimate = 10.0;
$parentTask->consumed = 5.0;
$parentTask->left = 5.0;

$childTask1 = new stdClass();
$childTask1->status = 'wait';
$childTask1->isParent = '0';
$childTask1->estimate = 3.0;
$childTask1->consumed = 0.0;
$childTask1->left = 3.0;

$childTask2 = new stdClass();
$childTask2->status = 'doing';
$childTask2->isParent = '0';
$childTask2->estimate = 4.0;
$childTask2->consumed = 2.0;
$childTask2->left = 2.0;

$parentTask->children = array($childTask1, $childTask2);
$parentChildTasks = array($parentTask);
r($executionTest->summaryTest($parentChildTasks)) && p() && e('本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>2</strong>，总预计 <strong>0</strong> 工时，已消耗 <strong>0</strong> 工时，剩余 <strong>0</strong> 工时。');

// 测试步骤5：包含已取消和已关闭任务的统计
$cancelTask = new stdClass();
$cancelTask->status = 'cancel';
$cancelTask->isParent = '0';
$cancelTask->estimate = 8.0;
$cancelTask->consumed = 2.0;
$cancelTask->left = 6.0;

$closedTask = new stdClass();
$closedTask->status = 'closed';
$closedTask->isParent = '0';
$closedTask->estimate = 6.0;
$closedTask->consumed = 6.0;
$closedTask->left = 0.0;

$normalTask = new stdClass();
$normalTask->status = 'doing';
$normalTask->isParent = '0';
$normalTask->estimate = 4.0;
$normalTask->consumed = 1.0;
$normalTask->left = 3.0;

$cancelClosedTasks = array($cancelTask, $closedTask, $normalTask);
r($executionTest->summaryTest($cancelClosedTasks)) && p() && e('本页共 <strong>3</strong> 个任务，未开始 <strong>0</strong>，进行中 <strong>1</strong>，总预计 <strong>18</strong> 工时，已消耗 <strong>9</strong> 工时，剩余 <strong>3</strong> 工时。');

// 测试步骤6：包含子任务的完整统计场景
$mainTask1 = new stdClass();
$mainTask1->status = 'wait';
$mainTask1->isParent = '0';
$mainTask1->estimate = 5.0;
$mainTask1->consumed = 0.0;
$mainTask1->left = 5.0;

$mainTask2 = new stdClass();
$mainTask2->status = 'doing';
$mainTask2->isParent = '1';
$mainTask2->estimate = 12.0;
$mainTask2->consumed = 6.0;
$mainTask2->left = 6.0;

$subTask1 = new stdClass();
$subTask1->status = 'done';
$subTask1->isParent = '0';
$subTask1->estimate = 4.0;
$subTask1->consumed = 4.0;
$subTask1->left = 0.0;

$subTask2 = new stdClass();
$subTask2->status = 'wait';
$subTask2->isParent = '0';
$subTask2->estimate = 3.0;
$subTask2->consumed = 0.0;
$subTask2->left = 3.0;

$mainTask2->children = array($subTask1, $subTask2);
$fullTasks = array($mainTask1, $mainTask2);
r($executionTest->summaryTest($fullTasks)) && p() && e('本页共 <strong>4</strong> 个任务，未开始 <strong>2</strong>，进行中 <strong>1</strong>，总预计 <strong>5</strong> 工时，已消耗 <strong>0</strong> 工时，剩余 <strong>5</strong> 工时。');

// 测试步骤7：混合状态任务的边界值测试
$edgeTask1 = new stdClass();
$edgeTask1->status = 'pause';
$edgeTask1->isParent = '0';
$edgeTask1->estimate = 0.0;
$edgeTask1->consumed = 0.0;
$edgeTask1->left = 0.0;

$edgeTask2 = new stdClass();
$edgeTask2->status = 'doing';
$edgeTask2->isParent = '0';
$edgeTask2->estimate = 0.1;
$edgeTask2->consumed = 0.05;
$edgeTask2->left = 0.05;

$edgeTask3 = new stdClass();
$edgeTask3->status = 'wait';
$edgeTask3->isParent = '0';
$edgeTask3->estimate = 100.5;
$edgeTask3->consumed = 50.25;
$edgeTask3->left = 50.25;

$edgeTasks = array($edgeTask1, $edgeTask2, $edgeTask3);
r($executionTest->summaryTest($edgeTasks)) && p() && e('本页共 <strong>3</strong> 个任务，未开始 <strong>1</strong>，进行中 <strong>1</strong>，总预计 <strong>100.6</strong> 工时，已消耗 <strong>50.3</strong> 工时，剩余 <strong>50.3</strong> 工时。');