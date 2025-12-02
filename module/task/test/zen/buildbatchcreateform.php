#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildBatchCreateForm();
timeout=0
cid=18899

- 步骤1:正常sprint执行类型,无父任务属性title @批量创建任务
- 步骤2:看板类型执行属性execution @13
- 步骤3:有父任务的情况属性parentTitle @任务1
- 步骤4:有需求的情况属性storyID @1
- 步骤5:验证modules被正确设置属性modules @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备(根据需要配置)
zenData('user')->gen(10);
zenData('project')->gen(5);
zenData('product')->gen(5);
zenData('usergroup')->gen(20);

$execution = zenData('project');
$execution->id->range('11-15');
$execution->project->range('1-5');
$execution->type->range('sprint,stage,kanban,kanban,sprint');
$execution->name->range('执行1,执行2,执行3,执行4,执行5');
$execution->status->range('wait,doing,suspended,closed,doing');
$execution->multiple->range('1,1,0,1,1');
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
$task->pri->range('1-4');
$task->gen(20);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-5');
$story->module->range('1-10');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->status->range('active,active,closed,active,active,active,closed,active,active,active');
$story->gen(10);

zenData('team')->gen(20);
zenData('module')->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskZenTest();
$executionModel = $taskTest->tester->loadModel('execution');

// 5. 获取执行对象
$execution1 = $executionModel->getByID(11);
$execution2 = $executionModel->getByID(13);
$execution3 = $executionModel->getByID(15);

// 6. 执行测试步骤(必须至少5个)
r($taskTest->buildBatchCreateFormTest($execution1, 0, 0, 0, array())) && p('title') && e('批量创建任务'); // 步骤1:正常sprint执行类型,无父任务
r($taskTest->buildBatchCreateFormTest($execution2, 0, 0, 0, array())) && p('execution') && e('13'); // 步骤2:看板类型执行
r($taskTest->buildBatchCreateFormTest($execution1, 0, 0, 1, array())) && p('parentTitle') && e('任务1'); // 步骤3:有父任务的情况
r($taskTest->buildBatchCreateFormTest($execution1, 1, 0, 0, array())) && p('storyID') && e('1'); // 步骤4:有需求的情况
r($taskTest->buildBatchCreateFormTest($execution1, 0, 0, 0, array())) && p('modules') && e('0'); // 步骤5:验证modules被正确设置