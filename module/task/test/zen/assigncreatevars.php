#!/usr/bin/env php
<?php

/**

title=测试 taskZen::assignCreateVars();
timeout=0
cid=18895

- 步骤1:测试正常执行情况,验证execution对象被赋值属性name @执行1
- 步骤2:测试带storyID情况,验证storyID视图变量被赋值 @1
- 步骤3:测试带moduleID情况,验证task对象的module属性被赋值属性module @0
- 步骤4:测试带taskID情况,验证taskID视图变量被赋值 @1
- 步骤5:测试execution名称赋值,验证title视图变量包含执行名称 @执行1-建任务

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
$execution->multiple->range('1');
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
$task->module->range('1-10');
$task->gen(20);

zenData('story')->gen(10);
zenData('team')->gen(20);
zenData('module')->gen(15);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$taskTest = new taskZenTest();
$executionModel = $taskTest->tester->loadModel('execution');

// 5. 强制要求:必须包含至少5个测试步骤
r($taskTest->assignCreateVarsTest($executionModel->getByID(11), 0, 0, 0, 0, 0, array(), '', 'execution')) && p('name') && e('执行1'); // 步骤1:测试正常执行情况,验证execution对象被赋值
r($taskTest->assignCreateVarsTest($executionModel->getByID(11), 1, 0, 0, 0, 0, array(), '', 'storyID')) && p() && e('1'); // 步骤2:测试带storyID情况,验证storyID视图变量被赋值
r($taskTest->assignCreateVarsTest($executionModel->getByID(11), 0, 1, 0, 0, 0, array(), '', 'task')) && p('module') && e('0'); // 步骤3:测试带moduleID情况,验证task对象的module属性被赋值
r($taskTest->assignCreateVarsTest($executionModel->getByID(11), 0, 0, 1, 0, 0, array(), '', 'taskID')) && p() && e('1'); // 步骤4:测试带taskID情况,验证taskID视图变量被赋值
r($taskTest->assignCreateVarsTest($executionModel->getByID(11), 0, 0, 0, 0, 0, array(), '', 'title')) && p() && e('执行1-建任务'); // 步骤5:测试execution名称赋值,验证title视图变量包含执行名称