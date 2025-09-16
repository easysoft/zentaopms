#!/usr/bin/env php
<?php

/**

title=测试 projectZen::recordExecutionsOfUnlinkedProducts();
timeout=0
cid=0

- 步骤1：正常情况 @0
- 步骤2：空的取消关联产品列表 @0
- 步骤3：空的执行ID列表 @0
- 步骤4：执行有多个取消关联的产品 @0
- 步骤5：多个执行取消不同产品关联 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->model->range('scrum,kanban');
$project->multiple->range('1');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品{1-10}');
$product->gen(5);

$execution = zenData('project');
$execution->id->range('11-20');
$execution->name->range('执行{1-10}');
$execution->type->range('sprint');
$execution->parent->range('1-5');
$execution->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('11-20');
$projectProduct->product->range('1-5');
$projectProduct->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => (object)array('name' => '产品1'), 2 => (object)array('name' => '产品2')), array(3, 4, 5), array(11, 12, 13))) && p() && e('0'); // 步骤1：正常情况
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(), array(), array(11, 12))) && p() && e('0'); // 步骤2：空的取消关联产品列表
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => (object)array('name' => '产品1')), array(2), array())) && p() && e('0'); // 步骤3：空的执行ID列表
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => (object)array('name' => '产品1'), 2 => (object)array('name' => '产品2'), 3 => (object)array('name' => '产品3')), array(4, 5), array(14, 15))) && p() && e('0'); // 步骤4：执行有多个取消关联的产品
r($projectTest->recordExecutionsOfUnlinkedProductsTest(array(1 => (object)array('name' => '产品1'), 3 => (object)array('name' => '产品3')), array(1, 3), array(16, 17, 18))) && p() && e('0'); // 步骤5：多个执行取消不同产品关联