#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getLinkedProducts();
timeout=0
cid=16432

- 步骤1：通过复制执行ID获取产品（无执行数据时返回空） @0
- 步骤2：通过产品计划ID获取产品第1条的name属性 @正常产品1
- 步骤3：无产品项目获取Shadow产品 @0
- 步骤4：空输入情况 @0
- 步骤5：不存在的计划ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_getlinkedproducts', false, 2)->gen(10);

$productplanTable = zenData('productplan');
$productplanTable->loadYaml('productplan_getlinkedproducts', false, 2)->gen(5);

$projectproductTable = zenData('projectproduct');
$projectproductTable->loadYaml('projectproduct_getlinkedproducts', false, 2)->gen(5);

$projectTable = zenData('project');
$projectTable->loadYaml('project_getlinkedproducts', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 准备测试数据
$project1 = new stdClass();
$project1->id = 1;
$project1->hasProduct = '1';

$project2 = new stdClass();
$project2->id = 4;
$project2->hasProduct = '0';

r($executionTest->getLinkedProductsTest(1, 0, null)) && p() && e('0'); // 步骤1：通过复制执行ID获取产品（无执行数据时返回空）
r($executionTest->getLinkedProductsTest(0, 1, null)) && p('1:name') && e('正常产品1'); // 步骤2：通过产品计划ID获取产品
r($executionTest->getLinkedProductsTest(0, 0, $project2)) && p() && e('0'); // 步骤3：无产品项目获取Shadow产品  
r($executionTest->getLinkedProductsTest(0, 0, null)) && p() && e('0'); // 步骤4：空输入情况
r($executionTest->getLinkedProductsTest(0, 999, null)) && p() && e('0'); // 步骤5：不存在的计划ID