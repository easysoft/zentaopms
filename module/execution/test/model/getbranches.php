#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$product = zenData('product');
$product->id->range('1-2');
$product->name->range('正常产品1,多分支产品1');
$product->type->range('normal,branch');
$product->status->range('normal');
$product->createdBy->range('admin,user1');
$product->gen(2);

$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('2');
$branch->name->range('分支1,分支2');
$branch->status->range('active');
$branch->gen(2);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('3{3}, 4{3}, 5{3}');
$projectProduct->product->range('1-2{2}');
$projectProduct->branch->range('0-2');
$projectProduct->gen(9);

/**

title=测试executionModel->getBranchesTest();
timeout=0
cid=16299

*/

$executionIDList = array(3, 4, 5);
$count           = array('0','1');

$executionTester = new executionTest();
r($executionTester->getBranchesTest($executionIDList[0],$count[0])) && p('1')      && e('1');     // 敏捷项目下根据执行查询产品分支
r($executionTester->getBranchesTest($executionIDList[1],$count[0])) && p('2', '|') && e('0,1,2'); // 瀑布项目下根据执行查询产品分支
r($executionTester->getBranchesTest($executionIDList[2],$count[0])) && p('1')      && e('2');     // 看板项目下根据执行查询产品分支
r($executionTester->getBranchesTest($executionIDList[0],$count[1])) && p()         && e('2');    // 敏捷执行关联产品分支统计
r($executionTester->getBranchesTest($executionIDList[1],$count[1])) && p()         && e('2');    // 瀑布执行关联产品分支统计
r($executionTester->getBranchesTest($executionIDList[2],$count[1])) && p()         && e('2');    // 看板执行关联产品分支统计
