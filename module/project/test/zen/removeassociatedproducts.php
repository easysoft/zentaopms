#!/usr/bin/env php
<?php

/**

title=测试 projectZen::removeAssociatedProducts();
timeout=0
cid=0

- 步骤1：有产品的项目 @has_product_no_delete
- 步骤2：无产品的项目但没有关联产品 @no_product_found
- 步骤3：无产品的项目且关联的产品不是影子产品 @not_shadow_product
- 步骤4：无产品的项目且关联的产品是影子产品 @shadow_product_deleted
- 步骤5：无产品的项目但产品为空 @no_product_found

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-5');
$table->name->range('项目1,项目2,项目3,项目4,项目5');
$table->hasProduct->range('1,0,0,0,0');
$table->deleted->range('0');
$table->gen(5);

$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->shadow->range('0,0,0,1,0');
$productTable->deleted->range('0');
$productTable->gen(5);

$projectProductTable = zenData('projectproduct');
$projectProductTable->project->range('2-5');
$projectProductTable->product->range('2-5');
$projectProductTable->gen(4);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->removeAssociatedProductsTest((object)array('id' => 1, 'hasProduct' => 1))) && p() && e('has_product_no_delete'); // 步骤1：有产品的项目
r($projectTest->removeAssociatedProductsTest((object)array('id' => 6, 'hasProduct' => 0))) && p() && e('no_product_found'); // 步骤2：无产品的项目但没有关联产品
r($projectTest->removeAssociatedProductsTest((object)array('id' => 2, 'hasProduct' => 0))) && p() && e('not_shadow_product'); // 步骤3：无产品的项目且关联的产品不是影子产品
r($projectTest->removeAssociatedProductsTest((object)array('id' => 4, 'hasProduct' => 0))) && p() && e('shadow_product_deleted'); // 步骤4：无产品的项目且关联的产品是影子产品
r($projectTest->removeAssociatedProductsTest((object)array('id' => 7, 'hasProduct' => 0))) && p() && e('no_product_found'); // 步骤5：无产品的项目但产品为空