#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getBranchesForPlanKanban();
timeout=0
cid=0

- 步骤1：正常产品类型测试 >> 返回包含all键的数组
- 步骤2：多分支产品branchID为all >> 调用branch->getPairs获取分支列表
- 步骤3：主分支测试 >> 返回包含主分支的数组
- 步骤4：指定单个分支ID >> 返回包含指定分支的数组
- 步骤5：指定多个分支ID列表 >> 返回包含多个分支的数组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 准备测试数据
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal,branch{4}');
$product->status->range('normal{5}');
$product->gen(5);

$branch = zenData('branch');
$branch->id->range('1-10');
$branch->product->range('2-5:R');
$branch->name->range('分支1,分支2,分支3,分支4,分支5,分支6,分支7,分支8,分支9,分支10');
$branch->status->range('active{8},closed{2}');
$branch->deleted->range('0{8},1{2}');
$branch->gen(10);

su('admin');

// 定义常量
if (!defined('BRANCH_MAIN')) define('BRANCH_MAIN', 0);

$kanbanTest = new kanbanTest();

// 准备测试用的产品对象
$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdclass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';

// 测试步骤
r($kanbanTest->getBranchesForPlanKanbanTest($normalProduct, 'all')) && p('all') && e('所有'); // 步骤1：正常产品类型测试
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, 'all')) && p() && e('1'); // 步骤2：多分支产品branchID为all
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '0')) && p('0') && e('主干'); // 步骤3：主分支测试
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '1')) && p('1') && e('分支1'); // 步骤4：指定单个分支ID
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '1,2')) && p() && e('1'); // 步骤5：指定多个分支ID列表