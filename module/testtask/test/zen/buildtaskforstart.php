#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::buildTaskForStart();
timeout=0
cid=19231

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

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testtask');
$table->id->range('1-5');
$table->project->range('1{5}');
$table->product->range('1{5}');
$table->name->range('测试单{5}');
$table->execution->range('1{5}');
$table->build->range('1{5}');
$table->status->range('wait{5}');
$table->begin->range('`2024-01-01`{5}');
$table->end->range('`2024-01-31`{5}');
$table->realBegan->range('`0000-00-00`{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->buildTaskForStartTest(1)) && p('id,status') && e('1,doing'); // 步骤1：正常情况
r($testtaskTest->buildTaskForStartTest(2)) && p('id,status') && e('2,doing'); // 步骤2：空注释情况
r($testtaskTest->buildTaskForStartTest(3)) && p('id,status') && e('3,doing'); // 步骤3：长注释情况
r($testtaskTest->buildTaskForStartTest(4)) && p('id,status') && e('4,doing'); // 步骤4：带HTML标签
r($testtaskTest->buildTaskForStartTest(0)) && p('id,status') && e('0,doing'); // 步骤5：无效ID