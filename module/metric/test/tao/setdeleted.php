#!/usr/bin/env php
<?php

/**

title=测试 metricTao::setDeleted();
timeout=0
cid=17179

- 步骤1：设置已删除状态 @5
- 步骤2：设置未删除状态 @5
- 步骤3：不存在的度量项 @0
- 步骤4：空字符串代码 @invalid_code
- 步骤5：设置删除状态 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metriclib');
$table->id->range('1-10');
$table->metricCode->range('test_metric_01{5},test_metric_02{5}');
$table->value->range('10-100:10');
$table->year->range('2024');
$table->month->range('01-12:R');
$table->day->range('01-28:R');
$table->date->range('`2024-01-01 00:00:00`,`2024-12-31 23:59:59`');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($metricTest->setDeletedTest('test_metric_01', '1')) && p() && e('5'); // 步骤1：设置已删除状态
r($metricTest->setDeletedTest('test_metric_01', '0')) && p() && e('5'); // 步骤2：设置未删除状态
r($metricTest->setDeletedTest('nonexistent_metric', '1')) && p() && e('0'); // 步骤3：不存在的度量项
r($metricTest->setDeletedTest('', '1')) && p() && e('invalid_code'); // 步骤4：空字符串代码
r($metricTest->setDeletedTest('test_metric_02', '1')) && p() && e('3'); // 步骤5：设置删除状态