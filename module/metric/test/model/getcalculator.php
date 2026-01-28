#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getCalculator();
timeout=0
cid=17082

- 步骤1：正常情况 @rate_of_annual_finished_story
- 步骤2：不存在的计算器 @0
- 步骤3：空参数 @0
- 步骤4：无效scope @0
- 步骤5：无效purpose @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$metricTest = new metricModelTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->getCalculatorTest('system', 'rate', 'rate_of_annual_finished_story')) && p() && e('rate_of_annual_finished_story'); // 步骤1：正常情况
r($metricTest->getCalculatorTest('system', 'rate', 'nonexistent_calculator')) && p() && e('0'); // 步骤2：不存在的计算器
r($metricTest->getCalculatorTest('', '', '')) && p() && e('0'); // 步骤3：空参数
r($metricTest->getCalculatorTest('invalid_scope', 'rate', 'rate_of_annual_finished_story')) && p() && e('0'); // 步骤4：无效scope
r($metricTest->getCalculatorTest('system', 'invalid_purpose', 'rate_of_annual_finished_story')) && p() && e('0'); // 步骤5：无效purpose