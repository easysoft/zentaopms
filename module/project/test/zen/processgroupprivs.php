#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processGroupPrivs();
timeout=0
cid=0

- 步骤1：有产品项目权限处理 @1
- 步骤2：无产品项目权限处理 @1
- 步骤3：瀑布模型项目权限处理 @1
- 步骤4：敏捷模型项目权限处理 @1
- 步骤5：无迭代无产品项目权限处理 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$table->hasProduct->range('1{5},0{5}');
$table->model->range('scrum{2},waterfall{2},kanban{1},scrum{2},waterfall{2},kanban{1}');
$table->multiple->range('1{5},0{5}');
$table->status->range('wait{3},doing{4},closed{3}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->processGroupPrivsTest((object)array('hasProduct' => 1, 'model' => 'scrum', 'multiple' => 1))) && p() && e('1'); // 步骤1：有产品项目权限处理
r($projectTest->processGroupPrivsTest((object)array('hasProduct' => 0, 'model' => 'scrum', 'multiple' => 1))) && p() && e('1'); // 步骤2：无产品项目权限处理
r($projectTest->processGroupPrivsTest((object)array('hasProduct' => 1, 'model' => 'waterfall', 'multiple' => 1))) && p() && e('1'); // 步骤3：瀑布模型项目权限处理
r($projectTest->processGroupPrivsTest((object)array('hasProduct' => 1, 'model' => 'scrum', 'multiple' => 1))) && p() && e('1'); // 步骤4：敏捷模型项目权限处理
r($projectTest->processGroupPrivsTest((object)array('hasProduct' => 0, 'model' => 'scrum', 'multiple' => 0))) && p() && e('1'); // 步骤5：无迭代无产品项目权限处理