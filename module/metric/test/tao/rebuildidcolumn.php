#!/usr/bin/env php
<?php

/**

title=测试 metricTao::rebuildIdColumn();
timeout=0
cid=17178

- 步骤1：空表情况属性result @empty_table
- 步骤2：正常数据重建属性result @success
- 步骤3：不连续ID重建属性result @success
- 步骤4：大量数据重建属性result @success
- 步骤5：自增值验证属性autoIncrement @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('metriclib');
$table->metricCode->range('test_code_{1-10}');
$table->value->range('10,20,30,40,50');
$table->year->range('2024');
$table->month->range('01,02,03,04,05');
$table->day->range('01-31:3');
$table->date->range('`2024-01-01`,`2024-01-02`,`2024-01-03`,`2024-01-04`,`2024-01-05`');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$metricTest = new metricTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($metricTest->rebuildIdColumnTest('')) && p('result') && e('empty_table'); // 步骤1：空表情况
r($metricTest->rebuildIdColumnTest('normal')) && p('result') && e('success'); // 步骤2：正常数据重建
r($metricTest->rebuildIdColumnTest('discontinuous')) && p('result') && e('success'); // 步骤3：不连续ID重建
r($metricTest->rebuildIdColumnTest('large')) && p('result') && e('success'); // 步骤4：大量数据重建
r($metricTest->rebuildIdColumnTest('autoincrement')) && p('autoIncrement') && e('1'); // 步骤5：自增值验证