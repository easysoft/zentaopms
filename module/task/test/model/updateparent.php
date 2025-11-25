#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=测试 taskModel::updateParent();
timeout=0
cid=18857

- 执行taskTest模块的updateParentTest方法，参数是$task, false
 - 属性path @
- 执行$parentTask属性isParent @1
- 执行taskTest模块的updateParentTest方法，参数是$task, true
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task, true
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task, false
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task, false
 - 属性path @
- 执行taskTest模块的updateParentTest方法，参数是$task, true
 - 属性path @

*/

// 准备测试数据
$table = zenData('task');
$table->id->range('1-20');
$table->parent->range('0,0,1,1,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0');
$table->path->range('`1`, `2`, `1,3`, `1,4`, `3,5`, `6`, `7`, `8`, `9`, `10`, `11`, `12`, `13`, `14`, `15`, `16`, `17`, `18`, `19`, `20`')->prefix(',')->postfix(',');
$table->isParent->range('0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0');
$table->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10,任务11,任务12,任务13,任务14,任务15,任务16,任务17,任务18,任务19,任务20');
$table->execution->range('1-20');
$table->project->range('1');
$table->status->range('wait');
$table->assignedTo->range('admin');
$table->gen(20);

zenData('action')->gen(0);
zenData('taskteam')->gen(0);

su('admin');

$taskTest = new taskTest();

// 测试步骤1: 测试任务2设置父任务为任务1
$task = new stdClass();
$task->id = 2;
$task->parent = 1;
r($taskTest->updateParentTest($task, false)) && p('path') && e(',1,2,');

// 测试步骤2: 验证父任务1的isParent字段被设置为1
global $tester;
$parentTask = $tester->loadModel('task')->fetchByID(1);
r($parentTask) && p('isParent') && e('1');

// 测试步骤3: 测试任务4更改父任务从1到6
$task = new stdClass();
$task->id = 4;
$task->parent = 6;
r($taskTest->updateParentTest($task, true)) && p('path') && e(',6,4,');

// 测试步骤4: 测试任务3移除父任务
$task = new stdClass();
$task->id = 3;
$task->parent = 0;
r($taskTest->updateParentTest($task, true)) && p('path') && e(',3,');

// 测试步骤5: 测试任务7设置父任务为1
$task = new stdClass();
$task->id = 7;
$task->parent = 1;
r($taskTest->updateParentTest($task, false)) && p('path') && e(',1,7,');

// 测试步骤6: 测试任务8设置父任务为3,形成多层级结构
$task = new stdClass();
$task->id = 8;
$task->parent = 3;
r($taskTest->updateParentTest($task, false)) && p('path') && e(',3,8,');

// 测试步骤7: 测试任务10从无父任务变为有父任务
$task = new stdClass();
$task->id = 10;
$task->parent = 5;
r($taskTest->updateParentTest($task, true)) && p('path') && e(',3,5,10,');
