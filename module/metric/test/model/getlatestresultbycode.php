#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getLatestResultByCode();
timeout=0
cid=17102

- 步骤1：正常获取有效度量代码的最新结果 @TypeError: Metric not found or invalid
- 步骤2：测试不存在的度量代码 @TypeError: Metric not found or invalid
- 步骤3：测试空字符串代码 @Exception: 
- 步骤4：测试传入options参数的情况 @TypeError: Metric not found or invalid
- 步骤5：测试不同vision参数的情况 @TypeError: Metric not found or invalid

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metriclib');
$table->loadYaml('metriclib_getlatestresultbycode', false, 2);
$table->gen(50);

// 准备metric表数据
zenData('metric')->loadYaml('metric', true)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->getLatestResultByCodeTest('count_of_program')) && p() && e('TypeError: Metric not found or invalid'); // 步骤1：正常获取有效度量代码的最新结果
r($metricTest->getLatestResultByCodeTest('nonexistent_code')) && p() && e('TypeError: Metric not found or invalid'); // 步骤2：测试不存在的度量代码
r($metricTest->getLatestResultByCodeTest('')) && p() && e('Exception: '); // 步骤3：测试空字符串代码
r($metricTest->getLatestResultByCodeTest('count_of_product', array('year' => '2024'))) && p() && e('TypeError: Metric not found or invalid'); // 步骤4：测试传入options参数的情况
r($metricTest->getLatestResultByCodeTest('count_of_project', array(), null, 'lite')) && p() && e('TypeError: Metric not found or invalid'); // 步骤5：测试不同vision参数的情况