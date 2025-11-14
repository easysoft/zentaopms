#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignTesttaskVars();
timeout=0
cid=16409

- 步骤1：正常情况状态统计
 - 属性waitCount @3
 - 属性testingCount @3
 - 属性blockedCount @2
 - 属性doneCount @2
- 步骤2：空数组处理
 - 属性waitCount @0
 - 属性testingCount @0
 - 属性blockedCount @0
 - 属性doneCount @0
- 步骤3：单一产品任务计数属性waitCount @1
- 步骤4：多产品任务计数属性waitCount @6
- 步骤5：trunk版本处理验证属性waitCount @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testtask');
$table->loadYaml('zt_testtask_assigntesttaskvars', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 构造测试数据
global $lang;
if(!isset($lang->trunk)) $lang->trunk = 'trunk';

$tasksNormal = array();
for($i = 1; $i <= 10; $i++)
{
    $task = new stdClass();
    $task->id = $i;
    $task->product = ($i <= 4) ? 1 : (($i <= 8) ? 2 : 3);
    $task->status = ($i <= 3) ? 'wait' : (($i <= 6) ? 'doing' : (($i <= 8) ? 'blocked' : 'done'));
    $task->build = ($i <= 3) ? 'trunk' : 'v1.0';
    $task->buildName = ($i <= 3) ? '' : '版本1.0';
    $tasksNormal[] = $task;
}

$tasksSingle = array();
$task1 = new stdClass();
$task1->id = 1;
$task1->product = 1;
$task1->status = 'wait';
$task1->build = 'v1.0';
$task1->buildName = '版本1.0';
$tasksSingle[] = $task1;

$task2 = new stdClass();
$task2->id = 2;
$task2->product = 1;
$task2->status = 'doing';
$task2->build = 'trunk';
$task2->buildName = '';
$tasksSingle[] = $task2;

$tasksMultiProduct = array();
for($i = 1; $i <= 6; $i++)
{
    $task = new stdClass();
    $task->id = $i;
    $task->product = ($i <= 2) ? 1 : (($i <= 4) ? 2 : 3);
    $task->status = 'wait';
    $task->build = 'v1.0';
    $task->buildName = '版本1.0';
    $tasksMultiProduct[] = $task;
}

$tasksTrunk = array();
$taskTrunk = new stdClass();
$taskTrunk->id = 1;
$taskTrunk->product = 1;
$taskTrunk->status = 'wait';
$taskTrunk->build = 'trunk';
$taskTrunk->buildName = '';
$tasksTrunk[] = $taskTrunk;

// 6. 必须包含至少5个测试步骤
r($executionTest->assignTesttaskVarsTest($tasksNormal)) && p('waitCount,testingCount,blockedCount,doneCount') && e('3,3,2,2'); // 步骤1：正常情况状态统计
r($executionTest->assignTesttaskVarsTest(array())) && p('waitCount,testingCount,blockedCount,doneCount') && e('0,0,0,0'); // 步骤2：空数组处理
r($executionTest->assignTesttaskVarsTest($tasksSingle)) && p('waitCount') && e('1'); // 步骤3：单一产品任务计数
r($executionTest->assignTesttaskVarsTest($tasksMultiProduct)) && p('waitCount') && e('6'); // 步骤4：多产品任务计数
r($executionTest->assignTesttaskVarsTest($tasksTrunk)) && p('waitCount') && e('1'); // 步骤5：trunk版本处理验证