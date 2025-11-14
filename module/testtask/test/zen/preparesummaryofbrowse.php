#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::prepareSummaryForBrowse();
timeout=0
cid=19238

- 空数组测试属性total @0
- 混合状态测试
 - 属性total @4
 - 属性wait @1
 - 属性testing @1
 - 属性blocked @1
 - 属性done @1
- 全部wait状态测试属性wait @3
- 边界情况测试
 - 属性total @2
 - 属性wait @1
 - 属性testing @1
- 大量混合状态测试
 - 属性total @20
 - 属性wait @5
 - 属性testing @5
 - 属性blocked @5
 - 属性done @5

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$testtaskZenTest = new testtaskZenTest();

// 4. 测试用例执行

// 测试步骤1：测试空数组情况
r($testtaskZenTest->prepareSummaryForBrowseTest(array())) && p('total') && e(0); // 空数组测试

// 测试步骤2：测试包含不同状态测试单的数组
$testtasks = array();
$task1 = new stdclass();
$task1->status = 'wait';
$task1->build = '1';
$task1->buildName = 'Build 1';
$testtasks[] = $task1;

$task2 = new stdclass();
$task2->status = 'doing';
$task2->build = '2';
$task2->buildName = 'Build 2';
$testtasks[] = $task2;

$task3 = new stdclass();
$task3->status = 'blocked';
$task3->build = '3';
$task3->buildName = 'Build 3';
$testtasks[] = $task3;

$task4 = new stdclass();
$task4->status = 'done';
$task4->build = '4';
$task4->buildName = 'Build 4';
$testtasks[] = $task4;

r($testtaskZenTest->prepareSummaryForBrowseTest($testtasks)) && p('total,wait,testing,blocked,done') && e('4,1,1,1,1'); // 混合状态测试

// 测试步骤3：测试所有测试单状态为wait的情况
$waitTasks = array();
for($i = 1; $i <= 3; $i++)
{
    $task = new stdclass();
    $task->status = 'wait';
    $task->build = $i;
    $task->buildName = 'Build ' . $i;
    $waitTasks[] = $task;
}
r($testtaskZenTest->prepareSummaryForBrowseTest($waitTasks)) && p('wait') && e(3); // 全部wait状态测试

// 测试步骤4：测试边界情况
$edgeTasks = array();
$edgeTask1 = new stdclass();
$edgeTask1->status = 'doing';
$edgeTask1->build = 'trunk';
$edgeTask1->buildName = '';
$edgeTasks[] = $edgeTask1;

$edgeTask2 = new stdclass();
$edgeTask2->status = 'wait';
$edgeTask2->build = '5';
$edgeTask2->buildName = '';
$edgeTasks[] = $edgeTask2;

r($testtaskZenTest->prepareSummaryForBrowseTest($edgeTasks)) && p('total,wait,testing') && e('2,1,1'); // 边界情况测试

// 测试步骤5：测试大量混合状态的测试单数组
$mixedTasks = array();
$statuses = array('wait', 'doing', 'blocked', 'done');
for($i = 1; $i <= 20; $i++)
{
    $task = new stdclass();
    $task->status = $statuses[($i - 1) % 4];
    $task->build = $i;
    $task->buildName = 'Build ' . $i;
    $mixedTasks[] = $task;
}
r($testtaskZenTest->prepareSummaryForBrowseTest($mixedTasks)) && p('total,wait,testing,blocked,done') && e('20,5,5,5,5'); // 大量混合状态测试