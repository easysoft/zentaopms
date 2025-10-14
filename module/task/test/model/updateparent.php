#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('task')->loadYaml('task')->gen(10);

/**

title=测试 taskModel::updateParent();
timeout=0
cid=0

- 执行taskTest模块的updateParentTest方法，参数是$task1, false
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task2, false
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task3, false
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task4, false
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task5, false
 - 属性path @

*/

$taskTest = new taskTest();

// 创建测试任务对象
$task1 = new stdClass();
$task1->id = 4;
$task1->parent = 1;

$task2 = new stdClass();
$task2->id = 5;
$task2->parent = 2;

$task3 = new stdClass();
$task3->id = 6;
$task3->parent = 0;

$task4 = new stdClass();
$task4->id = 7;
$task4->parent = 4;

$task5 = new stdClass();
$task5->id = 8;
$task5->parent = 0;

r($taskTest->updateParentTest($task1, false)) && p('path') && e(',1,4,');
r($taskTest->updateParentTest($task2, false)) && p('path') && e(',2,5,');
r($taskTest->updateParentTest($task3, false)) && p('path') && e(',6,');
r($taskTest->updateParentTest($task4, false)) && p('path') && e(',1,4,7,');
r($taskTest->updateParentTest($task5, false)) && p('path') && e(',8,');