#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printBuildBlock();
timeout=0
cid=0

- 步骤1：正常情况属性buildsCount @5
- 步骤2：管理员权限
 - 属性userAdmin @1
 - 属性hasValidation @1
- 步骤3：权限验证功能
 - 属性userAdmin @1
 - 属性hasValidation @1
- 步骤4：我的仪表板
 - 属性dashboard @my
 - 属性buildsCount @5
- 步骤5：项目仪表板
 - 属性dashboard @project
 - 属性hasValidation @1
- 步骤6：数量限制
 - 属性count @3
 - 属性buildsCount @3
- 步骤7：默认参数
 - 属性count @15
 - 属性hasValidation @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$build = zenData('build');
$build->loadYaml('build_printbuildblock', false, 2)->gen(30);

$product = zenData('product');
$product->loadYaml('product_printbuildblock', false, 2)->gen(5);

$project = zenData('project');
$project->loadYaml('project_printbuildblock', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：正常输入构建区块参数
$normalBlock = new stdclass();
$normalBlock->params = new stdclass();
$normalBlock->params->count = 10;
$normalBlock->dashboard = 'my';
r($blockTest->printBuildBlockTest($normalBlock)) && p('buildsCount') && e('5'); // 步骤1：正常情况

// 测试步骤2：测试管理员用户访问
$adminBlock = new stdclass();
$adminBlock->params = new stdclass();
$adminBlock->params->count = 15;
$adminBlock->dashboard = 'my';
r($blockTest->printBuildBlockTest($adminBlock)) && p('userAdmin,hasValidation') && e('1,1'); // 步骤2：管理员权限

// 测试步骤3：测试用户权限检查功能
$userBlock = new stdclass();
$userBlock->params = new stdclass();
$userBlock->params->count = 10;
$userBlock->dashboard = 'my';
r($blockTest->printBuildBlockTest($userBlock)) && p('userAdmin,hasValidation') && e('1,1'); // 步骤3：权限验证功能

// 测试步骤4：测试my仪表板模式
$myDashBlock = new stdclass();
$myDashBlock->params = new stdclass();
$myDashBlock->params->count = 8;
$myDashBlock->dashboard = 'my';
r($blockTest->printBuildBlockTest($myDashBlock)) && p('dashboard,buildsCount') && e('my,5'); // 步骤4：我的仪表板

// 测试步骤5：测试项目仪表板模式
$projDashBlock = new stdclass();
$projDashBlock->params = new stdclass();
$projDashBlock->params->count = 12;
$projDashBlock->dashboard = 'project';
r($blockTest->printBuildBlockTest($projDashBlock)) && p('dashboard,hasValidation') && e('project,1'); // 步骤5：项目仪表板

// 测试步骤6：测试构建数量限制参数
$countBlock = new stdclass();
$countBlock->params = new stdclass();
$countBlock->params->count = 3;
$countBlock->dashboard = 'my';
r($blockTest->printBuildBlockTest($countBlock)) && p('count,buildsCount') && e('3,3'); // 步骤6：数量限制

// 测试步骤7：测试空构建参数情况
$emptyBlock = new stdclass();
$emptyBlock->params = new stdclass();
$emptyBlock->dashboard = 'my';
r($blockTest->printBuildBlockTest($emptyBlock)) && p('count,hasValidation') && e('15,1'); // 步骤7：默认参数