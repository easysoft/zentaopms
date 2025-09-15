#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printScrumOverviewBlock();
timeout=0
cid=0

- 步骤1：验证项目ID正确设置 @1
- 步骤2：验证有项目数据 @1
- 步骤3：验证项目对象存在 @1
- 步骤4：验证包含项目执行数据 @1
- 步骤5：验证所有必要属性都存在 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（不使用数据库数据，完全模拟）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result = $blockTest->printScrumOverviewBlockTest();
r(is_object($result) && isset($result->projectID) && $result->projectID == 1) && p() && e('1'); // 步骤1：验证项目ID正确设置
r(is_object($result) && isset($result->hasProject) && $result->hasProject == 1) && p() && e('1'); // 步骤2：验证有项目数据
r(is_object($result) && isset($result->project) && is_object($result->project)) && p() && e('1'); // 步骤3：验证项目对象存在
r(is_object($result) && isset($result->hasProjectData) && $result->hasProjectData == 1) && p() && e('1'); // 步骤4：验证包含项目执行数据
r(isset($result->projectID, $result->project, $result->hasProject, $result->hasProjectData)) && p() && e('1'); // 步骤5：验证所有必要属性都存在