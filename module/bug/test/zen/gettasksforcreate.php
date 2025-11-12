#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getTasksForCreate();
timeout=0
cid=0

- 步骤1:测试有executionID时tasks是数组 @1
- 步骤2:测试无executionID时tasks为null @1
- 步骤3:测试executionID为0时tasks为null @1
- 步骤4:测试返回对象包含tasks属性 @1
- 步骤5:测试不同executionID获取不同tasks @1
- 步骤6:测试返回对象包含原有属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$task = zenData('task');
$task->id->range('1-30');
$task->execution->range('101{10},102{10},103{10}');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10');
$task->status->range('wait{10},doing{10},done{10}');
$task->deleted->range('0');
$task->gen(30);

zenData('user')->gen(5);

$bugTest = new bugZenTest();

$bug1 = new stdClass();
$bug1->executionID = 101;

$bug2 = new stdClass();
$bug2->executionID = 0;

$bug3 = new stdClass();
$bug3->executionID = 0;

$bug4 = new stdClass();
$bug4->executionID = 102;
$bug4->productID = 1;

r(is_array($bugTest->getTasksForCreateTest($bug1)->tasks)) && p() && e('1'); // 步骤1:测试有executionID时tasks是数组
r(is_null($bugTest->getTasksForCreateTest($bug2)->tasks)) && p() && e('1'); // 步骤2:测试无executionID时tasks为null
r(is_null($bugTest->getTasksForCreateTest($bug3)->tasks)) && p() && e('1'); // 步骤3:测试executionID为0时tasks为null
r(property_exists($bugTest->getTasksForCreateTest($bug1), 'tasks')) && p() && e('1'); // 步骤4:测试返回对象包含tasks属性
r(is_array($bugTest->getTasksForCreateTest($bug4)->tasks)) && p() && e('1'); // 步骤5:测试不同executionID获取不同tasks
r(property_exists($bugTest->getTasksForCreateTest($bug4), 'productID')) && p() && e('1'); // 步骤6:测试返回对象包含原有属性