#!/usr/bin/env php
<?php

/**

title=测试 projectZen::getUnmodifiableProducts();
timeout=0
cid=17946

- 步骤1：瀑布项目有关联需求的产品1和2
 - 属性1 @1
 - 属性2 @2
- 步骤2：瀑布项目按产品分阶段且有执行关联的产品4属性4 @4
- 步骤3：非瀑布项目返回空数组 @0
- 步骤4：看板项目返回空数组 @0
- 步骤5：不存在的项目ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('瀑布项目,瀑布增强项目,敏捷项目,看板项目,测试项目');
$project->model->range('waterfall,waterfallplus,scrum,kanban,scrum');
$project->stageBy->range('product,project,product,product,product');
$project->type->range('project{5}');
$project->status->range('doing{5}');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品A,产品B,产品C,产品D,产品E');
$product->status->range('normal{5}');
$product->gen(5);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1{3},2{2}');
$projectProduct->product->range('1-3,4-5');
$projectProduct->branch->range('0{5}');
$projectProduct->gen(5);

$story = zenData('story');
$story->id->range('1-3');
$story->product->range('1,2,4');
$story->title->range('需求1,需求2,需求3');
$story->status->range('active');
$story->deleted->range('0');
$story->gen(3);

$projectStory = zenData('projectstory');
$projectStory->project->range('1{2},2{1}');
$projectStory->product->range('1-2,4');
$projectStory->story->range('1-3');
$projectStory->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectzenTest = new projectzenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectzenTest->getUnmodifiableProductsTest(1, (object)array('id' => 1, 'model' => 'waterfall', 'stageBy' => 'product'))) && p('1,2') && e('1,2'); // 步骤1：瀑布项目有关联需求的产品1和2
r($projectzenTest->getUnmodifiableProductsTest(2, (object)array('id' => 2, 'model' => 'waterfallplus', 'stageBy' => 'product'))) && p('4') && e('4'); // 步骤2：瀑布项目按产品分阶段且有执行关联的产品4
r($projectzenTest->getUnmodifiableProductsTest(3, (object)array('id' => 3, 'model' => 'scrum', 'stageBy' => 'product'))) && p() && e(0); // 步骤3：非瀑布项目返回空数组
r($projectzenTest->getUnmodifiableProductsTest(4, (object)array('id' => 4, 'model' => 'kanban', 'stageBy' => 'product'))) && p() && e(0); // 步骤4：看板项目返回空数组
r($projectzenTest->getUnmodifiableProductsTest(999, (object)array('id' => 999, 'model' => 'waterfall', 'stageBy' => 'product'))) && p() && e(0); // 步骤5：不存在的项目ID
