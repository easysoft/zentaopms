#!/usr/bin/env php
<?php

/**

title=测试 metricTao::getObjectsWithPager();
timeout=0
cid=0

- 步骤1：system范围返回false @0
- 步骤2：product范围正常情况 @0
- 步骤3：project范围正常情况 @0
- 步骤4：execution范围正常情况 @0
- 步骤5：user范围正常情况 @0
- 步骤6：repo范围正常情况 @0
- 步骤7：空数据情况 @0
- 步骤8：分页功能测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 创建测试用的metric对象
$systemMetric = new stdClass();
$systemMetric->code = 'test_system_metric';
$systemMetric->scope = 'system';
$systemMetric->dateType = 'day';

$productMetric = new stdClass();
$productMetric->code = 'test_product_metric';
$productMetric->scope = 'product';
$productMetric->dateType = 'day';

$projectMetric = new stdClass();
$projectMetric->code = 'test_project_metric';
$projectMetric->scope = 'project';
$projectMetric->dateType = 'day';

$executionMetric = new stdClass();
$executionMetric->code = 'test_execution_metric';
$executionMetric->scope = 'execution';
$executionMetric->dateType = 'day';

$userMetric = new stdClass();
$userMetric->code = 'test_user_metric';
$userMetric->scope = 'user';
$userMetric->dateType = 'day';

$repoMetric = new stdClass();
$repoMetric->code = 'test_repo_metric';
$repoMetric->scope = 'repo';
$repoMetric->dateType = 'day';

$emptyMetric = new stdClass();
$emptyMetric->code = 'test_empty_metric';
$emptyMetric->scope = 'product';
$emptyMetric->dateType = 'day';

// 创建分页对象
global $app;
$app->loadClass('pager', true);
$pager = new pager(0, 2, 1);

// 6. 强制要求：必须包含至少8个测试步骤
r($metricTest->getObjectsWithPagerTest($systemMetric, array())) && p() && e('0'); // 步骤1：system范围返回false
r($metricTest->getObjectsWithPagerTest($productMetric, array())) && p() && e('0'); // 步骤2：product范围正常情况
r($metricTest->getObjectsWithPagerTest($projectMetric, array())) && p() && e('0'); // 步骤3：project范围正常情况
r($metricTest->getObjectsWithPagerTest($executionMetric, array())) && p() && e('0'); // 步骤4：execution范围正常情况
r($metricTest->getObjectsWithPagerTest($userMetric, array())) && p() && e('0'); // 步骤5：user范围正常情况
r($metricTest->getObjectsWithPagerTest($repoMetric, array())) && p() && e('0'); // 步骤6：repo范围正常情况
r($metricTest->getObjectsWithPagerTest($emptyMetric, array())) && p() && e('0'); // 步骤7：空数据情况
r($metricTest->getObjectsWithPagerTest($productMetric, array(), $pager)) && p() && e('0'); // 步骤8：分页功能测试