#!/usr/bin/env php
<?php

/**

title=测试 screenModel::initChartAndPivotComponent();
timeout=0
cid=18262

- 执行screenTest模块的initChartAndPivotComponentTest方法，参数是null, 'chart' 属性1 @1
- 执行screenTest模块的initChartAndPivotComponentTest方法，参数是$chart, 'chart' 第0条的id属性 @cluBarX
- 执行screenTest模块的initChartAndPivotComponentTest方法，参数是$chart, 'chart' 第0条的type属性 @table
- 执行screenTest模块的initChartAndPivotComponentTest方法，参数是$chart2, 'pivot' 第0条的type属性 @metric
- 执行screenTest模块的initChartAndPivotComponentTest方法，参数是$chart3, 'metric' 第0条的type属性 @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('chart')->loadYaml('chart_initchartandpivotcomponent', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：空chart参数测试
r($screenTest->initChartAndPivotComponentTest(null, 'chart')) && p('1') && e('1');

// 测试步骤2：正常chart初始化测试
$chart = new stdclass();
$chart->id = 1;
$chart->name = 'Test Chart';
$chart->type = 'cluBarX';
$chart->builtin = 0;
$chart->settings = '[{"type":"cluBarX"}]';
$chart->version = 1;
r($screenTest->initChartAndPivotComponentTest($chart, 'chart')) && p('0:id') && e('cluBarX');

// 测试步骤3：chart类型设置测试
r($screenTest->initChartAndPivotComponentTest($chart, 'chart')) && p('0:type') && e('table');

// 测试步骤4：pivot类型设置测试
$chart2 = new stdclass();
$chart2->id = 2;
$chart2->name = 'Test Table';
$chart2->type = 'table';
$chart2->builtin = 0;
$chart2->settings = '[{"type":"table"}]';
$chart2->version = 1;
r($screenTest->initChartAndPivotComponentTest($chart2, 'pivot')) && p('0:type') && e('metric');

// 测试步骤5：metric类型设置测试
$chart3 = new stdclass();
$chart3->id = 3;
$chart3->name = 'Test Metric';
$chart3->type = 'metric';
$chart3->builtin = 0;
$chart3->settings = '[{"type":"metric"}]';
$chart3->version = 1;
r($screenTest->initChartAndPivotComponentTest($chart3, 'metric')) && p('0:type') && e('~~');