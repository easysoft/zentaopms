#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildOrgChart();
timeout=0
cid=18214

- 步骤1：有效对象但chart->settings为空 @0
- 步骤2：有效对象且chart->settings不为空 @0
- 步骤3：空component，有效chart @0
- 步骤4：有效component，空chart @0
- 步骤5：两个空对象 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 4. 创建测试数据对象
$validComponent = new stdclass();
$validComponent->id = 1;
$validComponent->title = 'Test Component';

$validChart = new stdclass();
$validChart->id = 1;
$validChart->title = 'Test Chart';
$validChart->type = 'org';

$validChartWithSettings = new stdclass();
$validChartWithSettings->id = 2;
$validChartWithSettings->title = 'Test Chart With Settings';
$validChartWithSettings->type = 'org';
$validChartWithSettings->settings = json_decode('{"test": "value"}');

$emptyComponent = new stdclass();
$emptyChart = new stdclass();

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->buildOrgChartTest($validComponent, $validChart)) && p() && e('0'); // 步骤1：有效对象但chart->settings为空
r($screenTest->buildOrgChartTest($validComponent, $validChartWithSettings)) && p() && e('0'); // 步骤2：有效对象且chart->settings不为空
r($screenTest->buildOrgChartTest($emptyComponent, $validChart)) && p() && e('0'); // 步骤3：空component，有效chart
r($screenTest->buildOrgChartTest($validComponent, $emptyChart)) && p() && e('0'); // 步骤4：有效component，空chart
r($screenTest->buildOrgChartTest($emptyComponent, $emptyChart)) && p() && e('0'); // 步骤5：两个空对象