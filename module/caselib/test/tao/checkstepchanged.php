#!/usr/bin/env php
<?php

/**

title=测试 caselibTao::checkStepChanged();
timeout=0
cid=15538

- 步骤1：相同的步骤比较 @0
- 步骤2：步骤描述不同 @1
- 步骤3：步骤期望结果不同 @1
- 步骤4：步骤数量不同 @1
- 步骤5：空步骤与非空步骤比较 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibTest();

// 4. 准备测试数据：创建相同的步骤数据
$step1 = new stdclass();
$step1->desc = '点击登录按钮';
$step1->expect = '页面跳转到首页';

$step2 = new stdclass();
$step2->desc = '输入用户名密码';
$step2->expect = '验证通过';

$sameSteps1 = array(1 => $step1, 2 => $step2);
$sameSteps2 = array(1 => clone $step1, 2 => clone $step2);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($caselibTest->checkStepChangedTest($sameSteps1, $sameSteps2)) && p() && e('0'); // 步骤1：相同的步骤比较

// 准备不同描述的步骤数据
$diffDescStep = clone $step1;
$diffDescStep->desc = '点击确认按钮';
$diffSteps1 = array(1 => $diffDescStep, 2 => $step2);
r($caselibTest->checkStepChangedTest($sameSteps1, $diffSteps1)) && p() && e('1'); // 步骤2：步骤描述不同

// 准备不同期望的步骤数据
$diffExpectStep = clone $step1;
$diffExpectStep->expect = '页面跳转到错误页';
$diffSteps2 = array(1 => $diffExpectStep, 2 => $step2);
r($caselibTest->checkStepChangedTest($sameSteps1, $diffSteps2)) && p() && e('1'); // 步骤3：步骤期望结果不同

// 准备不同数量的步骤数据
$diffCountSteps = array(1 => $step1);
r($caselibTest->checkStepChangedTest($sameSteps1, $diffCountSteps)) && p() && e('1'); // 步骤4：步骤数量不同

// 准备空步骤数据
$emptySteps = array();
r($caselibTest->checkStepChangedTest($sameSteps1, $emptySteps)) && p() && e('1'); // 步骤5：空步骤与非空步骤比较