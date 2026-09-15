#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$project = zenData('project');
$project->id->range('1,2,11,12');
$project->name->range('项目A,项目B,执行A,执行B');
$project->type->range('project{2},sprint{2}');
$project->project->range('0{2},1,2');
$project->parent->range('0{2},1,2');
$project->multiple->range('1,0,1,0');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(4);

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品A');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(1);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1,11,2,12');
$projectProduct->product->range('1');
$projectProduct->branch->range('0');
$projectProduct->plan->range('1,0,1,1')->prefix(',')->postfix(',');
$projectProduct->gen(4);

zenData('user')->gen(5);
su('admin');

/**

title=测试executionModel->updateProducts() 同步计划到项目;
timeout=0
cid=1

- 执行关联项目未关联的计划时，同步到项目 @,1,2,
- 项目已有计划时合并而不是覆盖 @,1,2,
- 执行取消计划时不从项目移除 @,1,2,
- 执行未关联产品时不修改项目计划 @,1,
- 计划关联的产品与项目关联的产品不一致时，不同步到项目 @,1,2,
- 无迭代项目的执行计划合并到项目 @,1,2,

*/

$execution = new executionModelTest();

$syncNewPlan = array('products' => array(1), 'branch' => array(array(0)), 'plans' => array(1 => array(1, 2)));
r($execution->updateProductsAndGetProjectPlanTest(11, $syncNewPlan)) && p() && e(',1,2,'); // 执行关联项目未关联的计划时，同步到项目

$mergePlan = array('products' => array(1), 'branch' => array(array(0)), 'plans' => array(1 => array(2)));
r($execution->updateProductsAndGetProjectPlanTest(11, $mergePlan)) && p() && e(',1,2,'); // 项目已有计划时合并而不是覆盖

$unlinkPlan = array('products' => array(1), 'branch' => array(array(0)), 'plans' => array(1 => array()));
r($execution->updateProductsAndGetProjectPlanTest(11, $unlinkPlan)) && p() && e(',1,2,'); // 执行取消计划时不从项目移除

$noProductPlan = array('products' => array(), 'branch' => array(), 'plans' => array(1 => array(9)));
r($execution->updateProductsAndGetProjectPlanTest(12, $noProductPlan)) && p() && e(',1,'); // 执行未关联产品时不修改项目计划

$unlinkedProductPlan = array('products' => array(1), 'branch' => array(array(0)), 'plans' => array(2 => array(9)));
r($execution->updateProductsAndGetProjectPlanTest(11, $unlinkedProductPlan)) && p() && e(',1,2,'); // 计划关联的产品与项目关联的产品不一致时，不同步到项目

$noMultiple = array('products' => array(1), 'branch' => array(array(0)), 'plans' => array(1 => array(1, 2)));
r($execution->updateProductsAndGetProjectPlanTest(12, $noMultiple)) && p() && e(',1,2,'); // 无迭代项目的执行计划合并到项目
