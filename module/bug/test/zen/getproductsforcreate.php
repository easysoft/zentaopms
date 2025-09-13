#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getProductsForCreate();
timeout=0
cid=0

- 执行bugTest模块的getProductsForCreateTest方法，参数是$bug1 属性productID @1
- 执行bugTest模块的getProductsForCreateTest方法，参数是$bug2 属性productID @1
- 执行bugTest模块的getProductsForCreateTest方法，参数是$bug3 属性productID @1
- 执行bugTest模块的getProductsForCreateTest方法，参数是$bug4 属性productID @1
- 执行bugTest模块的getProductsForCreateTest方法，参数是$bug5 属性productID @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,正常产品,关闭产品');
$table->code->range('product1,product2,product3,normal,closed');
$table->type->range('normal{4},branch{1}');
$table->status->range('normal{4},closed{1}');
$table->deleted->range('0{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常情况下的产品获取
$bug1 = new stdclass();
$bug1->productID = 1;
$bug1->projectID = 0;
$bug1->executionID = 0;
r($bugTest->getProductsForCreateTest($bug1)) && p('productID') && e('1');

// 步骤2：测试项目模式下的产品过滤
global $app;
$app->tab = 'project';
$bug2 = new stdclass();
$bug2->productID = 1;
$bug2->projectID = 1;
$bug2->executionID = 0;
r($bugTest->getProductsForCreateTest($bug2)) && p('productID') && e('1');

// 步骤3：测试执行模式下的产品获取  
$app->tab = 'execution';
$bug3 = new stdclass();
$bug3->productID = 1;
$bug3->projectID = 0;
$bug3->executionID = 101;
r($bugTest->getProductsForCreateTest($bug3)) && p('productID') && e('1');

// 步骤4：测试无效产品ID的处理
$app->tab = 'qa';
$bug4 = new stdclass();
$bug4->productID = 999;
$bug4->projectID = 0;
$bug4->executionID = 0;
r($bugTest->getProductsForCreateTest($bug4)) && p('productID') && e('1');

// 步骤5：测试空配置情况的产品处理
$bug5 = new stdclass();
$bug5->productID = 2;
$bug5->projectID = 0;
$bug5->executionID = 0;
global $config;
$config->CRProduct = false;
r($bugTest->getProductsForCreateTest($bug5)) && p('productID') && e('2');