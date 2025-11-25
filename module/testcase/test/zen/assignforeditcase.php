#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::assignForEditCase();
timeout=0
cid=19068

- 步骤1：正常情况属性executed @1
- 步骤2：检查isLibCase属性isLibCase @0
- 步骤3：检查产品信息属性product @1
- 步骤4：第二个用例属性executed @1
- 步骤5：验证分支设置属性branch @main

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseZenTest = new testcaseZenTest();

// 准备测试数据
$validCase = new stdClass();
$validCase->id = 1;
$validCase->title = '测试用例标题';
$validCase->product = 1;
$validCase->module = 1;
$validCase->branch = 'main';
$validCase->project = 1;

$secondCase = new stdClass();
$secondCase->id = 2;
$secondCase->title = '第二个测试用例';
$secondCase->product = 2;
$secondCase->module = 2;
$secondCase->branch = 'develop';
$secondCase->project = 2;

$caseWithoutProduct = new stdClass();
$caseWithoutProduct->id = 3;
$caseWithoutProduct->title = '无产品的用例';
$caseWithoutProduct->product = 0;
$caseWithoutProduct->module = 1;
$caseWithoutProduct->branch = 'main';
$caseWithoutProduct->project = 1;

$executionID = 10;
$zeroExecutionID = 0;
$negativeExecutionID = -1;

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($testcaseZenTest->assignForEditCaseTest($validCase, $executionID)) && p('executed') && e('1'); // 步骤1：正常情况
r($testcaseZenTest->assignForEditCaseTest($validCase, $executionID)) && p('isLibCase') && e('0'); // 步骤2：检查isLibCase
r($testcaseZenTest->assignForEditCaseTest($validCase, $executionID)) && p('product') && e('1'); // 步骤3：检查产品信息
r($testcaseZenTest->assignForEditCaseTest($secondCase, $zeroExecutionID)) && p('executed') && e('1'); // 步骤4：第二个用例
r($testcaseZenTest->assignForEditCaseTest($validCase, $negativeExecutionID)) && p('branch') && e('main'); // 步骤5：验证分支设置