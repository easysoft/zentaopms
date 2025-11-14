#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setLinkedBranches();
timeout=0
cid=16441

- 步骤1：有copyExecutionID时的正常分支设置 @copyExecution
- 步骤2：有project且stageBy='project'时的分支设置 @projectStage
- 步骤3：有planID时的计划分支设置 @planBranch
- 步骤4：products为空数组时的处理 @emptyProducts
- 步骤5：所有参数都为空/无效时的边界情况 @emptyProducts

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->loadYaml('product_setlinkedbranches', false, 2);
$productTable->gen(10);

$projectTable = zenData('project');
$projectTable->loadYaml('project_setlinkedbranches', false, 2);
$projectTable->gen(10);

$productPlanTable = zenData('productplan');
$productPlanTable->loadYaml('productplan_setlinkedbranches', false, 2);
$productPlanTable->gen(15);

$branchTable = zenData('branch');
$branchTable->loadYaml('branch_setlinkedbranches', false, 2);
$branchTable->gen(12);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 准备测试数据
$products = array(1 => '产品1', 2 => '产品2', 3 => '产品3');
$emptyProducts = array();

$project = new stdClass();
$project->id = 1;
$project->stageBy = 'project';
$project->hasProduct = 1;

$projectStage = new stdClass();
$projectStage->id = 2;
$projectStage->stageBy = 'task';
$projectStage->hasProduct = 1;

$projectNoProduct = new stdClass();
$projectNoProduct->id = 3;
$projectNoProduct->hasProduct = 0;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($executionTest->setLinkedBranchesTest($products, 1, 0, null)) && p() && e('copyExecution'); // 步骤1：有copyExecutionID时的正常分支设置
r($executionTest->setLinkedBranchesTest($products, 0, 0, $project)) && p() && e('projectStage'); // 步骤2：有project且stageBy='project'时的分支设置
r($executionTest->setLinkedBranchesTest($products, 0, 1, null)) && p() && e('planBranch'); // 步骤3：有planID时的计划分支设置
r($executionTest->setLinkedBranchesTest($emptyProducts, 0, 0, null)) && p() && e('emptyProducts'); // 步骤4：products为空数组时的处理
r($executionTest->setLinkedBranchesTest(array(), 0, 0, null)) && p() && e('emptyProducts'); // 步骤5：所有参数都为空/无效时的边界情况