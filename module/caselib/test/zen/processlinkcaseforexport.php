#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::processLinkCaseForExport();
timeout=0
cid=15556

- 步骤1：单个ID @123
- 步骤2：多个ID包含分号 @1
- 步骤3：空值 @1
- 步骤4：无效ID @abc
- 步骤5：包含空格的ID数量 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
$case1 = new stdclass();
$case1->linkCase = '123';
r($caselibTest->processLinkCaseForExportTest($case1, 'linkCase')) && p() && e('123'); // 步骤1：单个ID

$case2 = new stdclass();
$case2->linkCase = '123,456,789';
r($caselibTest->processLinkCaseForExportTest($case2, 'has_semicolon')) && p() && e('1'); // 步骤2：多个ID包含分号

$case3 = new stdclass();
$case3->linkCase = '';
r($caselibTest->processLinkCaseForExportTest($case3, 'is_empty')) && p() && e('1'); // 步骤3：空值

$case4 = new stdclass();
$case4->linkCase = 'abc';
r($caselibTest->processLinkCaseForExportTest($case4, 'linkCase')) && p() && e('abc'); // 步骤4：无效ID

$case5 = new stdclass();
$case5->linkCase = '123, abc, 456';
r($caselibTest->processLinkCaseForExportTest($case5, 'linkCase_parts_count')) && p() && e('3'); // 步骤5：包含空格的ID数量