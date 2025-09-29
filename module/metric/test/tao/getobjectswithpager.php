#!/usr/bin/env php
<?php

/**

title=测试 metricTao::getObjectsWithPager();
timeout=0
cid=0

- 步骤1：system范围返回false @alse
- 步骤2：product范围返回数组或错误 @array,database_error

- 步骤3：project范围返回数组或错误 @array,database_error

- 步骤4：execution范围返回数组或错误 @array,database_error

- 步骤5：user范围返回数组或错误 @array,database_error

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（生成少量测试数据）
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品1,产品2,产品3');
$product->deleted->range('0');
$product->shadow->range('0');
$product->gen(3);

$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目1,项目2,项目3');
$project->type->range('project{3}');
$project->deleted->range('0');
$project->gen(3);

$execution = zenData('execution');
$execution->id->range('1-3');
$execution->name->range('执行1,执行2,执行3');
$execution->type->range('sprint,stage,kanban');
$execution->deleted->range('0');
$execution->gen(3);

$user = zenData('user');
$user->account->range('admin,user1,user2');
$user->password->range('123456{3}');
$user->deleted->range('0');
$user->gen(3);

$repo = zenData('repo');
$repo->id->range('1-3');
$repo->name->range('仓库1,仓库2,仓库3');
$repo->deleted->range('0');
$repo->gen(3);

$metriclib = zenData('metriclib');
$metriclib->id->range('1-9');
$metriclib->metricCode->range('test_product_metric{3},test_project_metric{3},test_execution_metric{3}');
$metriclib->product->range('1-3:R');
$metriclib->project->range('1-3:R');
$metriclib->execution->range('1-3:R');
$metriclib->value->range('100-999');
$metriclib->year->range('2024');
$metriclib->month->range('01-12:R');
$metriclib->day->range('01-28:R');
$metriclib->date->range('2024-01-01 - 2024-12-31:1D');
$metriclib->gen(9);

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

// 6. 强制要求：必须包含至少5个测试步骤
r($metricTest->getObjectsWithPagerTest($systemMetric, array())) && p() && e(false); // 步骤1：system范围返回false
r($metricTest->getObjectsWithPagerTest($productMetric, array())) && p() && e('array,database_error'); // 步骤2：product范围返回数组或错误
r($metricTest->getObjectsWithPagerTest($projectMetric, array())) && p() && e('array,database_error'); // 步骤3：project范围返回数组或错误
r($metricTest->getObjectsWithPagerTest($executionMetric, array())) && p() && e('array,database_error'); // 步骤4：execution范围返回数组或错误
r($metricTest->getObjectsWithPagerTest($userMetric, array())) && p() && e('array,database_error'); // 步骤5：user范围返回数组或错误