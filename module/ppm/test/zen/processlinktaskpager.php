#!/usr/bin/env php
<?php

/**

title=测试 mrZen::processLinkTaskPager();
timeout=0
cid=17269

- 执行mrTest模块的processLinkTaskPagerTest方法，参数是25, 10, 1, $tasks25 属性taskCount @10
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是25, 10, 2, $tasks25 属性taskCount @10
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是25, 10, 3, $tasks25 属性taskCount @5
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是3, 5, 1, $tasks3 属性taskCount @3
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是3, 5, 2, $tasks3
 - 属性taskCount @3
 - 属性pageID @1
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是0, 10, 1, array 属性taskCount @0
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是1, 10, 1, $task1 属性taskCount @1
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是25, 20, 1, $tasks25 属性taskCount @20
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是25, 20, 2, $tasks25 属性taskCount @5
- 执行mrTest模块的processLinkTaskPagerTest方法，参数是30, 10, 1, $tasks30 属性pageTotal @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $app;
$app->setMethodName('linkTask');

su('admin');

$mrTest = new mrZenTest();

// 准备测试数据 - 创建25个任务对象
$tasks25 = array();
for($i = 1; $i <= 25; $i++)
{
    $task = new stdclass();
    $task->id = $i;
    $task->name = "任务{$i}";
    $tasks25[$i] = $task;
}

// 准备3个任务对象
$tasks3 = array();
for($i = 1; $i <= 3; $i++)
{
    $task = new stdclass();
    $task->id = $i;
    $task->name = "任务{$i}";
    $tasks3[$i] = $task;
}

// 准备单个任务对象
$task1 = array();
$task = new stdclass();
$task->id = 1;
$task->name = "任务1";
$task1[1] = $task;

// 准备30个任务对象
$tasks30 = array();
for($i = 1; $i <= 30; $i++)
{
    $task = new stdclass();
    $task->id = $i;
    $task->name = "任务{$i}";
    $tasks30[$i] = $task;
}

r($mrTest->processLinkTaskPagerTest(25, 10, 1, $tasks25)) && p('taskCount') && e('10');
r($mrTest->processLinkTaskPagerTest(25, 10, 2, $tasks25)) && p('taskCount') && e('10');
r($mrTest->processLinkTaskPagerTest(25, 10, 3, $tasks25)) && p('taskCount') && e('5');
r($mrTest->processLinkTaskPagerTest(3, 5, 1, $tasks3)) && p('taskCount') && e('3');
r($mrTest->processLinkTaskPagerTest(3, 5, 2, $tasks3)) && p('taskCount;pageID') && e('3;1');
r($mrTest->processLinkTaskPagerTest(0, 10, 1, array())) && p('taskCount') && e('0');
r($mrTest->processLinkTaskPagerTest(1, 10, 1, $task1)) && p('taskCount') && e('1');
r($mrTest->processLinkTaskPagerTest(25, 20, 1, $tasks25)) && p('taskCount') && e('20');
r($mrTest->processLinkTaskPagerTest(25, 20, 2, $tasks25)) && p('taskCount') && e('5');
r($mrTest->processLinkTaskPagerTest(30, 10, 1, $tasks30)) && p('pageTotal') && e('3');