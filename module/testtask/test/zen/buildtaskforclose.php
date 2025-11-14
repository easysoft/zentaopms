#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::buildTaskForClose();
timeout=0
cid=19228

- 步骤1：正常情况
 - 属性id @1
 - 属性status @done
- 步骤2：不同ID测试
 - 属性id @2
 - 属性status @done
- 步骤3：长注释内容
 - 属性id @3
 - 属性status @done
- 步骤4：HTML标签注释
 - 属性id @4
 - 属性status @done
- 步骤5：无效ID
 - 属性id @0
 - 属性status @done
- 步骤6：不存在的ID
 - 属性id @999
 - 属性status @done

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('testtask');
$table->id->range('1-10');
$table->name->range('测试单{1-10}');
$table->product->range('1');
$table->build->range('1');
$table->status->range('doing');
$table->begin->range('2024-01-01:2024-01-10');
$table->end->range('2024-01-31:2024-02-10');
$table->createdBy->range('admin');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->buildTaskForCloseTest(1)) && p('id,status') && e('1,done'); // 步骤1：正常情况
r($testtaskTest->buildTaskForCloseTest(2)) && p('id,status') && e('2,done'); // 步骤2：不同ID测试
r($testtaskTest->buildTaskForCloseTest(3)) && p('id,status') && e('3,done'); // 步骤3：长注释内容
r($testtaskTest->buildTaskForCloseTest(4)) && p('id,status') && e('4,done'); // 步骤4：HTML标签注释
r($testtaskTest->buildTaskForCloseTest(0)) && p('id,status') && e('0,done'); // 步骤5：无效ID
r($testtaskTest->buildTaskForCloseTest(999)) && p('id,status') && e('999,done'); // 步骤6：不存在的ID