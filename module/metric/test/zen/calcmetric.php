#!/usr/bin/env php
<?php

/**

title=测试 metricZen::calcMetric();
timeout=0
cid=17183

- 步骤1：测试空statement和空calcList @1
- 步骤2：测试false statement和空calcList @1
- 步骤3：测试空statement但有calcList @1
- 步骤4：测试无效statement @Error: Call to a member function query() on string
- 步骤5：测试空字符串statement @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metriczen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metriclib');
$table->metricID->range('1-5');
$table->metricCode->range('test_metric_1,test_metric_2,test_metric_3,test_metric_4,test_metric_5');
$table->value->range('10,20,30,40,50');
$table->year->range('2024');
$table->month->range('01-12');
$table->day->range('01-31');
$table->date->range('`2024-01-01`,`2024-01-02`,`2024-01-03`,`2024-01-04`,`2024-01-05`');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->calcMetricZenTest(null, array())) && p() && e(1); // 步骤1：测试空statement和空calcList
r($metricTest->calcMetricZenTest(false, array())) && p() && e(1); // 步骤2：测试false statement和空calcList
r($metricTest->calcMetricZenTest(null, array('test' => 'value'))) && p() && e(1); // 步骤3：测试空statement但有calcList
r($metricTest->calcMetricZenTest('invalid', array())) && p() && e('Error: Call to a member function query() on string'); // 步骤4：测试无效statement
r($metricTest->calcMetricZenTest('', array())) && p() && e(1); // 步骤5：测试空字符串statement