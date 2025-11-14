#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildFunnelChart();
timeout=0
cid=18210

- 步骤1：正常情况属性key @Funnel
- 步骤2：settings为null属性key @Funnel
- 步骤3：settings存在时不返回值 @0
- 步骤4：空component处理属性key @Funnel
- 步骤5：验证chartConfig的chartKey第chartConfig条的chartKey属性 @VFunnel

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 4. 准备测试数据
$component1 = new stdclass();
$component1->id = 1;

$chart1 = new stdclass();
$chart1->settings = false;

$component2 = new stdclass();
$component2->id = 2;

$chart2 = new stdclass();
$chart2->settings = null;

$component3 = new stdclass();
$component3->id = 3;

$chart3 = new stdclass();
$chart3->settings = json_decode('{"test":"value"}');

$component4 = new stdclass();

$chart4 = new stdclass();
$chart4->settings = false;

$component5 = new stdclass();
$component5->id = 5;

$chart5 = new stdclass();
$chart5->settings = false;

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->buildFunnelChartTest($component1, $chart1)) && p('key') && e('Funnel'); // 步骤1：正常情况
r($screenTest->buildFunnelChartTest($component2, $chart2)) && p('key') && e('Funnel'); // 步骤2：settings为null
r($screenTest->buildFunnelChartTest($component3, $chart3)) && p() && e('0'); // 步骤3：settings存在时不返回值
r($screenTest->buildFunnelChartTest($component4, $chart4)) && p('key') && e('Funnel'); // 步骤4：空component处理
r($screenTest->buildFunnelChartTest($component5, $chart5)) && p('chartConfig:chartKey') && e('VFunnel'); // 步骤5：验证chartConfig的chartKey