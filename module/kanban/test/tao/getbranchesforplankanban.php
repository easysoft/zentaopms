#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getBranchesForPlanKanban();
timeout=0
cid=16978

- 步骤1:正常产品返回all键 @1
- 步骤2:多分支产品获取所有分支(5个+主分支) @6
- 步骤3:获取主分支 @主干
- 步骤4:获取单个特定分支 @1
- 步骤5:获取多个特定分支 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$tester->loadModel('productplan');

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('正常产品,多分支产品A,多分支产品B,测试产品C,测试产品D');
$product->type->range('normal,branch,branch,normal,branch');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('2,2,2,2,2,3,3,3,5,5');
$branch->name->range('开发分支1,开发分支2,测试分支1,发布分支1,V1.0分支,V2.0分支,hotfix分支,feature分支,develop分支,master分支');
$branch->status->range('active');
$branch->gen(10);

su('admin');

$kanbanTest = new kanbanTaoTest();

$normalProduct = new stdClass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProductForAll = new stdClass();
$branchProductForAll->id = 2;
$branchProductForAll->type = 'branch';

$branchProductForMain = new stdClass();
$branchProductForMain->id = 3;
$branchProductForMain->type = 'branch';

$branchProductForSingle = new stdClass();
$branchProductForSingle->id = 2;
$branchProductForSingle->type = 'branch';

$branchProductForMultiple = new stdClass();
$branchProductForMultiple->id = 2;
$branchProductForMultiple->type = 'branch';

r(isset($kanbanTest->getBranchesForPlanKanbanTest($normalProduct, 'all')['all'])) && p() && e('1'); // 步骤1:正常产品返回all键
r(count($kanbanTest->getBranchesForPlanKanbanTest($branchProductForAll, 'all'))) && p() && e('6'); // 步骤2:多分支产品获取所有分支(5个+主分支)
r($kanbanTest->getBranchesForPlanKanbanTest($branchProductForMain, '0')) && p('0') && e('主干'); // 步骤3:获取主分支
r(isset($kanbanTest->getBranchesForPlanKanbanTest($branchProductForSingle, '1')['1'])) && p() && e('1'); // 步骤4:获取单个特定分支
r(count($kanbanTest->getBranchesForPlanKanbanTest($branchProductForMultiple, '1,2,3'))) && p() && e('3'); // 步骤5:获取多个特定分支