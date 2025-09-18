#!/usr/bin/env php
<?php

/**

title=测试 projectZen::assignTesttaskVars();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性waitCount @2
 - 属性testingCount @3
 - 属性blockedCount @2
 - 属性doneCount @3
- 步骤2：空列表
 - 属性waitCount @0
 - 属性testingCount @0
 - 属性blockedCount @0
 - 属性doneCount @0
- 步骤3：混合状态
 - 属性waitCount @1
 - 属性testingCount @2
 - 属性blockedCount @1
 - 属性doneCount @1
- 步骤4：trunk版本属性trunkCount @1
- 步骤5：产品分组属性productGroupsCount @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testtask');
$table->id->range('1-10');
$table->product->range('1{3},2{3},3{4}');
$table->build->range('1,2,trunk,3{2},4{4},5');
$table->status->range('wait{2},doing{3},blocked{2},done{3}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectzenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($projectTest->assignTesttaskVarsTest('normal_tasks')) && p('waitCount,testingCount,blockedCount,doneCount') && e('2,3,2,3'); // 步骤1：正常情况
r($projectTest->assignTesttaskVarsTest('empty_tasks')) && p('waitCount,testingCount,blockedCount,doneCount') && e('0,0,0,0'); // 步骤2：空列表
r($projectTest->assignTesttaskVarsTest('mixed_status')) && p('waitCount,testingCount,blockedCount,doneCount') && e('1,2,1,1'); // 步骤3：混合状态
r($projectTest->assignTesttaskVarsTest('trunk_build')) && p('trunkCount') && e('1'); // 步骤4：trunk版本
r($projectTest->assignTesttaskVarsTest('grouped_products')) && p('productGroupsCount') && e('3'); // 步骤5：产品分组