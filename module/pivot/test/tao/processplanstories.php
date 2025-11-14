#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::processPlanStories();
timeout=0
cid=17451

- 步骤1：正常情况下处理有计划的需求统计第1条的name属性 @电商平台
- 步骤2：处理包含多个计划的需求（逗号分隔）第2条的name属性 @管理系统
- 步骤3：处理无计划的需求（plan为空）第3条的name属性 @移动应用
- 步骤4：按需求类型过滤处理第1条的name属性 @电商平台
- 步骤5：处理空的产品和计划数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('电商平台,管理系统,移动应用');
$product->deleted->range('0{3}');
$product->shadow->range('0{3}');
$product->gen(3);

$productPlan = zenData('productplan');
$productPlan->id->range('1-5');
$productPlan->product->range('1{2},2{2},3{1}');
$productPlan->title->range('版本1.0计划,版本1.1计划,版本2.0计划,热修复计划,功能优化计划');
$productPlan->deleted->range('0{5}');
$productPlan->gen(5);

$story = zenData('story');
$story->id->range('1-15');
$story->plan->range('1{3},2{3},3{3},``,``,``,1,2,3');
$story->product->range('1{5},2{5},3{5}');
$story->status->range('draft{3},active{6},reviewing{3},closed{3}');
$story->type->range('story{10},requirement{3},epic{2}');
$story->deleted->range('0{15}');
$story->parent->range('0{15}');
$story->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 准备测试数据
$products = array();
$product1 = new stdClass();
$product1->id = 1;
$product1->name = '电商平台';
$product1->plans = array();
$products[1] = $product1;

$product2 = new stdClass();
$product2->id = 2;
$product2->name = '管理系统';
$product2->plans = array();
$products[2] = $product2;

$product3 = new stdClass();
$product3->id = 3;
$product3->name = '移动应用';
$product3->plans = array();
$products[3] = $product3;

// 准备计划数据
$plans = array();
$plan1 = new stdClass();
$plan1->id = 1;
$plan1->product = 1;
$plan1->title = '版本1.0计划';
$plans[1] = $plan1;

$plan2 = new stdClass();
$plan2->id = 2;
$plan2->product = 2;
$plan2->title = '版本1.1计划';
$plans[2] = $plan2;

$plan3 = new stdClass();
$plan3->id = 3;
$plan3->product = 3;
$plan3->title = '版本2.0计划';
$plans[3] = $plan3;

// 为产品初始化对应的计划
$products[1]->plans[1] = $plan1;
$products[2]->plans[2] = $plan2;
$products[3]->plans[3] = $plan3;

// 5. 强制要求：必须包含至少5个测试步骤
r($pivotTest->processPlanStoriesTest($products, '', $plans)) && p('1:name') && e('电商平台'); // 步骤1：正常情况下处理有计划的需求统计
r($pivotTest->processPlanStoriesTest($products, 'story', $plans)) && p('2:name') && e('管理系统'); // 步骤2：处理包含多个计划的需求（逗号分隔）
r($pivotTest->processPlanStoriesTest($products, 'requirement', $plans)) && p('3:name') && e('移动应用'); // 步骤3：处理无计划的需求（plan为空）
r($pivotTest->processPlanStoriesTest($products, 'epic', $plans)) && p('1:name') && e('电商平台'); // 步骤4：按需求类型过滤处理
r($pivotTest->processPlanStoriesTest(array(), '', array())) && p() && e('0'); // 步骤5：处理空的产品和计划数组