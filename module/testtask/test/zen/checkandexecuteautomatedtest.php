#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::checkAndExecuteAutomatedTest();
timeout=0
cid=19232

- 步骤1：自动化用例未确认时 @fail
- 步骤2：非自动化用例 @0
- 步骤3：自动化用例确认yes @0
- 步骤4：自动化用例确认no @0
- 步骤5：其他自动化用例测试 @fail

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$caseTable = zenData('case');
$caseTable->id->range('1-5');
$caseTable->product->range('1');
$caseTable->title->range('自动化测试用例{1},手动测试用例{4}');
$caseTable->auto->range('auto{1},no{4}');
$caseTable->version->range('1');
$caseTable->gen(5);

$runTable = zenData('testrun');
$runTable->id->range('1-5');
$runTable->task->range('1');
$runTable->case->range('1-5');
$runTable->version->range('1');
$runTable->assignedTo->range('admin');
$runTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->checkAndExecuteAutomatedTestTest(1, 1, 1, '')) && p() && e('fail'); // 步骤1：自动化用例未确认时
r($testtaskTest->checkAndExecuteAutomatedTestTest(2, 2, 2, '')) && p() && e('0'); // 步骤2：非自动化用例
r($testtaskTest->checkAndExecuteAutomatedTestTest(1, 1, 1, 'yes')) && p() && e('0'); // 步骤3：自动化用例确认yes
r($testtaskTest->checkAndExecuteAutomatedTestTest(1, 1, 1, 'no')) && p() && e('0'); // 步骤4：自动化用例确认no
r($testtaskTest->checkAndExecuteAutomatedTestTest(3, 3, 3, '')) && p() && e('fail'); // 步骤5：其他自动化用例测试