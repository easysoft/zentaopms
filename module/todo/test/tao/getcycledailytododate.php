#!/usr/bin/env php
<?php

/**

title=测试 todoTao::getCycleDailyTodoDate();
timeout=0
cid=19273

- 执行todoTest模块的getCycleDailyTodoDateTest方法，参数是$todo1, $lastCycle1, $today1  @2024-01-04
- 执行todoTest模块的getCycleDailyTodoDateTest方法，参数是$todo2, $lastCycle2, $today2  @0
- 执行todoTest模块的getCycleDailyTodoDateTest方法，参数是$todo3, $lastCycle3, $today3  @2024-01-10
- 执行todoTest模块的getCycleDailyTodoDateTest方法，参数是$todo4, $lastCycle4, $today4  @0
- 执行todoTest模块的getCycleDailyTodoDateTest方法，参数是$todo5, $lastCycle5, $today5  @0
- 执行todoTest模块的getCycleDailyTodoDateTest方法，参数是$todo6, $lastCycle6, $today6  @0
- 执行todoTest模块的getCycleDailyTodoDateTest方法，参数是$todo7, $lastCycle7, $today7  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';

zenData('todo');

su('admin');

$todoTest = new todoTest();

// 测试步骤1：按天间隔且无lastCycle数据，今天与开始日期间隔等于配置天数
$todo1 = new stdclass();
$todo1->config = new stdclass();
$todo1->config->day = 3;
$todo1->config->begin = '2024-01-01';
$lastCycle1 = '';
$today1 = '2024-01-04';
r($todoTest->getCycleDailyTodoDateTest($todo1, $lastCycle1, $today1)) && p() && e('2024-01-04');

// 测试步骤2：按天间隔且无lastCycle数据，今天与开始日期间隔不等于配置天数
$todo2 = new stdclass();
$todo2->config = new stdclass();
$todo2->config->day = 3;
$todo2->config->begin = '2024-01-01';
$lastCycle2 = '';
$today2 = '2024-01-03';
r($todoTest->getCycleDailyTodoDateTest($todo2, $lastCycle2, $today2)) && p() && e('0');

// 测试步骤3：按天间隔且有lastCycle数据，今天与上次日期间隔等于配置天数
$todo3 = new stdclass();
$todo3->config = new stdclass();
$todo3->config->day = 5;
$lastCycle3 = new stdclass();
$lastCycle3->date = '2024-01-05';
$today3 = '2024-01-10';
r($todoTest->getCycleDailyTodoDateTest($todo3, $lastCycle3, $today3)) && p() && e('2024-01-10');

// 测试步骤4：按天间隔且有lastCycle数据，今天与上次日期间隔不等于配置天数
$todo4 = new stdclass();
$todo4->config = new stdclass();
$todo4->config->day = 7;
$lastCycle4 = new stdclass();
$lastCycle4->date = '2024-01-01';
$today4 = '2024-01-05';
r($todoTest->getCycleDailyTodoDateTest($todo4, $lastCycle4, $today4)) && p() && e('0');

// 测试步骤5：按天间隔模式但配置天数为0
$todo5 = new stdclass();
$todo5->config = new stdclass();
$todo5->config->day = 0;
$todo5->config->begin = '2024-01-01';
$lastCycle5 = '';
$today5 = '2024-01-01';
r($todoTest->getCycleDailyTodoDateTest($todo5, $lastCycle5, $today5)) && p() && e('0');

// 测试步骤6：仅设置指定日期且匹配日期格式（使用会匹配的日期）
$todo6 = new stdclass();
$todo6->config = new stdclass();
$todo6->config->specifiedDate = true;
$todo6->config->specify = new stdclass();
$todo6->config->specify->month = 2;
$todo6->config->specify->day = 5;
$lastCycle6 = '';
$today6 = '2024-03-05';
r($todoTest->getCycleDailyTodoDateTest($todo6, $lastCycle6, $today6)) && p() && e('0');

// 测试步骤7：仅设置指定日期且不匹配日期格式
$todo7 = new stdclass();
$todo7->config = new stdclass();
$todo7->config->specifiedDate = true;
$todo7->config->specify = new stdclass();
$todo7->config->specify->month = 2;
$todo7->config->specify->day = 15;
$lastCycle7 = '';
$today7 = '2024-03-20';
r($todoTest->getCycleDailyTodoDateTest($todo7, $lastCycle7, $today7)) && p() && e('0');