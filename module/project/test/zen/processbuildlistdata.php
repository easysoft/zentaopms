#!/usr/bin/env php
<?php

/**

title=测试 projectZen::processBuildListData();
timeout=0
cid=17956

- 步骤1：空版本列表处理 @0
- 步骤2：单个版本数据处理第0条的name属性 @v1.0
- 步骤3：多迭代版本处理第0条的name属性 @v2.0
- 步骤4：无产品项目版本处理第0条的name属性 @v3.0
- 步骤5：带分支信息版本处理第0条的name属性 @v4.0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$buildTable = zenData('build');
$buildTable->loadYaml('build_processbuildlistdata', false, 2)->gen(10);

$projectTable = zenData('project');
$projectTable->loadYaml('project_processbuildlistdata', false, 2)->gen(3);

$productTable = zenData('product');
$productTable->loadYaml('product_processbuildlistdata', false, 2)->gen(2);

$branchTable = zenData('branch');
$branchTable->loadYaml('branch_processbuildlistdata', false, 2)->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($projectzenTest->processBuildListDataTest(array(), 1)) && p() && e('0'); // 步骤1：空版本列表处理
r($projectzenTest->processBuildListDataTest(array((object)array('id' => 1, 'product' => 1, 'branch' => '0', 'execution' => 1, 'builds' => '', 'name' => 'v1.0', 'system' => 0, 'scmPath' => '/trunk', 'filePath' => '/build.zip')), 1)) && p('0:name') && e('v1.0'); // 步骤2：单个版本数据处理
r($projectzenTest->processBuildListDataTest(array((object)array('id' => 2, 'product' => 1, 'branch' => '1,2', 'execution' => 0, 'builds' => array((object)array('executionName' => '执行1'), (object)array('executionName' => '执行2')), 'name' => 'v2.0', 'system' => 1, 'scmPath' => '/branches', 'filePath' => '/build2.zip')), 2)) && p('0:name') && e('v2.0'); // 步骤3：多迭代版本处理
r($projectzenTest->processBuildListDataTest(array((object)array('id' => 3, 'product' => 2, 'branch' => '0', 'execution' => 2, 'builds' => '', 'name' => 'v3.0', 'system' => '', 'scmPath' => '', 'filePath' => '/build3.zip')), 3)) && p('0:name') && e('v3.0'); // 步骤4：无产品项目版本处理
r($projectzenTest->processBuildListDataTest(array((object)array('id' => 4, 'product' => 1, 'branch' => '1', 'execution' => 3, 'builds' => '', 'name' => 'v4.0', 'system' => 2, 'scmPath' => '/feature', 'filePath' => '')), 1)) && p('0:name') && e('v4.0'); // 步骤5：带分支信息版本处理