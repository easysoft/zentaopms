#!/usr/bin/env php
<?php

/**

title=测试 projectZen::updateLinkedProducts();
timeout=0
cid=17968

- 测试正常更新 @1
- 测试无迭代项目 @1
- 测试瀑布项目按项目创建阶段 @1
- 测试瀑布项目按产品创建阶段 @1
- 测试多迭代敏捷项目 @1
- 测试添加新产品 @1
- 测试删除产品记录动态 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

function setPost($productIds = array(), $branches = array(), $plans = array())
{
    $_POST = array();
    $_POST['products'] = $productIds;
    $_POST['branch'] = $branches;
    $_POST['plans'] = $plans;
}

$project = zenData('project');
$project->id->range('1-10');
$project->project->range('0');
$project->model->range('scrum,waterfall,waterfallplus,ipd,kanban');
$project->type->range('project');
$project->parent->range('0');
$project->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$project->status->range('wait');
$project->hasProduct->range('1');
$project->stageBy->range('product');
$project->multiple->range('0,1');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-20');
$product->program->range('0');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10,产品11,产品12,产品13,产品14,产品15,产品16,产品17,产品18,产品19,产品20');
$product->type->range('normal');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(20);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1-10');
$projectProduct->product->range('1-10');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(10);

$execution = zenData('project');
$execution->id->range('101-120');
$execution->project->range('1-10');
$execution->model->range('scrum,waterfall');
$execution->type->range('sprint,stage');
$execution->parent->range('1-10');
$execution->name->range('迭代1,迭代2,迭代3,迭代4,迭代5,阶段1,阶段2,阶段3,阶段4,阶段5,阶段6,阶段7,阶段8,阶段9,阶段10,阶段11,阶段12,阶段13,阶段14,阶段15');
$execution->status->range('wait');
$execution->gen(20);

$projectTest = new projectZenTest();

// 测试1:测试正常更新关联产品:项目关联单个产品
setPost(array(1, 11), array(array(0), array(0)), array(array(), array()));
$project1 = new stdClass();
$project1->id = 1;
$project1->model = 'scrum';
$project1->multiple = 1;
$project1->stageBy = 'product';
r($projectTest->updateLinkedProductsTest(1, $project1, array(101))) && p() && e('1'); // 测试正常更新

// 测试2:测试无迭代项目更新关联产品
setPost(array(2, 12), array(array(0), array(0)), array(array(), array()));
$project2 = new stdClass();
$project2->id = 2;
$project2->model = 'scrum';
$project2->multiple = 0;
$project2->stageBy = 'product';
r($projectTest->updateLinkedProductsTest(2, $project2, array())) && p() && e('1'); // 测试无迭代项目

// 测试3:测试瀑布项目按项目创建阶段更新关联产品
setPost(array(3, 13), array(array(0), array(0)), array(array(), array()));
$project3 = new stdClass();
$project3->id = 3;
$project3->model = 'waterfall';
$project3->multiple = 1;
$project3->stageBy = 'project';
r($projectTest->updateLinkedProductsTest(3, $project3, array(103, 104))) && p() && e('1'); // 测试瀑布项目按项目创建阶段

// 测试4:测试瀑布项目按产品创建阶段更新关联产品
setPost(array(4), array(array(0)), array(array()));
$project4 = new stdClass();
$project4->id = 4;
$project4->model = 'waterfallplus';
$project4->multiple = 1;
$project4->stageBy = 'product';
r($projectTest->updateLinkedProductsTest(4, $project4, array(105, 106))) && p() && e('1'); // 测试瀑布项目按产品创建阶段

// 测试5:测试多迭代敏捷项目更新关联产品
setPost(array(5, 15), array(array(0), array(0)), array(array(), array()));
$project5 = new stdClass();
$project5->id = 5;
$project5->model = 'scrum';
$project5->multiple = 1;
$project5->stageBy = 'product';
r($projectTest->updateLinkedProductsTest(5, $project5, array(107, 108, 109))) && p() && e('1'); // 测试多迭代敏捷项目

// 测试6:测试项目添加新关联产品
setPost(array(6, 16, 17), array(array(0), array(0), array(0)), array(array(), array(), array()));
$project6 = new stdClass();
$project6->id = 6;
$project6->model = 'scrum';
$project6->multiple = 1;
$project6->stageBy = 'product';
r($projectTest->updateLinkedProductsTest(6, $project6, array(110, 111))) && p() && e('1'); // 测试添加新产品

// 测试7:测试项目删除关联产品并记录动态
setPost(array(7), array(array(0)), array(array()));
$project7 = new stdClass();
$project7->id = 7;
$project7->model = 'scrum';
$project7->multiple = 1;
$project7->stageBy = 'product';
r($projectTest->updateLinkedProductsTest(7, $project7, array(112, 113))) && p() && e('1'); // 测试删除产品记录动态