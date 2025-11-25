#!/usr/bin/env php
<?php

/**

title=测试 executionModel::createDefaultSprint();
timeout=0
cid=16289

- 步骤1：正常敏捷项目创建默认迭代 @6
- 步骤2：看板项目创建默认看板 @7
- 步骤3：瀑布项目创建默认迭代 @8
- 步骤4：不存在的项目ID测试 @0
- 步骤5：敏捷项目创建默认迭代 @9

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 2. 准备测试数据
zenData('user')->gen(5);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{5}');
$project->model->range('scrum,kanban,waterfall,scrum,scrum');
$project->status->range('wait{5}');
$project->begin->range('20240101 000000:0')->type('timestamp')->format('YY/MM/DD');
$project->end->range('20240601 000000:0')->type('timestamp')->format('YY/MM/DD');
$project->openedBy->range('admin{5}');
$project->parent->range('0{5}');
$project->gen(5);

zenData('team')->gen(0);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$executionTest = new executionTest();

// 5. 执行测试步骤
r($executionTest->createDefaultSprintTest(1)) && p() && e('6'); // 步骤1：正常敏捷项目创建默认迭代
r($executionTest->createDefaultSprintTest(2)) && p() && e('7'); // 步骤2：看板项目创建默认看板
r($executionTest->createDefaultSprintTest(3)) && p() && e('8'); // 步骤3：瀑布项目创建默认迭代
r($executionTest->createDefaultSprintTest(999)) && p() && e('0'); // 步骤4：不存在的项目ID测试
r($executionTest->createDefaultSprintTest(4)) && p() && e('9'); // 步骤5：敏捷项目创建默认迭代