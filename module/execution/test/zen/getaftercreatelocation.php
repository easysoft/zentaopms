#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getAfterCreateLocation();
timeout=0
cid=0

- 步骤1：kanban模型测试 @Array
- 步骤2：默认情况测试 @(
- 步骤3：无项目ID测试（期望返回错误）属性error @[error] =>
- 步骤4：project tab下测试 @)
- 步骤5：doc tab下测试 @Array

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->model->range('scrum{3},waterfall{2},kanban{3},other{2}');
$project->type->range('project{5},sprint{3},stage{2}');
$project->isTpl->range('0{8},1{2}');
$project->status->range('wait{3},doing{5},suspended{1},closed{1}');
$project->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->getAfterCreateLocationTest(1, 1, 'kanban', array())) && p() && e('Array'); // 步骤1：kanban模型测试
r($executionTest->getAfterCreateLocationTest(1, 1, '', array())) && p() && e('('); // 步骤2：默认情况测试
r($executionTest->getAfterCreateLocationTest(0, 2, '', array())) && p('error') && e('[error] =>'); // 步骤3：无项目ID测试（期望返回错误）
r($executionTest->getAfterCreateLocationTest(1, 1, 'kanban', array('tab' => 'project', 'vision' => 'lite'))) && p() && e(')'); // 步骤4：project tab下测试
r($executionTest->getAfterCreateLocationTest(1, 1, '', array('tab' => 'doc'))) && p() && e('Array'); // 步骤5：doc tab下测试