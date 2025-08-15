#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todo.unittest.class.php';
su('admin');

function initData ()
{
    zenData('todo')->loadYaml('getcycletododate')->gen(3);
}

/**

title=测试 todoTao::getCycleTodoDate();
timeout=0
cid=1

- 获取类型为按天生成的周期待办的日期，结果1 @1
- 获取类型为按周生成的周期待办的日期，结果1 @1
- 获取类型为按月生成的周期待办的日期，结果1 @1
- 生成周期为按天生成的周期待办的日期，结果0 @0
- 生成周期为空的周期待办的日期，结果0 @0

*/

initData();

$todo = new todoTest();

r($todo->getCycleTodoDateTest('day'))   && p() && e('1'); // 获取类型为按天生成的周期待办的日期，结果1
r($todo->getCycleTodoDateTest('week'))  && p() && e('1'); // 获取类型为按周生成的周期待办的日期，结果1
r($todo->getCycleTodoDateTest('month')) && p() && e('1'); // 获取类型为按月生成的周期待办的日期，结果1

global $tester;
$today = date('Y-m-d');

$todo = new stdclass();
$todo->config = new stdclass();
$todo->config->type  = 'day';
$todo->config->week  = '1,2,3,4,5';
$todo->config->month = '1,2,3,4,5';

r($tester->loadModel('todo')->getCycleTodoDate($todo, '', $today))   && p() && e('0'); // 生成周期为按天生成的周期待办的日期，结果0

$todo = new stdclass();
$todo->config = new stdclass();
$todo->config->type  = '';
$todo->config->week  = '1,2,3,4,5';
$todo->config->month = '1,2,3,4,5';

r($tester->loadModel('todo')->getCycleTodoDate($todo, '', $today))   && p() && e('0'); // 生成周期为空的周期待办的日期，结果0