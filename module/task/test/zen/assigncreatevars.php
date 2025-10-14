#!/usr/bin/env php
<?php

/**

title=测试 taskZen::assignCreateVars();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性success @1
 - 属性from @task
- 步骤2：指定故事ID
 - 属性storyID @1
 - 属性from @other
- 步骤3：指定模块ID
 - 属性success @1
 - 属性from @task
- 步骤4：指定任务ID
 - 属性taskID @1
 - 属性from @other
- 步骤5：看板类型执行
 - 属性success @1
 - 属性from @task

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->loadYaml('project_assigncreatevars', false, 2)->gen(10);

$taskTable = zenData('task');
$taskTable->loadYaml('task_assigncreatevars', false, 2)->gen(20);

$moduleTable = zenData('module');
$moduleTable->loadYaml('module_assigncreatevars', false, 2)->gen(10);

$userTable = zenData('user');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->password->range('123456{5}');
$userTable->role->range('admin,dev{4}');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 准备测试用的执行对象
$execution1 = new stdClass();
$execution1->id = 1;
$execution1->name = '测试项目1';
$execution1->type = 'sprint';
$execution1->multiple = 1;
$execution1->project = 1;

$execution2 = new stdClass();
$execution2->id = 2;
$execution2->name = '看板项目';
$execution2->type = 'kanban';
$execution2->multiple = 1;
$execution2->project = 2;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskZenTest->assignCreateVarsTest($execution1, 0, 0, 0, 0, 0, array(), '')) && p('success,from') && e('1,task'); // 步骤1：正常情况
r($taskZenTest->assignCreateVarsTest($execution1, 1, 0, 0, 0, 0, array(), '')) && p('storyID,from') && e('1,other'); // 步骤2：指定故事ID
r($taskZenTest->assignCreateVarsTest($execution1, 0, 101, 0, 0, 0, array(), '')) && p('success,from') && e('1,task'); // 步骤3：指定模块ID
r($taskZenTest->assignCreateVarsTest($execution1, 0, 0, 1, 0, 0, array(), '')) && p('taskID,from') && e('1,other'); // 步骤4：指定任务ID
r($taskZenTest->assignCreateVarsTest($execution2, 0, 0, 0, 0, 0, array(), '')) && p('success,from') && e('1,task'); // 步骤5：看板类型执行