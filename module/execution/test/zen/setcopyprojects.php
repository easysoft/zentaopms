#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setCopyProjects();
timeout=0
cid=16439

- 步骤1：正常项目对象设置拷贝项目 @0
- 步骤2：敏捷增强项目模式处理 @0
- 步骤3：瀑布增强项目模式处理 @0
- 步骤4：空项目对象处理 @0
- 步骤5：null项目参数处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->loadYaml('project_setcopyprojects', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionzenTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 创建测试项目对象
$normalProject = new stdClass();
$normalProject->id = 1;
$normalProject->parent = 0;
$normalProject->model = 'scrum';

$agilePlusProject = new stdClass();
$agilePlusProject->id = 2;
$agilePlusProject->parent = 1;
$agilePlusProject->model = 'agileplus';

$waterfallPlusProject = new stdClass();
$waterfallPlusProject->id = 3;
$waterfallPlusProject->parent = 1;
$waterfallPlusProject->model = 'waterfallplus';

$emptyProject = new stdClass();

$result1 = $executionTest->setCopyProjectsTest($normalProject);
$result2 = $executionTest->setCopyProjectsTest($agilePlusProject);
$result3 = $executionTest->setCopyProjectsTest($waterfallPlusProject);
$result4 = $executionTest->setCopyProjectsTest($emptyProject);
$result5 = $executionTest->setCopyProjectsTest(null);

r(count($result1->copyProjects)) && p() && e('0'); // 步骤1：正常项目对象设置拷贝项目
r(count($result2->copyProjects)) && p() && e('0'); // 步骤2：敏捷增强项目模式处理
r(count($result3->copyProjects)) && p() && e('0'); // 步骤3：瀑布增强项目模式处理
r($result4->copyProjectID) && p() && e('0'); // 步骤4：空项目对象处理
r(count($result5->copyProjects)) && p() && e('0'); // 步骤5：null项目参数处理