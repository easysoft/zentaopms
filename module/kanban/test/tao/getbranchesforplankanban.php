#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getBranchesForPlanKanban();
timeout=0
cid=0

- 步骤1：正常产品类型测试，应返回包含all键 >> 返回包含all键的数组
- 步骤2：多分支产品branchID为all，获取活跃分支 >> 返回活跃分支数组或空数组
- 步骤3：多分支产品主分支测试（BRANCH_MAIN=0） >> 返回包含主分支的数组
- 步骤4：多分支产品指定单个分支ID >> 返回指定分支的数组
- 步骤5：多分支产品指定多个分支ID列表 >> 返回多个分支的数组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 准备测试数据
zenData('product')->gen(5);
zenData('branch')->gen(10);

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
r($kanbanTest->getBranchesForPlanKanbanTest($normalProduct, 'all')) && p() && e('~~'); // 步骤1：正常产品类型测试
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, 'all')) && p() && e('~~'); // 步骤2：多分支产品branchID为all
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '0')) && p() && e('~~'); // 步骤3：主分支测试
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '1')) && p() && e('~~'); // 步骤4：指定单个分支ID
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '1,2')) && p() && e('~~'); // 步骤5：指定多个分支ID列表