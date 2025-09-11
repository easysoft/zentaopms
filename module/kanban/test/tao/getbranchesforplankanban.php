#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::getBranchesForPlanKanban();
timeout=0
cid=0

- 步骤1：正常产品类型测试属性all @所有
- 步骤2：多分支产品branchID为all（无活跃分支返回空） @0
- 步骤3：主分支测试（BRANCH_MAIN=0） @主干
- 步骤4：指定单个分支ID属性1 @开发分支1
- 步骤5：指定多个分支ID列表属性1 @开发分支1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_getbranchesforplankanban', false, 2)->gen(5);

$branchTable = zenData('branch');
$branchTable->loadYaml('branch_getbranchesforplankanban', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 加载productplan语言文件
global $app;
$app->loadLang('productplan');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
global $tester;

// 准备测试用的产品对象
$normalProduct = new stdclass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdclass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';

r($kanbanTest->getBranchesForPlanKanbanTest($normalProduct, 'all')) && p('all') && e('所有'); // 步骤1：正常产品类型测试
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, 'all')) && p() && e(0); // 步骤2：多分支产品branchID为all（无活跃分支返回空）
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '0')) && p('0') && e('主干'); // 步骤3：主分支测试（BRANCH_MAIN=0）
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '1')) && p('1') && e('开发分支1'); // 步骤4：指定单个分支ID
r($kanbanTest->getBranchesForPlanKanbanTest($branchProduct, '1,2,3')) && p('1') && e('开发分支1'); // 步骤5：指定多个分支ID列表