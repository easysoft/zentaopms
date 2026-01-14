#!/usr/bin/env php
<?php

/**

title=测试 taskModel::updateParent();
timeout=0
cid=18857

- 执行taskTest模块的updateParentTest方法，参数是$task1, false
 - 属性id @4
 - 属性path @4,
- 执行taskTest模块的updateParentTest方法，参数是$task2, false
 - 属性id @5
 - 属性path @5,
- 执行taskTest模块的updateParentTest方法，参数是$task3, false
 - 属性id @6
 - 属性path @,6,
- 执行taskTest模块的updateParentTest方法，参数是$task4, false
 - 属性id @2
 - 属性path @4,2,
- 执行taskTest模块的updateParentTest方法，参数是$task5, true
 - 属性id @10
 - 属性path @10,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('task')->loadYaml('updateparent', false, 2)->gen(10);
zenData('project')->loadYaml('execution', false, 4)->gen(5);
zenData('product')->gen(5);

su('admin');

$taskTest = new taskModelTest();

$task1 = new stdClass();
$task1->id = 4;
$task1->parent = 2;
r($taskTest->updateParentTest($task1, false)) && p('id|path', '|') && e('4|4,');

$task2 = new stdClass();
$task2->id = 5;
$task2->parent = 3;
r($taskTest->updateParentTest($task2, false)) && p('id|path', '|') && e('5|5,');

$task3 = new stdClass();
$task3->id = 6;
$task3->parent = 0;
r($taskTest->updateParentTest($task3, false)) && p('id|path', '|') && e('6|,6,');

$task4 = new stdClass();
$task4->id = 2;
$task4->parent = 4;
r($taskTest->updateParentTest($task4, false)) && p('id|path', '|') && e('2|4,2,');

$task5 = new stdClass();
$task5->id = 10;
$task5->parent = 3;
r($taskTest->updateParentTest($task5, true)) && p('id|path', '|') && e('10|10,');