#!/usr/bin/env php
<?php

/**

title=测试 projectZen::assignTesttaskVars();
timeout=0
cid=0

- 步骤1:空数组输入
 - 属性waitCount @0
 - 属性testingCount @0
 - 属性blockedCount @0
 - 属性doneCount @0
 - 属性taskCount @0
- 步骤2:不同状态统计
 - 属性waitCount @2
 - 属性testingCount @2
 - 属性blockedCount @2
 - 属性doneCount @2
- 步骤3:同产品多测试单属性taskCount @5
- 步骤4:trunk版本处理
 - 属性taskCount @3
 - 属性testingCount @3
- 步骤5:全部完成状态属性doneCount @5
- 步骤6:全部等待状态属性waitCount @5
- 步骤7:混合状态分布
 - 属性waitCount @2
 - 属性testingCount @3
 - 属性blockedCount @1
 - 属性doneCount @2

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
$testtaskTable = zenData('testtask');
$testtaskTable->loadYaml('assigntesttaskvars/testtask', false, 2)->gen(20);

$productTable = zenData('product');
$productTable->loadYaml('assigntesttaskvars/product', false, 2)->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$projectTest = new projectzenTest();

// 5. 🔴 强制要求:必须包含至少5个测试步骤
r($projectTest->assignTesttaskVarsTest(array())) && p('waitCount,testingCount,blockedCount,doneCount,taskCount') && e('0,0,0,0,0'); // 步骤1:空数组输入

// 步骤2:测试包含不同状态的测试单
$tasks2 = array();
for($i = 1; $i <= 8; $i++)
{
    $task = new stdClass();
    $task->id = $i;
    $task->product = ($i <= 4) ? 1 : 2;
    $task->name = '测试单' . $i;
    $task->build = $i;
    $task->buildName = '版本' . $i;
    if($i <= 2) $task->status = 'wait';
    elseif($i <= 4) $task->status = 'doing';
    elseif($i <= 6) $task->status = 'blocked';
    else $task->status = 'done';
    $tasks2[] = $task;
}
r($projectTest->assignTesttaskVarsTest($tasks2)) && p('waitCount,testingCount,blockedCount,doneCount') && e('2,2,2,2'); // 步骤2:不同状态统计

// 步骤3:测试同一产品多个测试单
$tasks3 = array();
for($i = 1; $i <= 5; $i++)
{
    $task = new stdClass();
    $task->id = $i;
    $task->product = 1;
    $task->name = '测试单' . $i;
    $task->build = $i;
    $task->buildName = '版本' . $i;
    $task->status = 'wait';
    $tasks3[] = $task;
}
r($projectTest->assignTesttaskVarsTest($tasks3)) && p('taskCount') && e('5'); // 步骤3:同产品多测试单

// 步骤4:测试trunk版本处理
$tasks4 = array();
for($i = 1; $i <= 3; $i++)
{
    $task = new stdClass();
    $task->id = $i;
    $task->product = $i;
    $task->name = '测试单' . $i;
    $task->build = 'trunk';
    $task->buildName = '';
    $task->status = 'doing';
    $tasks4[] = $task;
}
r($projectTest->assignTesttaskVarsTest($tasks4)) && p('taskCount,testingCount') && e('3,3'); // 步骤4:trunk版本处理

// 步骤5:所有状态都是done
$tasks5 = array();
for($i = 1; $i <= 5; $i++)
{
    $task = new stdClass();
    $task->id = $i;
    $task->product = ($i % 3) + 1;
    $task->name = '测试单' . $i;
    $task->build = $i;
    $task->buildName = '版本' . $i;
    $task->status = 'done';
    $tasks5[] = $task;
}
r($projectTest->assignTesttaskVarsTest($tasks5)) && p('doneCount') && e('5'); // 步骤5:全部完成状态

// 步骤6:所有状态都是wait
$tasks6 = array();
for($i = 1; $i <= 5; $i++)
{
    $task = new stdClass();
    $task->id = $i;
    $task->product = (($i - 1) % 2) + 1;
    $task->name = '测试单' . $i;
    $task->build = $i;
    $task->buildName = '版本' . $i;
    $task->status = 'wait';
    $tasks6[] = $task;
}
r($projectTest->assignTesttaskVarsTest($tasks6)) && p('waitCount') && e('5'); // 步骤6:全部等待状态

// 步骤7:混合状态分布
$tasks7 = array();
$statuses = array('wait', 'wait', 'doing', 'doing', 'doing', 'blocked', 'done', 'done');
for($i = 0; $i < 8; $i++)
{
    $task = new stdClass();
    $task->id = $i + 1;
    $task->product = ($i % 4) + 1;
    $task->name = '测试单' . ($i + 1);
    $task->build = ($i + 1);
    $task->buildName = '版本' . ($i + 1);
    $task->status = $statuses[$i];
    $tasks7[] = $task;
}
r($projectTest->assignTesttaskVarsTest($tasks7)) && p('waitCount,testingCount,blockedCount,doneCount') && e('2,3,1,2'); // 步骤7:混合状态分布