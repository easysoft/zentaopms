#!/usr/bin/env php
<?php

/**

title=测试 executionZen::getAllProductsForCreate();
timeout=0
cid=16428

- 步骤1：有产品关联的项目获取产品属性1 @正常产品1
- 步骤2：有hasProduct=1的项目应包含空选项 @~~
- 步骤3：无hasProduct属性的项目获取产品属性1 @正常产品1
- 步骤4：空项目对象返回空数组 @0
- 步骤5：null项目返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->loadYaml('project_getallproductsforcreate', false, 2)->gen(10);

$productTable = zenData('product');
$productTable->loadYaml('product_getallproductsforcreate', false, 2)->gen(10);

$projectproductTable = zenData('projectproduct');
$projectproductTable->loadYaml('projectproduct_getallproductsforcreate', false, 2)->gen(7);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionzenTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 创建测试项目对象
$projectWithProduct = new stdClass();
$projectWithProduct->id = 1;
$projectWithProduct->hasProduct = 1;

$projectWithoutProductFlag = new stdClass();
$projectWithoutProductFlag->id = 2;

$projectNoProduct = new stdClass();
$projectNoProduct->id = 4;
$projectNoProduct->hasProduct = 0;

$emptyProject = new stdClass();

r($executionTest->getAllProductsForCreateTest($projectWithProduct)) && p('1') && e('正常产品1'); // 步骤1：有产品关联的项目获取产品
r($executionTest->getAllProductsForCreateTest($projectWithProduct)) && p('0') && e('~~'); // 步骤2：有hasProduct=1的项目应包含空选项
r($executionTest->getAllProductsForCreateTest($projectWithoutProductFlag)) && p('1') && e('正常产品1'); // 步骤3：无hasProduct属性的项目获取产品
r($executionTest->getAllProductsForCreateTest($emptyProject)) && p() && e('0'); // 步骤4：空项目对象返回空数组
r($executionTest->getAllProductsForCreateTest(null)) && p() && e('0'); // 步骤5：null项目返回空数组