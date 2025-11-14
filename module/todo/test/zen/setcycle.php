#!/usr/bin/env php
<?php

/**

title=测试 todoZen::setCycle();
timeout=0
cid=19306

- 步骤1：正常天循环属性type @cycle
- 步骤2：正常周循环属性type @cycle
- 步骤3：正常月循环属性type @cycle
- 步骤4：缺少day字段 @0
- 步骤5：空周配置 @0
- 步骤6：空日期处理属性type @cycle
- 步骤7：非整数beforeDays @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

zenData('todo')->gen(0);

su('admin');

$todoTest = new todoTest();

// 测试步骤1：正常的天循环配置
$dayTodoData = new stdClass();
$dayTodoData->date = '2024-01-01';
$dayTodoData->config = array(
    'type' => 'day',
    'day' => 5,
    'beforeDays' => 2
);
$dayTodoData->cycle = 1;
r($todoTest->setCycleTest($dayTodoData)) && p('type') && e('cycle'); // 步骤1：正常天循环

// 测试步骤2：正常的周循环配置
$weekTodoData = new stdClass();
$weekTodoData->date = '2024-01-01';
$weekTodoData->config = array(
    'type' => 'week',
    'week' => array(1, 3, 5),
    'beforeDays' => 1
);
$weekTodoData->cycle = 1;
r($todoTest->setCycleTest($weekTodoData)) && p('type') && e('cycle'); // 步骤2：正常周循环

// 测试步骤3：正常的月循环配置
$monthTodoData = new stdClass();
$monthTodoData->date = '2024-01-01';
$monthTodoData->config = array(
    'type' => 'month',
    'month' => array(1, 15, 30),
    'beforeDays' => 3
);
$monthTodoData->cycle = 1;
r($todoTest->setCycleTest($monthTodoData)) && p('type') && e('cycle'); // 步骤3：正常月循环

// 测试步骤4：缺少必需字段的天循环配置
$invalidDayTodoData = new stdClass();
$invalidDayTodoData->date = '2024-01-01';
$invalidDayTodoData->config = array(
    'type' => 'day',
    'beforeDays' => 2
);
$invalidDayTodoData->cycle = 1;
r($todoTest->setCycleTest($invalidDayTodoData)) && p() && e('0'); // 步骤4：缺少day字段

// 测试步骤5：无效输入的周循环配置
$invalidWeekTodoData = new stdClass();
$invalidWeekTodoData->date = '2024-01-01';
$invalidWeekTodoData->config = array(
    'type' => 'week',
    'week' => array(),
    'beforeDays' => 1
);
$invalidWeekTodoData->cycle = 1;
r($todoTest->setCycleTest($invalidWeekTodoData)) && p() && e('0'); // 步骤5：空周配置

// 测试步骤6：空日期的周期配置处理
$emptyDateTodoData = new stdClass();
$emptyDateTodoData->date = '0000-00-00';
$emptyDateTodoData->config = array(
    'type' => 'day',
    'day' => 3,
    'beforeDays' => 1
);
$emptyDateTodoData->cycle = 1;
r($todoTest->setCycleTest($emptyDateTodoData)) && p('type') && e('cycle'); // 步骤6：空日期处理

// 测试步骤7：非整数beforeDays配置
$invalidBeforeDaysTodoData = new stdClass();
$invalidBeforeDaysTodoData->date = '2024-01-01';
$invalidBeforeDaysTodoData->config = array(
    'type' => 'day',
    'day' => 5,
    'beforeDays' => 'abc'
);
$invalidBeforeDaysTodoData->cycle = 1;
r($todoTest->setCycleTest($invalidBeforeDaysTodoData)) && p() && e('0'); // 步骤7：非整数beforeDays