#!/usr/bin/env php
<?php

/**

title=测试 todoZen::buildAssignToTodoView();
timeout=0
cid=19292

- 步骤1：正常待办参数传入属性result @success
- 步骤2：机会类型待办参数传入属性result @success
- 步骤3：空项目数组参数传入属性result @success
- 步骤4：无效待办对象参数传入属性result @success
- 步骤5：不同账户参数传入属性result @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('todo');
$table->id->range('1-10');
$table->account->range('admin,user1,user2');
$table->name->range('测试待办,机会待办,任务待办');
$table->type->range('custom,opportunity,task');
$table->objectID->range('0,1-5');
$table->status->range('wait,doing');
$table->pri->range('1-3');
$table->assignedTo->range('admin,user1,user2');
$table->assignedBy->range('admin');
$table->private->range('0');
$table->deleted->range('0');
$table->vision->range('rnd');
$table->date->range('20231201');
$table->begin->range('0900');
$table->end->range('1700');
$table->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->type->range('project');
$projectTable->status->range('doing');
$projectTable->model->range('scrum,waterfall');
$projectTable->deleted->range('0');
$projectTable->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->deleted->range('0');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$todoTest = new todoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($todoTest->buildAssignToTodoViewTest('normal')) && p('result') && e('success'); // 步骤1：正常待办参数传入
r($todoTest->buildAssignToTodoViewTest('opportunity')) && p('result') && e('success'); // 步骤2：机会类型待办参数传入
r($todoTest->buildAssignToTodoViewTest('empty_projects')) && p('result') && e('success'); // 步骤3：空项目数组参数传入
r($todoTest->buildAssignToTodoViewTest('invalid_todo')) && p('result') && e('success'); // 步骤4：无效待办对象参数传入
r($todoTest->buildAssignToTodoViewTest('different_account')) && p('result') && e('success'); // 步骤5：不同账户参数传入