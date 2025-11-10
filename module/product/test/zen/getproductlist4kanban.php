#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProductList4Kanban();
timeout=0
cid=0

- 执行productTest模块的getProductList4KanbanTest方法，参数是array  @0
- 执行productTest模块的getProductList4KanbanTest方法，参数是$productList, $planList, $projectList, $releaseList, $projectProduct)) > 0  @1
- 执行productTest模块的getProductList4KanbanTest方法，参数是$productList, $planList, $projectList, $releaseList, $projectProduct  @1
- 执行productTest模块的getProductList4KanbanTest方法，参数是$productList, array  @1
- 执行productTest模块的getProductList4KanbanTest方法，参数是$productList, $planList, array  @1
- 执行productTest模块的getProductList4KanbanTest方法，参数是$productList, $planList, $projectList, array  @1
- 执行productTest模块的getProductList4KanbanTest方法，参数是$productList, $planList, $projectList, $releaseList, array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('product')->loadYaml('product_getproductlist4kanban', false, 2)->gen(10);
zenData('productplan')->loadYaml('productplan_getproductlist4kanban', false, 2)->gen(15);
zenData('project')->loadYaml('project_getproductlist4kanban', false, 2)->gen(12);
zenData('release')->loadYaml('release_getproductlist4kanban', false, 2)->gen(15);

su('admin');

global $tester;
$product = $tester->loadModel('product');

$productTest = new productZenTest();

$productList = $product->getByIdList(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10));

$planList = array();
$plans = $tester->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq('0')->fetchAll();
foreach($plans as $plan)
{
    if(!isset($planList[$plan->product])) $planList[$plan->product] = array();
    $planList[$plan->product][] = $plan;
}

$projectList = $tester->dao->select('*')->from(TABLE_PROJECT)->where('deleted')->eq('0')->andWhere('type')->eq('project')->fetchAll('id');

$releaseList = array();
$releases = $tester->dao->select('*')->from(TABLE_RELEASE)->where('deleted')->eq('0')->fetchAll();
foreach($releases as $release)
{
    if(!isset($releaseList[$release->product])) $releaseList[$release->product] = array();
    $releaseList[$release->product][] = $release;
}

$projectProduct = array();
if(isset($projectList[1])) $projectProduct[1][1] = $projectList[1];
if(isset($projectList[2])) $projectProduct[1][2] = $projectList[2];
if(isset($projectList[3])) $projectProduct[2][3] = $projectList[3];
if(isset($projectList[4])) $projectProduct[2][4] = $projectList[4];
if(isset($projectList[5])) $projectProduct[3][5] = $projectList[5];

r($productTest->getProductList4KanbanTest(array(), array(), array(), array(), array())) && p() && e('0');
r(count($productTest->getProductList4KanbanTest($productList, $planList, $projectList, $releaseList, $projectProduct)) > 0) && p() && e('1');
r(is_array($productTest->getProductList4KanbanTest($productList, $planList, $projectList, $releaseList, $projectProduct))) && p() && e('1');
r(count($productTest->getProductList4KanbanTest($productList, array(), $projectList, $releaseList, $projectProduct)) > 0) && p() && e('1');
r(count($productTest->getProductList4KanbanTest($productList, $planList, array(), $releaseList, $projectProduct)) > 0) && p() && e('1');
r(count($productTest->getProductList4KanbanTest($productList, $planList, $projectList, array(), $projectProduct)) > 0) && p() && e('1');
r(count($productTest->getProductList4KanbanTest($productList, $planList, $projectList, $releaseList, array())) > 0) && p() && e('1');