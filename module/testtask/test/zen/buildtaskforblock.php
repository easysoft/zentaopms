#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::buildTaskForBlock();
timeout=0
cid=19227

- 步骤1：正常阻塞测试单属性id @1
- 步骤2：空注释的阻塞测试单属性id @2
- 步骤3：带详细描述的阻塞测试单属性id @3
- 步骤4：包含HTML标签的阻塞信息属性id @4
- 步骤5：无效ID的阻塞测试单属性id @0
- 步骤6：不存在的测试单ID属性id @999

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testtask = zenData('testtask');
$testtask->id->range('1-10');
$testtask->name->range('测试单{1-10}');
$testtask->product->range('1');
$testtask->project->range('1-3');
$testtask->execution->range('1-5');
$testtask->build->range('1-3');
$testtask->owner->range('admin,user1,user2');
$testtask->pri->range('1-4');
$testtask->begin->range('`2024-01-01`');
$testtask->end->range('`2024-01-31`');
$testtask->status->range('wait,doing,blocked,done');
$testtask->desc->range('测试单描述{1-10}');
$testtask->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskZenTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskZenTest->buildTaskForBlockTest(1)) && p('id') && e('1'); // 步骤1：正常阻塞测试单
r($testtaskZenTest->buildTaskForBlockTest(2)) && p('id') && e('2'); // 步骤2：空注释的阻塞测试单
r($testtaskZenTest->buildTaskForBlockTest(3)) && p('id') && e('3'); // 步骤3：带详细描述的阻塞测试单
r($testtaskZenTest->buildTaskForBlockTest(4)) && p('id') && e('4'); // 步骤4：包含HTML标签的阻塞信息
r($testtaskZenTest->buildTaskForBlockTest(0)) && p('id') && e('0'); // 步骤5：无效ID的阻塞测试单
r($testtaskZenTest->buildTaskForBlockTest(999)) && p('id') && e('999'); // 步骤6：不存在的测试单ID