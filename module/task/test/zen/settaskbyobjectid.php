#!/usr/bin/env php
<?php

/**

title=测试 taskZen::setTaskByObjectID();
timeout=0
cid=18950

- 步骤1：仅传入moduleID的默认情况属性module @2
- 步骤2：传入不同moduleID的情况属性module @3
- 步骤3：传入todoID复制待办信息属性name @待办事项
- 步骤4：传入bugID复制缺陷信息属性name @缺陷标题
- 步骤5：传入storyID设置需求模块属性story @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->name->range('测试任务{1-10}');
$task->type->range('devel{5},test{3},design{2}');
$task->pri->range('1-4');
$task->estimate->range('1-8');
$task->consumed->range('0-3');
$task->left->range('0-5');
$task->status->range('wait{3},doing{4},done{3}');
$task->mode->range('','{2}multi{1}');
$task->execution->range('1-5');
$task->module->range('1-10');
$task->story->range('0{3},1-7');
$task->assignedTo->range('admin,user1,user2,user3,""');
$task->gen(10);

$todo = zenData('todo');
$todo->id->range('1-5');
$todo->name->range('待办事项{1-5}');
$todo->pri->range('1-4');
$todo->desc->range('待办描述{1-5}');
$todo->gen(5);

$bug = zenData('bug');
$bug->id->range('1-5');
$bug->title->range('缺陷标题{1-5}');
$bug->pri->range('1-4');
$bug->story->range('1-5');
$bug->assignedTo->range('admin,user1,user2,""');
$bug->gen(5);

$story = zenData('story');
$story->id->range('1-10');
$story->title->range('需求标题{1-10}');
$story->module->range('1-5');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskZenTest->setTaskByObjectIDTest(0, 2, 0, 0, 0, array())) && p('module') && e('2'); // 步骤1：仅传入moduleID的默认情况
r($taskZenTest->setTaskByObjectIDTest(0, 3, 0, 0, 0, array())) && p('module') && e('3'); // 步骤2：传入不同moduleID的情况
r($taskZenTest->setTaskByObjectIDTest(0, 0, 0, 1, 0, array())) && p('name') && e('待办事项'); // 步骤3：传入todoID复制待办信息
r($taskZenTest->setTaskByObjectIDTest(0, 0, 0, 0, 1, array())) && p('name') && e('缺陷标题'); // 步骤4：传入bugID复制缺陷信息
r($taskZenTest->setTaskByObjectIDTest(1, 0, 0, 0, 0, array())) && p('story') && e('1'); // 步骤5：传入storyID设置需求模块