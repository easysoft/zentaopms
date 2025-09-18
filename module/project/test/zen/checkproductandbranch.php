#!/usr/bin/env php
<?php

/**

title=测试 projectZen::checkProductAndBranch();
timeout=0
cid=0

- 步骤1：正常情况有产品关联 @1
- 步骤2：有产品但无关联产品数量且无新增产品 @0
- 步骤3：API模式产品不存在 @0
- 步骤4：分支为空 @0
- 步骤5：无产品项目正常情况 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-5');
$table->hasProduct->range('1{3},0{2}');
$table->parent->range('1-5');
$table->name->range('项目1,项目2,项目3,项目4,项目5');
$table->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal{3},platform{2}');
$productTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->checkProductAndBranchTest(1, array('hasProduct' => 1), array('products' => array(1, 2), 'branch' => array(array(0), array(0))))) && p() && e('1'); // 步骤1：正常情况有产品关联
r($projectTest->checkProductAndBranchTest(2, array('hasProduct' => 1, 'parent' => 1), array())) && p() && e('0'); // 步骤2：有产品但无关联产品数量且无新增产品
r($projectTest->checkProductAndBranchTest(3, array('hasProduct' => 1), array('products' => array(999), 'branch' => array(array(0))), true)) && p() && e('0'); // 步骤3：API模式产品不存在
r($projectTest->checkProductAndBranchTest(4, array('hasProduct' => 1), array('products' => array(4), 'branch' => array(array(''))))) && p() && e('0'); // 步骤4：分支为空
r($projectTest->checkProductAndBranchTest(5, array('hasProduct' => 0), array())) && p() && e('1'); // 步骤5：无产品项目正常情况