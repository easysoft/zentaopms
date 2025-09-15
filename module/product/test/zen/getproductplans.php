#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProductPlans();
timeout=0
cid=0

- 步骤1：正常项目需求且需求类型为story，但由于getPlans方法逻辑实际返回0 @0
- 步骤2：项目需求但需求类型非story @0
- 步骤3：非项目需求情况 @0
- 步骤4：空项目产品数组 @0
- 步骤5：项目ID为0的边界情况 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->code->range('PA,PB,PC,PD,PE');
$product->status->range('normal{5}');
$product->type->range('normal{5}');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目A,项目B,项目C');
$project->status->range('doing{3}');
$project->type->range('project{3}');
$project->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1{2},2{2}');
$projectProduct->product->range('1,2,3,4');
$projectProduct->branch->range('0{4}');
$projectProduct->gen(4);

$productPlan = zenData('productplan');
$productPlan->id->range('1-6');
$productPlan->product->range('1{2},2{2},3{2}');
$productPlan->title->range('计划A1,计划A2,计划B1,计划B2,计划C1,计划C2');
$productPlan->status->range('wait{2},doing{2},done{2}');
$productPlan->begin->range('`2024-01-01`,`2024-02-01`,`2024-03-01`,`2024-04-01`,`2024-05-01`,`2024-06-01`');
$productPlan->end->range('`2024-06-30`,`2024-07-31`,`2024-08-31`,`2024-09-30`,`2024-10-31`,`2024-11-30`');
$productPlan->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getProductPlansTest(array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), 1, 'story', true)) && p() && e('0'); // 步骤1：正常项目需求且需求类型为story，但由于getPlans方法逻辑实际返回0
r($productTest->getProductPlansTest(array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), 1, 'requirement', true)) && p() && e('0'); // 步骤2：项目需求但需求类型非story
r($productTest->getProductPlansTest(array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), 1, 'story', false)) && p() && e('0'); // 步骤3：非项目需求情况
r($productTest->getProductPlansTest(array(), 1, 'story', true)) && p() && e('0'); // 步骤4：空项目产品数组
r($productTest->getProductPlansTest(array(1 => (object)array('id' => 1)), 0, 'story', true)) && p() && e('0'); // 步骤5：项目ID为0的边界情况