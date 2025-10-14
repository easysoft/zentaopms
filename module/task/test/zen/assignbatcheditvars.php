#!/usr/bin/env php
<?php

/**

title=测试 taskZen::assignBatchEditVars();
timeout=0
cid=0

- 步骤1：指定执行ID设置属性executionID @1
- 步骤2：不指定执行ID获取用户信息属性users @10
- 步骤3：测试任务数据获取属性tasks @3
- 步骤4：测试管理链接信息属性manageLinkList @2
- 步骤5：测试子任务处理
 - 属性childTasks @0
 - 属性childrenDateLimit @0
- 步骤6：测试无执行ID时的标题属性title @批量编辑任务
- 步骤7：测试有执行ID时的标题属性title @迭代1 - 批量编辑任务

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('task')->loadYaml('task_assignbatcheditvars', false, 2)->gen(50);
zendata('project')->loadYaml('project_assignbatcheditvars', false, 2)->gen(10);
zendata('user')->loadYaml('user_assignbatcheditvars', false, 2)->gen(10);

$storyTable = zenData('story');
$storyTable->id->range('201-210');
$storyTable->product->range('1-3');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$storyTable->type->range('story{8},epic{1},requirement{1}');
$storyTable->status->range('active{7},closed{2},draft{1}');
$storyTable->stage->range('planned{5},projected{3},developing{2}');
$storyTable->estimate->range('1-8:R');
$storyTable->openedBy->range('admin,user1,user2');
$storyTable->version->range('1');
$storyTable->gen(10);

$moduleTable = zenData('module');
$moduleTable->id->range('101-110');
$moduleTable->root->range('1-10');
$moduleTable->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$moduleTable->type->range('task');
$moduleTable->gen(10);

$teamTable = zenData('team');
$teamTable->root->range('1-5{5},6-10{5}');
$teamTable->type->range('execution');
$teamTable->account->range('admin,user1,user2,user3,user4');
$teamTable->role->range('项目经理,开发工程师,测试工程师,产品经理,运维工程师');
$teamTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->assignBatchEditVarsTest(1)) && p('executionID') && e('1'); // 步骤1：指定执行ID设置
r($taskTest->assignBatchEditVarsTest(0)) && p('users') && e('10'); // 步骤2：不指定执行ID获取用户信息
r($taskTest->assignBatchEditVarsTest(2)) && p('tasks') && e('3'); // 步骤3：测试任务数据获取
r($taskTest->assignBatchEditVarsTest(1)) && p('manageLinkList') && e('2'); // 步骤4：测试管理链接信息
r($taskTest->assignBatchEditVarsTest(1)) && p('childTasks,childrenDateLimit') && e('0,0'); // 步骤5：测试子任务处理
r($taskTest->assignBatchEditVarsTest(0)) && p('title') && e('批量编辑任务'); // 步骤6：测试无执行ID时的标题
r($taskTest->assignBatchEditVarsTest(1)) && p('title') && e('迭代1 - 批量编辑任务'); // 步骤7：测试有执行ID时的标题