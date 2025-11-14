#!/usr/bin/env php
<?php

/**

title=测试 executionZen::assignTaskKanbanVars();
timeout=0
cid=16407

- 步骤1：正常情况第admin条的realname属性 @admin
- 步骤2：空execution对象 @看板
- 步骤3：不存在的execution ID @0
- 步骤4：包含多个产品的execution @0
- 步骤5：验证用户列表包含Closed状态第closed条的realname属性 @Closed

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$userTable = zenData('user');
$userTable->loadYaml('zt_user_assigntaskkanbanvars', false, 2);
$userTable->gen(10);

$projectTable = zenData('project');
$projectTable->loadYaml('zt_project_assigntaskkanbanvars', false, 2);
$projectTable->gen(5);

$productTable = zenData('product');
$productTable->loadYaml('zt_product_assigntaskkanbanvars', false, 2);
$productTable->gen(3);

$projectProductTable = zenData('projectproduct');
$projectProductTable->loadYaml('zt_projectproduct_assigntaskkanbanvars', false, 2);
$projectProductTable->gen(6);

$productPlanTable = zenData('productplan');
$productPlanTable->loadYaml('zt_productplan_assigntaskkanbanvars', false, 2);
$productPlanTable->gen(6);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
$execution = new stdClass();
$execution->id = 1;
$execution->project = 1;
$result1 = $executionTest->assignTaskKanbanVarsTest($execution);
r($result1->userList) && p('admin:realname') && e('admin'); // 步骤1：正常情况

$emptyExecution = new stdClass();
$emptyExecution->id = 0;
$emptyExecution->project = 0;
$result2 = $executionTest->assignTaskKanbanVarsTest($emptyExecution);
r($result2->title) && p() && e('看板'); // 步骤2：空execution对象

$nonExistExecution = new stdClass();
$nonExistExecution->id = 999;
$nonExistExecution->project = 999;
$result3 = $executionTest->assignTaskKanbanVarsTest($nonExistExecution);
r($result3->productNum) && p() && e(0); // 步骤3：不存在的execution ID

$multiProductExecution = new stdClass();
$multiProductExecution->id = 1;
$multiProductExecution->project = 1;
$result4 = $executionTest->assignTaskKanbanVarsTest($multiProductExecution);
r($result4->productNum) && p() && e(0); // 步骤4：包含多个产品的execution

$result5 = $executionTest->assignTaskKanbanVarsTest($execution);
r($result5->userList) && p('closed:realname') && e('Closed'); // 步骤5：验证用户列表包含Closed状态