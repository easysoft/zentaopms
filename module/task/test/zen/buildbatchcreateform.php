#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildBatchCreateForm();
timeout=0
cid=0

- 步骤1：正常执行对象
 - 属性title @批量添加任务
 - 属性execution @1
 - 属性project @1
- 步骤2：看板类型执行
 - 属性title @批量添加任务
 - 属性execution @2
- 步骤3：带父任务ID
 - 属性title @批量添加任务
 - 属性execution @3
 - 属性parent @1
- 步骤4：带需求和模块ID
 - 属性title @批量添加任务
 - 属性execution @4
 - 属性storyID @1
- 步骤5：非多迭代项目执行
 - 属性title @批量添加任务
 - 属性execution @5
 - 属性project @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->type->range('project{5},sprint{5}');
$project->status->range('wait,doing,suspended,closed');
$project->multiple->range('1{7},0{3}');
$project->lifetime->range('ops,long');
$project->attribute->range('request,review');
$project->gen(10);

$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1-5');
$task->execution->range('1-5');
$task->name->range('任务{1-10}');
$task->type->range('devel,test,design,study,discuss');
$task->pri->range('1,2,3,4');
$task->parent->range('0{8},1{2}');
$task->isParent->range('0{8},1{2}');
$task->status->range('wait,doing,done,pause,cancel');
$task->gen(10);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->module->range('1-5');
$story->title->range('需求{1-10}');
$story->type->range('story');
$story->pri->range('1,2,3,4');
$story->status->range('active');
$story->gen(10);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->role->range('admin,dev,qa,pm,po');
$user->deleted->range('0');
$user->gen(10);

$team = zenData('team');
$team->id->range('1-10');
$team->root->range('1-5');
$team->type->range('execution');
$team->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$team->join->range('2023-01-01:2023-12-31');
$team->gen(10);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1-5');
$module->name->range('模块{1-10}');
$module->type->range('task');
$module->deleted->range('0');
$module->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$execution1 = new stdClass();
$execution1->id = 1;
$execution1->name = '迭代1';
$execution1->type = 'sprint';
$execution1->multiple = 1;
$execution1->project = 1;
$execution1->lifetime = 'ops';
$execution1->attribute = 'request';

r($taskZenTest->buildBatchCreateFormTest($execution1)) && p('title,execution,project') && e('批量添加任务,1,1'); // 步骤1：正常执行对象

$execution2 = new stdClass();
$execution2->id = 2;
$execution2->name = '看板执行';
$execution2->type = 'kanban';
$execution2->multiple = 1;
$execution2->project = 2;
$execution2->lifetime = 'long';
$execution2->attribute = 'review';

r($taskZenTest->buildBatchCreateFormTest($execution2)) && p('title,execution') && e('批量添加任务,2'); // 步骤2：看板类型执行

$execution3 = new stdClass();
$execution3->id = 3;
$execution3->name = '迭代3';
$execution3->type = 'sprint';
$execution3->multiple = 1;
$execution3->project = 3;
$execution3->lifetime = 'ops';
$execution3->attribute = 'request';

r($taskZenTest->buildBatchCreateFormTest($execution3, 0, 0, 1)) && p('title,execution,parent') && e('批量添加任务,3,1'); // 步骤3：带父任务ID

$execution4 = new stdClass();
$execution4->id = 4;
$execution4->name = '迭代4';
$execution4->type = 'sprint';
$execution4->multiple = 1;
$execution4->project = 4;
$execution4->lifetime = 'long';
$execution4->attribute = 'review';

r($taskZenTest->buildBatchCreateFormTest($execution4, 1, 5)) && p('title,execution,storyID') && e('批量添加任务,4,1'); // 步骤4：带需求和模块ID

$execution5 = new stdClass();
$execution5->id = 5;
$execution5->name = '迭代5';
$execution5->type = 'sprint';
$execution5->multiple = 0;
$execution5->project = 5;
$execution5->lifetime = 'ops';
$execution5->attribute = 'request';

r($taskZenTest->buildBatchCreateFormTest($execution5)) && p('title,execution,project') && e('批量添加任务,5,5'); // 步骤5：非多迭代项目执行