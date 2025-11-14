#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getResultByCodeWithArray();
timeout=0
cid=17125

- 步骤1：正常情况-返回空数组 @0
- 步骤2：不存在的代码-返回空数组 @0
- 步骤3：空字符串-返回空数组 @0
- 步骤4：非rnd vision-返回空数组 @0
- 步骤5：有效代码和空options-返回空数组 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('metriclib')->loadYaml('metriclib_getresultbycodewitharray', false, 2)->gen(30);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metric = new metricTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metric->objectModel->getResultByCodeWithArray('count_of_productplan_in_product', array(), 'realtime', null, 'rnd')) && p() && e('0'); // 步骤1：正常情况-返回空数组
r($metric->objectModel->getResultByCodeWithArray('nonexistent_code', array(), 'realtime', null, 'rnd')) && p() && e('0'); // 步骤2：不存在的代码-返回空数组
r($metric->objectModel->getResultByCodeWithArray('', array(), 'realtime', null, 'rnd')) && p() && e('0'); // 步骤3：空字符串-返回空数组
r($metric->objectModel->getResultByCodeWithArray('count_of_productplan_in_product', array(), 'realtime', null, 'lite')) && p() && e('0'); // 步骤4：非rnd vision-返回空数组
r($metric->objectModel->getResultByCodeWithArray('count_of_annual_closed_feedback_in_product', array(), 'realtime', null, 'rnd')) && p() && e('0'); // 步骤5：有效代码和空options-返回空数组