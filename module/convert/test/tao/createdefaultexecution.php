#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createDefaultExecution();
timeout=0
cid=0

- 步骤1：正常创建execution @1
- 步骤2：不同项目ID测试 @1
- 步骤3：第三个项目测试 @1
- 步骤4：重复项目ID测试 @1
- 步骤5：再次测试第二个项目 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('测试项目1, 测试项目2, 测试项目3, 测试项目4, 测试项目5');
$project->type->range('project{5}');
$project->status->range('wait, doing, done, closed, suspended');
$project->PM->range('admin{2}, user1{2}, user2{1}');
$project->openedBy->range('admin{3}, user1{2}');
$project->gen(5);

zenData('team')->gen(10);
zenData('action')->gen(10);
zenData('doclib')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->createDefaultExecutionTest(1001, 1, array())) && p() && e('1'); // 步骤1：正常创建execution
r($convertTest->createDefaultExecutionTest(1002, 2, array())) && p() && e('1'); // 步骤2：不同项目ID测试
r($convertTest->createDefaultExecutionTest(1003, 3, array())) && p() && e('1'); // 步骤3：第三个项目测试
r($convertTest->createDefaultExecutionTest(1004, 1, array())) && p() && e('1'); // 步骤4：重复项目ID测试
r($convertTest->createDefaultExecutionTest(1005, 2, array())) && p() && e('1'); // 步骤5：再次测试第二个项目