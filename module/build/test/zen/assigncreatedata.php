#!/usr/bin/env php
<?php

/**

title=测试 buildZen::assignCreateData();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性productID @1
 - 属性executionID @1
- 步骤2：空产品和执行ID
 - 属性users @10
 - 属性executions @0
- 步骤3：无效项目ID
 - 属性executions @0
 - 属性productID @1
- 步骤4：状态过滤
 - 属性productID @1
 - 属性users @10
- 步骤5：分支产品测试
 - 属性productID @2
 - 属性executionID @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->loadYaml('product_assigncreatedata', false, 2)->gen(5);

$project = zenData('project');
$project->loadYaml('project_assigncreatedata', false, 2)->gen(10);

$user = zenData('user');
$user->loadYaml('user_assigncreatedata', false, 2)->gen(10);

$branch = zenData('branch');
$branch->loadYaml('branch_assigncreatedata', false, 2)->gen(5);

$build = zenData('build');
$build->loadYaml('build_assigncreatedata', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$buildTest = new buildTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($buildTest->assignCreateDataTest(1, 1, 1, 'normal')) && p('productID,executionID') && e('1,1'); // 步骤1：正常情况
r($buildTest->assignCreateDataTest(0, 0, 1, 'normal')) && p('users,executions') && e('10,0'); // 步骤2：空产品和执行ID
r($buildTest->assignCreateDataTest(1, 1, 999, 'normal')) && p('executions,productID') && e('0,1'); // 步骤3：无效项目ID
r($buildTest->assignCreateDataTest(1, 0, 1, 'closed')) && p('productID,users') && e('1,10'); // 步骤4：状态过滤
r($buildTest->assignCreateDataTest(2, 2, 2, 'normal')) && p('productID,executionID') && e('2,2'); // 步骤5：分支产品测试