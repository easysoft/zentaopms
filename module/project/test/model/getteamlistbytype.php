#!/usr/bin/env php
<?php

/**

title=测试 projectModel::getTeamListByType();
timeout=0
cid=17853

- 步骤1：查询project类型 @15
- 步骤2：查询task类型 @0
- 步骤3：查询execution类型 @0
- 步骤4：查询不存在类型 @0
- 步骤5：查询空字符串类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('team')->loadYaml('team_getteamlistbytype', false, 2)->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTester = new projectModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($projectTester->getTeamListByTypeTest('project')))   && p() && e(15); // 步骤1：查询project类型
r(count($projectTester->getTeamListByTypeTest('task')))      && p() && e(0);  // 步骤2：查询task类型
r(count($projectTester->getTeamListByTypeTest('execution'))) && p() && e(0);  // 步骤3：查询execution类型
r(count($projectTester->getTeamListByTypeTest('invalid')))   && p() && e(0);  // 步骤4：查询不存在类型
r(count($projectTester->getTeamListByTypeTest('')))          && p() && e(0);  // 步骤5：查询空字符串类型
