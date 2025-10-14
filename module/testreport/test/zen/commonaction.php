#!/usr/bin/env php
<?php

/**

title=测试 testreportZen::commonAction();
timeout=0
cid=0

- 步骤1：测试product类型 @1
- 步骤2：测试execution类型 @1
- 步骤3：测试project类型 @1
- 步骤4：测试无效objectID @0
- 步骤5：测试默认参数 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testreportzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-10');
$productTable->name->range('产品1,产品2,产品3{7}');
$productTable->status->range('normal{8},closed{2}');
$productTable->deleted->range('0');
$productTable->gen(10);

$executionTable = zenData('execution');
$executionTable->id->range('1-10');
$executionTable->name->range('执行1,执行2,执行3{7}');
$executionTable->status->range('wait{3},doing{5},done{2}');
$executionTable->deleted->range('0');
$executionTable->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目1,项目2,项目3{7}');
$projectTable->status->range('wait{3},doing{5},done{2}');
$projectTable->deleted->range('0');
$projectTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testreportTest = new testreportTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testreportTest->commonActionTest(1, 'product')) && p() && e('1'); // 步骤1：测试product类型
r($testreportTest->commonActionTest(1, 'execution')) && p() && e('1'); // 步骤2：测试execution类型
r($testreportTest->commonActionTest(1, 'project')) && p() && e('1'); // 步骤3：测试project类型
r($testreportTest->commonActionTest(999, 'product')) && p() && e('0'); // 步骤4：测试无效objectID
r($testreportTest->commonActionTest(1)) && p() && e('1'); // 步骤5：测试默认参数