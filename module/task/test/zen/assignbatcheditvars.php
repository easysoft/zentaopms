#!/usr/bin/env php
<?php

/**

title=测试 taskZen::assignBatchEditVars();
timeout=0
cid=18894

- 步骤1:测试带执行ID的正常情况,验证execution对象被赋值属性id @11
- 步骤2:测试带执行ID的情况,验证execution对象的名称属性name @执行1
- 步骤3:测试不带执行ID的情况,验证users视图变量被赋值属性admin @A:admin
- 步骤4:测试有父任务的情况,验证parentTasks视图变量第1条的id属性 @1
- 步骤5:测试任务列表,验证tasks视图变量中consumed被重置为0第1条的consumed属性 @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
zenData('user')->gen(10);
zenData('project')->gen(5);
zenData('product')->gen(5);

$execution = zenData('project');
$execution->id->range('11-15');
$execution->project->range('1-5');
$execution->type->range('sprint,stage,kanban');
$execution->name->range('执行1,执行2,执行3,执行4,执行5');
$execution->status->range('wait,doing,suspended,closed');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-20');
$task->project->range('1-5');
$task->execution->range('11-15');
$task->parent->range('0{15},1{3},2{2}');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10,任务11,任务12,任务13,任务14,任务15,任务16,任务17,任务18,任务19,任务20');
$task->status->range('wait,doing,done,pause,cancel,closed');
$task->assignedTo->range('admin,user1,user2,user3');
$task->consumed->range('0-10');
$task->left->range('0-10');
$task->story->range('0-5');
$task->module->range('0-10');
$task->gen(20);

zenData('story')->gen(10);
zenData('team')->gen(20);
zenData('module')->gen(15);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$taskTest = new taskZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
r($taskTest->assignBatchEditVarsTest(11, array(1, 2, 3), 'execution')) && p('id') && e('11'); // 步骤1:测试带执行ID的正常情况,验证execution对象被赋值
r($taskTest->assignBatchEditVarsTest(11, array(1, 2, 3), 'execution')) && p('name') && e('执行1'); // 步骤2:测试带执行ID的情况,验证execution对象的名称
r($taskTest->assignBatchEditVarsTest(0, array(1, 2, 3), 'users')) && p('admin') && e('A:admin'); // 步骤3:测试不带执行ID的情况,验证users视图变量被赋值
r($taskTest->assignBatchEditVarsTest(11, array(16, 17, 18), 'parentTasks')) && p('1:id') && e('1'); // 步骤4:测试有父任务的情况,验证parentTasks视图变量
r($taskTest->assignBatchEditVarsTest(11, array(1, 2, 3), 'tasks')) && p('1:consumed') && e('0'); // 步骤5:测试任务列表,验证tasks视图变量中consumed被重置为0