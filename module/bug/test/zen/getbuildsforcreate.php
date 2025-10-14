#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBuildsForCreate();
timeout=0
cid=0

- 步骤1：获取所有版本第builds条的1属性 @Build1
- 步骤2：根据执行ID获取版本第builds条的1属性 @ExecutionBuild1
- 步骤3：根据项目ID获取版本第builds条的1属性 @ProjectBuild1
- 步骤4：根据产品ID获取版本第builds条的1属性 @ProductBuild1
- 步骤5：无效产品ID属性count @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（简化，避免数据库依赖）
// 由于测试方法使用模拟逻辑，不需要实际的数据库数据

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$bugTest = new bugTest();

// 5. 测试步骤
r($bugTest->getBuildsForCreateTest((object)array('productID' => 1, 'branch' => '', 'projectID' => 0, 'executionID' => 0, 'allBuilds' => true))) && p('builds:1') && e('Build1'); // 步骤1：获取所有版本
r($bugTest->getBuildsForCreateTest((object)array('productID' => 1, 'branch' => '', 'projectID' => 0, 'executionID' => 101, 'allBuilds' => false))) && p('builds:1') && e('ExecutionBuild1'); // 步骤2：根据执行ID获取版本
r($bugTest->getBuildsForCreateTest((object)array('productID' => 1, 'branch' => '', 'projectID' => 11, 'executionID' => 0, 'allBuilds' => false))) && p('builds:1') && e('ProjectBuild1'); // 步骤3：根据项目ID获取版本
r($bugTest->getBuildsForCreateTest((object)array('productID' => 1, 'branch' => '', 'projectID' => 0, 'executionID' => 0, 'allBuilds' => false))) && p('builds:1') && e('ProductBuild1'); // 步骤4：根据产品ID获取版本
r($bugTest->getBuildsForCreateTest((object)array('productID' => 999, 'branch' => '', 'projectID' => 0, 'executionID' => 0, 'allBuilds' => false))) && p('count') && e('0'); // 步骤5：无效产品ID