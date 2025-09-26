#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genWaterpolo();
timeout=0
cid=0

- 执行$normalResult['series'][0]['type'] @liquidFill
- 执行$normalResult['tooltip']['show'] @1
- 执行$zeroResult['series'][0]['data'][0] @0
- 执行$highResult['series'][0]['data'][0] @0.95
- 执行$lowResult['series'][0]['data'][0] @0.05

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 步骤1：测试正常水球图的series类型
$normalResult = $chartTest->genWaterpoloTest('normal');
r($normalResult['series'][0]['type']) && p() && e('liquidFill');

// 步骤2：测试正常水球图的tooltip显示
r($normalResult['tooltip']['show']) && p() && e('1');

// 步骤3：测试分母为零的边界值
$zeroResult = $chartTest->genWaterpoloTest('zeroPercent');
r($zeroResult['series'][0]['data'][0]) && p() && e('0');

// 步骤4：测试高百分比数据（95%）
$highResult = $chartTest->genWaterpoloTest('highPercent');
r($highResult['series'][0]['data'][0]) && p() && e('0.95');

// 步骤5：测试低百分比数据（5%）
$lowResult = $chartTest->genWaterpoloTest('lowPercent');
r($lowResult['series'][0]['data'][0]) && p() && e('0.05');