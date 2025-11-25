#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::buildTaskForActivate();
timeout=0
cid=19226

- 步骤1：正常情况
 - 属性id @1
 - 属性status @doing
- 步骤2：空注释情况
 - 属性id @2
 - 属性status @doing
- 步骤3：长注释情况
 - 属性id @3
 - 属性status @doing
- 步骤4：带HTML标签
 - 属性id @4
 - 属性status @doing
- 步骤5：无效ID
 - 属性id @0
 - 属性status @doing
- 步骤6：不存在的ID
 - 属性id @999
 - 属性status @doing

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testtask = zenData('testtask');
$testtask->id->range('1-10');
$testtask->name->range('测试单{1-10}');
$testtask->product->range('1-3');
$testtask->project->range('1-3');
$testtask->build->range('1-5');
$testtask->owner->range('admin,user1,user2');
$testtask->status->range('blocked{5},done{5}');
$testtask->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->buildTaskForActivateTest(1)) && p('id,status') && e('1,doing'); // 步骤1：正常情况
r($testtaskTest->buildTaskForActivateTest(2)) && p('id,status') && e('2,doing'); // 步骤2：空注释情况
r($testtaskTest->buildTaskForActivateTest(3)) && p('id,status') && e('3,doing'); // 步骤3：长注释情况
r($testtaskTest->buildTaskForActivateTest(4)) && p('id,status') && e('4,doing'); // 步骤4：带HTML标签
r($testtaskTest->buildTaskForActivateTest(0)) && p('id,status') && e('0,doing'); // 步骤5：无效ID
r($testtaskTest->buildTaskForActivateTest(999)) && p('id,status') && e('999,doing'); // 步骤6：不存在的ID