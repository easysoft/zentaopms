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

*/

initData();

$todo = new todoTest();

r($todo->getCycleTodoDateTest($configType = 'day'))   && p() && e('1'); // 获取类型为按天生成的周期待办的日期，结果1
r($todo->getCycleTodoDateTest($configType = 'week'))  && p() && e('1'); // 获取类型为按周生成的周期待办的日期，结果1
r($todo->getCycleTodoDateTest($configType = 'month')) && p() && e('1'); // 获取类型为按月生成的周期待办的日期，结果1
